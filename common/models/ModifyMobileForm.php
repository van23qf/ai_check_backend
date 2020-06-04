<?php

namespace api\modules\xlc\models;

use common\models\SmsList;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class ModifyMobileForm extends Model {

    public $realname;
    public $idsn;
    public $mobile;
    public $verifycode;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mobile','verifycode'],'required'],

            ['mobile', 'match', 'pattern' => '/^1[3|5|7|8|][0-9]{9}$/', 'message' => '手机号码格式不正确'],
            ['mobile', 'unique', 'targetClass' => 'common\models\User', 'message' => '手机号码已存在'],

            ['verifycode', 'filter', 'filter' => 'trim'],
            ['verifycode', 'validateVerifycode'],
        ];
    }

    public function attributeLabels() {
        return [
            'mobile' => '新手机号码',
            'verifycode'=>'手机验证码'
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
}
