<?php

namespace common\models;

use common\components\ActiveRecord;
use common\components\behaviors\SerializeAttributesBehavior;
use common\models\WechatMenu as Menu;
use EasyWeChat\Factory;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\base\UserException;

/**
 * This is the model class for table "wechat_config".
 *
 * @property integer $id
 * @property string $appid
 * @property string $appsecret
 * @property string $token
 * @property string $encodingAesKey
 * @property string $username
 * @property string $pay_appid
 * @property string $pay_appsecret
 * @property string $pay_mch_id
 * @property string $pay_token
 * @property string $pay_encodingAesKey
 * @property string $pay_key
 * @property string $created_at
 * @property string $apiclient_cert
 * @property string $apiclient_key
 * @property string $logo
 * @property string $module_id
 * @property string $notifications
 * @property string $frontend_url
 * @property string $accesstoken_api_url
 * @property integer $is_closed
 * @property boolean $is_enabled
 * @property string $omnipotent_code
 */
class WechatConfig extends \common\components\ActiveRecord
{
    public $modelName = '微信配置';

    const KIND_SERVICE = '服务号';
    const KIND_SUBSCRIBE = '订阅号';
    const KIND_MINIAPP = '小程序';
    const KIND_MOBILEAPP = '移动应用';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_config';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function ($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
            // 生成模板消息配置
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['mpmsg_templates'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['mpmsg_templates'],
                ],
                'value' => function ($event) {
                    $templates_config = StringHelper::explode($this->notifications, "\n", true, true);
                    if (empty($this->mpmsg_templates)) {
                        $this->mpmsg_templates = [];
                    }
                    if (count($templates_config) != count($this->mpmsg_templates)) {
                        $wechat = $this->wechatApp;
                        // 删除已有模板
                        foreach ($this->mpmsg_templates as $template) {
                            $wechat->template_message->deletePrivateTemplate($template['template_id']);
                        }
                        // 重新添加模板
                        $mpmsg_templates = [];
                        try {
                            foreach ($templates_config as $config) {
                                list($name, $template_id_short) = StringHelper::explode($config, ' ', true, true);
                                $result = $wechat->template_message->addTemplate($template_id_short);
                                $mpmsg_templates[$name]['template_id'] = $result['template_id'];
                            }

                            $data = $wechat->template_message->getPrivateTemplates();
                            foreach ($data['template_list'] as $item) {
                                foreach ($mpmsg_templates as $name => $attr) {
                                    if ($attr['template_id'] == $item['template_id']) {
                                        unset($item['template_id']);
                                        $mpmsg_templates[$name]['attributes'] = $item;
                                        break;
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            Yii::error($e);
                        }

                        return $mpmsg_templates;
                    } else {
                        return $this->mpmsg_templates;
                    }
                },
            ],
            [
                'class' => SerializeAttributesBehavior::className(),
                'convertAttr' => ['mpmsg_templates' => 'json'],
            ],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->flush();
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        Yii::$app->cache->flush();
        parent::afterDelete();
    }

    public function getMenus($parent_id)
    {
        return $this->hasMany(Menu::className(), ['config_id' => 'id'])->where(['parent_id' => $parent_id]);
    }

    public function getMenuData($parent_id = 0)
    {
        $data = [];
        foreach ($this->getMenus($parent_id)->orderBy(['sortnum' => SORT_DESC])->all() as $menu) {
            $item = [];

            if ($menu->type == 'column') {
                $item['type'] = 'view';
                $item['name'] = $menu->name;

                $item['url'] = Yii::$app->params['frontend.url'] . '/article/index?config_id=' . $this->id . '&column_id=' . $menu->model_id;
            } elseif ($menu->type == 'click') {
                $item['type'] = 'click';
                $item['name'] = $menu->name;
                $item['key'] = $menu->event_key;
            } elseif ($menu->type == 'view') {
                $item['type'] = 'view';
                $item['name'] = $menu->name;

                $url = $menu->view_url;
                if(stripos($url, 'http://')===false && stripos($url, 'https://')===false){
                    $prefix = $this->is_open_platform ? $this->appid:$this->module_id;
                    $url = str_replace('wechat', $prefix, Yii::$app->params['wechat.url']).$menu->view_url;
                }

                $item['url'] = $url;
            } elseif ($menu->type == 'scancode_push') {
                $item['type'] = 'scancode_push';
                $item['name'] = $menu->name;
                $item['key'] = $menu->event_key;
            } elseif ($menu->type == 'sub_button') {
                $item['name'] = $menu->name;
                $item['sub_button'] = $this->getMenuData($menu->id);
            } elseif ($menu->type == 'miniprogram') {
                $item['type'] = 'miniprogram';
                $item['appid'] = $menu->miniappid;
                $item['pagepath'] = $menu->pagepath;
                $item['name'] = $menu->name;
                $item['url'] = $menu->miniurl;
            }
            $data[] = $item;
        }
        return $data;
    }

    // 返回微信的菜单配置
    public function getMenuConfig()
    {
        try {
            $app = $this->wechatApp;
            $data = $app->menu->list();
            return $data;
        } catch (\Exception $e) {
            return "菜单配置获取失败，错误为：" . $e->getMessage();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['appid', 'appsecret', 'token', 'encodingAesKey', 'kind', 'name', 'remark', 'welcome_message', 'module_id'], 'required'],
            [['appid', 'appsecret', 'name', 'module_id'], 'required'],
            [['is_closed'], 'integer'],
            [['is_enabled'], 'boolean'],
            [['pay_mch_id', 'pay_key', 'name', 'module_id'], 'string', 'max' => 50],
            [['created_at', 'notifications'], 'safe'],
            [['appid', 'username'], 'string', 'max' => 20],
            [['kind'], 'string', 'max' => 10],
            [['omnipotent_code'], 'string', 'min' => 6 ,'max' => 6],
            [['accesstoken_api_url'], 'string', 'max' => 255],
            [['pap_domain', 'frontend_url', 'token', 'logo', 'email_account'], 'string', 'max' => 100],
            [['appsecret', 'encodingAesKey', 'pay_mch_id', 'pay_key', 'email_pwd', 'email_host', 'sms_sn', 'sms_pw', 'sms_sign'], 'string', 'max' => 50],
            [['welcome_message', 'remark', 'pause_message'], 'string', 'max' => 500],
            //[['appid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'kind' => '类型',
            'appid' => 'APPID',
            'appsecret' => 'APPSECRET',
            'token' => 'TOKEN',
            'encodingAesKey' => 'encodingAesKey',
            'username' => '微信号账号(gh_*)',
            'pay_mch_id' => '微信支付商户号',
            'pay_key' => '微信支付KEY',
            'apiclient_cert' => '微信商户证书文件',
            'apiclient_key' => '微信商户证书密钥',
            'created_at' => '添加时间',
            'remark' => '备注信息',
            'welcome_message' => '欢迎信息',
            'module_id' => '模块名称',
            'pap_domain' => 'PAP系统域名',
            'frontend_url' => '微信端URL',
            'notifications' => '模板消息配置',
            //'miniapp_app_id'=>'小程序APPID',
            //'miniapp_secret'=>'小程序APPsecret',
            'accesstoken_api_url' => '外部ACCESSTOKEN接口地址',
            'is_closed' => '项目状态',
            'pause_message' => '关闭提示',
            'email_account'=>'邮箱账号',
            'email_pwd'=>'邮箱密码',
            'email_host'=>'SMTP主机',
            'sms_sn'=>'短信账号',
            'sms_pw'=>'短信密码',
            'sms_sign'=>'短信签名',
            'is_enabled'=>'是否启用',
            'omnipotent_code'=>'万能code',
        ];
    }

