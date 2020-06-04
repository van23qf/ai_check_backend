<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifycode;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'on'=>'default'],
            [['username', 'verifycode'], 'required', 'on'=>'applogin'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            [['username','password', 'verifycode'], 'filter', 'filter' => 'trim'],
            // password is validated by validatePassword()
            ['password', 'validatePassword', 'on'=>'default'],
            ['verifycode', 'validateVerifycode', 'on'=>'applogin'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '无效的密码');
            }
        }
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
                ->where(['mobile'=>$this->username,'type'=>Sms::TYPE_VERIFYCODE])
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
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->access_token = md5(time().rand(1,1000000));
            $user->save(false);

            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
            if(!$this->_user && is_numeric($this->username) && strlen($this->username)==11){
                $this->_user = User::findByMobile($this->username);
            }
        }

        return $this->_user;
    }
}
