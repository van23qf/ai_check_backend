<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;

// use common\components\ActiveRecord;
// use yii\behaviors\AttributeBehavior;
//

/**
 * This is the model class for table "wechat_user".
 *
 * @property integer $id
 * @property integer $config_id
 * @property integer $from_user_id
 * @property string $openid
 * @property string $unionid
 * @property string $push_alias
 * @property string $subscribe_at
 * @property string $unsubscribe_at
 * @property string $nickname
 * @property string $sex
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $headimgurl
 * @property string $remark
 * @property integer $groupid
 * @property decimal $balance
 * @property string $mobile
 * @property string $realname
 * @property string $idsn
 * @property string $address
 * @property string $title
 * @property string $photo
 * @property string $sign
 * @property integer $credit
 * @property bool $is_certified
 */
class User extends \common\components\ActiveRecord implements IdentityInterface
{
    public $modelName = '微信用户';

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $invite_count;
    public $onair; //是否正在直播
    private $old_from_user_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_user';
    }

    // public function behaviors()
    // {
    //     return [
    //         [
    //             'class' => AttributeBehavior::className(),
    //             'attributes' => [
    //                 ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
    //             ],
    //             'value' => function ($event) {
    //                 return date('Y-m-d H:i:s');
    //             },
    //         ],
    //     ];
    // }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'openid', 'subscribe_at'], 'required'],
            [['realname', 'company', 'post', 'mobile', 'idsn', 'address'], 'required', 'on' => 'enroll'],
            [['config_id', 'groupid', 'mobile', 'credit', 'from_user_id'], 'integer'],
            [['subscribe_at', 'unsubscribe_at', 'birthdate'], 'safe'],
            [['openid', 'nickname', 'rank', 'company', 'post', 'unionid'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
            [['city', 'province', 'country'], 'string', 'max' => 10],
            [['remark', 'address', 'title', 'photo'], 'string', 'max' => 100],
            [['headimgurl', 'sign'], 'string', 'max' => 200],
            [['mobile'], 'string', 'max' => 11, 'min' => 11],
            [['realname', 'idsn'], 'string', 'max' => 18],
            [['push_alias'], 'string', 'max' => 128],
            [['balance'], 'number'],
            [['is_teacher'], 'boolean'],
            [['access_token'], 'unique'],
            [['realname'], 'match', 'pattern' => '/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})a-zA-Z_\d]*$/u', 'message' => '{attribute}由字母，汉字，数字，下划线组成，且不能以数字和下划线开头。'],
        ];
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }

    public function getGender()
    {
        return $this->sex == 1 ? '男' : '女';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_id' => '所属配置ID',
            'openid' => 'OPENID',
            'subscribe' => '是否关注',
            'subscribe_at' => '关注时间',
            'unsubscribe_at' => '取消关注时间',
            'nickname' => '微信昵称',
            'sex' => '性别',
            'gender' => '性别',
            'city' => '城市',
            'province' => '省份',
            'country' => '国家',
            'headimgurl' => '头像',
            'remark' => '用户备注',
            'groupid' => '分组ID',
            'balance' => '账户余额',
            'mobile' => '手机号码',
            'realname' => '姓名',
            'birthdate' => '生日',
            'company' => '公司名称',
            'post' => '职位',
            'scene_id' => '关注场景',
            'rank' => '会员级别',
            'idsn' => '身份证号',
            'address' => '通讯地址',
            'is_teacher' => '是否导师',
            'is_certified' => '是否认证',
            'title' => '导师头衔',
            'photo' => '导师照片',
            'sign' => '学习宣言',
            'credit' => '积分',
            'push_alias' => 'APP推送ALIAS',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByMobile($mobile)
    {
        return static::findOne(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['member.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['auth_key']);
        unset($fields['password_hash']);
        unset($fields['password_reset_token']);
        unset($fields['access_token']);
        return $fields;
    }

    //来源用户
    public function getFromUser()
    {
        return $this->hasOne(User::className(), ['id' => 'from_user_id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->old_from_user_id = $this->from_user_id;
    }
}