    /**
     * 检查指定菜单类型是否存在
     * @param  string $type
     * @return boolean
     */
    public function checkMenuExists($type)
    {
        return Menu::find()->where(['config_id' => $this->id, 'type' => $type])->exists();
    }

    /**
     * 检查消息模板类型是否存在
     *
     * @param string $type
     * @return void
     */
    public function checkMsgTemplate($type){
        $templates_config = StringHelper::explode($this->notifications, "\n", true, true);
        foreach ($templates_config as $config) {
            list($name, $template_id_short) = StringHelper::explode($config, ' ', true, true);
            if(strcmp($type, $name)===0){
                return true;
            }
        }

        return false;
    }

    public function getWechatApp()
    {
        if($this->is_open_platform){
            if(!$this->authorizer_refresh_token)
                throw new UserException('未进行微信开放平台授权！');

            return $this->openPlatform->officialAccount($this->appid, $this->authorizer_refresh_token);
        }
        else{
            $config = [
                'debug' => false, // 如果设为true，将修改php的error_reporting级别
                'app_id' => $this->appid, // AppID
                'secret' => $this->appsecret, // AppSecret
                'token' => $this->token, // Token
                'aes_key' => $this->encodingAesKey,
                'response_type' => 'array',
                // 'cache'   => $cacheDriver,
                'log' => [
                    'level' => 'error',
                    'file' => Yii::$app->runtimePath . '/logs/wechat.log',
                ],
                'guzzle' => [
                    'verfiy' => false,
                ],
    
                'payment' => [
                    'merchant_id' => $this->pay_mch_id,
                    'key' => $this->pay_key,
                    'cert_path' => Yii::$app->basePath . '/../certs/' . $this->apiclient_cert, // XXX: 绝对路径！！！！
                    'key_path' => Yii::$app->basePath . '/../certs/' . $this->apiclient_key, // XXX: 绝对路径！！！！
                    // 'notify_url'         => '',       // 你也可以在下单时单独设置来想覆盖它
                ],
            ];
    
            $wechat = Factory::officialAccount($config);

            if ($this->accesstoken_api_url) {
                $accessToken = $wechat->access_token;
                $accessToken->setToken($this->getAccessToken($this->accesstoken_api_url));
            }

            return $wechat;
        }
    }

