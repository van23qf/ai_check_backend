<?php

namespace common\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $mobile;
    public $password;
    public $confirm_password;
    public $verifycode;
    public $config_id;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'password', 'confirm_password'], 'required'],
            [['mobile', 'password', 'confirm_password'],'filter', 'filter' => 'trim'],
            ['password', 'string', 'min' => 6, 'max'=>20],
            ['password', 'compare', 'compareAttribute'=>'confirm_password'],
            ['mobile', 'match','pattern'=>'/^1\d{10}$/','message'=>'手机号码格式不正确'],
            ['mobile', 'unique', 'targetClass' => '\common\models\User', 'message' => '此{attribute}已经被注册，您可尝试登录'],
            ['verifycode', 'filter', 'filter' => 'trim'],
            ['verifycode', 'validateVerifycode'],
        ];
    }

    /**
     * 验证短信
     *
     * @param  [type] $attribute [description]
     * @param  [type] $params    [description]
     * @return [type]            [description]
     */
    public function validateVerifycode($attribute, $params)
    {
        if( !$this->hasErrors() )
        {
            $sms = Sms::find()
                ->where(['mobile'=>$this->mobile,'type'=>Sms::TYPE_VERIFYCODE])
                ->andWhere('DATE_ADD(created_at, INTERVAL 15 MINUTE) > NOW()')
                ->orderBy(['id'=>SORT_DESC])
                ->one();
            if( $sms )
            {
                if( $sms->verifycode != $this->verifycode )
                {
                    $this->addError($attribute,'验证码不正确或已过期，请重新获取');
                }
            }else{
                $this->addError($attribute,'您还没有提交短信验证');
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'mobile' => '手机号码',
            'password' => '设定密码',
            'confirm_password' => '确认密码',
        ];

    }

    public function register(){
        $config = WechatConfig::findOne($this->config_id);

        $model = new User;
        $model->config_id = $this->config_id;
        $model->mobile = $this->mobile;
        $model->setPassword($this->password);
        $model->access_token = md5(uniqid().$config->appid);
        $model->openid = '_blank';
        $model->nickname = $model->mobile;
        $model->status = User::STATUS_ACTIVE;
        $model->subscribe_at = date('Y-m-d H:i:s');

        if($model->validate() && $model->save()){
            return true;
        }
        return false;
    }

    public function getErrorstr()
    {
        foreach($this->errors as $val)
        {
            $ers[] = implode('', $val);
        }
        return implode('', $ers);
    }
}