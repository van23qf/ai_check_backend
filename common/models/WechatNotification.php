<?php

namespace common\models;

use common\components\ActiveRecord;
use common\components\behaviors\SerializeAttributesBehavior;
use console\jobs\WechatNotificationJob;
use Yii;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "wechat_notifcation".
 *
 * @property integer $id
 * @property integer $config_id
 * @property integer $user_id
 * @property string $type
 * @property string $openid
 * @property string $data
 * @property string $status
 * @property string $created_at
 * @property string $send_at
 */
class WechatNotification extends \common\components\ActiveRecord
{
    public $modelName = '微信模板消息';

    const STATUS_PENDING = '等待发送';
    const STATUS_SUCCESS = '发送成功';
    const STATUS_FAILED = '发送失败';

    public $delay = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_notification';
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
            [
                'class' => SerializeAttributesBehavior::className(),
                'convertAttr' => ['data' => 'json'],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'type', 'openid', 'data', 'status'], 'required'],
            [['data', 'exception'], 'safe'],
            [['created_at', 'send_at'], 'safe'],
            [['type'], 'string', 'max' => 50],
            [['openid'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
        ];
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['openid' => 'openid']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_id' => '所属配置ID',
            'user_id' => '所属用户ID',
            'type' => '消息类型',
            'openid' => 'OPENID',
            'data' => '模板数据',
            'status' => '状态',
            'created_at' => '生成时间',
            'send_at' => '发送时间',
            'preview' => '消息预览',
            'retry_times' => '重试次数',
            'exception' => '异常信息',
        ];
    }

    public function getPreview()
    {
        $template = $this->config->mpmsg_templates[$this->type];
        $content = $template['attributes']['content'];
        foreach ($this->data['data'] as $name => $attribute) {
            if (is_array($attribute)) {
                $attribute = $attribute['value'];
            }

            $content = str_replace('{{' . $name . '.DATA}}', $attribute, $content);
        }
        $content .= "\n\n打开URL：" . $this->data['url'];
        return $content;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            Yii::$app->queue->delay($this->delay)->push(new WechatNotificationJob([
                'id' => $this->id,
            ]));
        } 
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $user = User::findOne(['openid'=>$this->openid]);
        if($user!==null){
            $this->user_id = $user->id;
        }
        return true;
    } 
}
