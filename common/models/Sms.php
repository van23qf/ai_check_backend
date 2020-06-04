<?php

namespace common\models;

use common\components\ActiveRecord;
use console\jobs\SmsJob;
use Yii;
use yii\behaviors\AttributeBehavior;

use api\modules\rlxs\models\Doctor;

/**
 * This is the model class for table "sms".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $mobile
 * @property string $content
 * @property string $type
 * @property string $created_at
 * @property string $status
 * @property integer $retry_times
 * @property string $send_at
 */
class Sms extends \common\components\ActiveRecord
{
    public static $modelName = '短信记录';

    public $role = '';

    const STATUS_PENDING = '等待发送';
    const STATUS_SUCCESS = '发送成功';
    const STATUS_FAILED = '发送失败';

    const TYPE_MANUAL = '自定义号码';
    const TYPE_NOTICE = '系统通知';
    const TYPE_VERIFYCODE = '验证码';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms';
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
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['status'],
                ],
                'value' => function ($event) {
                    return self::STATUS_PENDING;
                },
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['id' => 'user_id']);
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'type'], 'required'],
            [['user_id', 'retry_times'], 'integer'],
            [['mobile', 'content'], 'string'],
            [['created_at', 'send_at','verifycode', 'exception'], 'safe'],
            [['type','content_type'], 'string', 'max' => 30],
            [['status'], 'string', 'max' => 10],
            ['mobile', function ($attribute) {
                if ($this->type == self::TYPE_MANUAL && empty($this->mobile)) {
                    $this->addError('mobile', '接收手机号不能为空');
                }
            }, 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '发送用户ID',
            'mobile' => '接收手机号',
            'content' => '短信内容',
            'type' => '短信类型',
            'created_at' => '添加时间',
            'status' => '发送状态',
            'retry_times' => '重试次数',
            'send_at' => '发送时间',
            'verifycode' => '验证码',
            'content_type'=>'内容分类',
            'exception' => '异常信息',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            Yii::$app->queue->push(new SmsJob([
                'id' => $this->id,
            ]));
        }
    }
}