    public function getMiniApp()
    {
        if($this->is_open_platform){
            if(!$this->authorizer_refresh_token)
                throw new UserException('未进行微信开放平台授权！');

            return $this->openPlatform->miniProgram($this->appid, $this->authorizer_refresh_token);
        }
        else{
            $config = [
                'app_id' => $this->appid,
                'secret' => $this->appsecret,

                // 下面为可选项
                // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
                'response_type' => 'array',

                'log' => [
                    'level' => 'error',
                    'file' => Yii::$app->runtimePath . '/logs/miniapp.log',
                ],
            ];

            return Factory::miniProgram($config);
        }
    }

    public function getPayment()
    {
        $config = [
            'debug' => false, // 如果设为true，将修改php的error_reporting级别
            'app_id' => $this->appid, // AppID
            'mch_id' => $this->pay_mch_id,
            'key' => $this->pay_key,
            'cert_path' => Yii::$app->basePath . '/../certs/' . $this->apiclient_cert, // XXX: 绝对路径！！！！
            'key_path' => Yii::$app->basePath . '/../certs/' . $this->apiclient_key, // XXX: 绝对路径！！！！
            // 'notify_url'         => '',       // 你也可以在下单时单独设置来想覆盖它
            'rsa_public_key_path' => Yii::$app->basePath . '/../certs/public-' . $this->pay_mch_id . '.pem',
        ];

        return Factory::payment($config);
    }

    public function getOpenPlatform(){
        return Factory::openPlatform(Yii::$app->params['wechat_open_platform']);
    }


    public function getAccessToken($url)
    {
        Yii::info($url, 'api');
        $client = new \yii\httpclient\Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setOptions(['followLocation'=>true, CURLOPT_POSTREDIR=>3])
            ->setUrl($url)
            ->send();

        if ($response->isOk) {
            $result = $response->data;
            Yii::info($result, 'api');
            return $result['access_token'];
        } else {
            Yii::info($response->data,'api');
            throw new UserException('从外部系统获取access_token失败');
        }
    }
}
