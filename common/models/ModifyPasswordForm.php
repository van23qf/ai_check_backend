<?php

namespace api\modules\xlc\models;

use common\models\SmsList;
use Yii;
use yii\base\Model;
use common\models\User;


class ModifyPasswordForm extends Model {

    public $_user = false;

    public $orgpassword;
    public $password;
    public $chkpassword;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['orgpassword','password','chkpassword'],'required'],

            ['chkpassword', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入的密码不一致'],
            [['chkpassword','password'], 'filter', 'filter' => 'trim'],

            ['orgpassword','validatePassword'],
        ];
    }

    public function attributeLabels() {
        return [
            'orgpassword' => '原密码',
            'password'=>'新密码',
            'chkpassword' => '确认密码',
        ];
    }

    /**
     * 验证短信
     *
     * @param  [type] $attribute [description]
     * @param  [type] $params    [description]
     * @return [type]            [description]
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->orgpassword)) {
                $this->addError($attribute, '原始密码不正确');
            }
        }
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne(Yii::$app->user->identity->id);
        }

        return $this->_user;
    }
}
