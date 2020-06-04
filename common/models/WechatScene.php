<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\components\ActiveRecord;
use common\components\behaviors\DatetimeBehavior;

/**
 * This is the model class for table "wechat_scene".
 *
 * @property integer $id
 * @property integer $config_id
 * @property string $name
 * @property string $redirect_url
 * @property string $image
 * @property string $summary
 * @property string $qrcode_ticket
 * @property string $qrcode_url
 */
class WechatScene extends \common\components\ActiveRecord
{
    public $modelName = '场景';
    public $fileAttributes = ['image'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_scene';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'name', 'redirect_url', 'summary'], 'required'],
            [['image'], 'required', 'enableClientValidation'=>false],
            [['config_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['image'], 'string', 'max' => 100],
            [['redirect_url', 'summary'], 'string', 'max' => 200],
            [['qrcode_ticket', 'qrcode_url'], 'string', 'max' => 255],
            [['redirect_url'], 'url'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_id' => '所属配置',
            'name' => '场景名称',
            'image' => '消息图片',
            'summary' => '消息内容',
            'redirect_url' => '跳转URL',
            'qrcode_ticket' => '二维码TICKET',
            'qrcode_url' => '二维码URL',
        ];
    }

    /**
     * 返回关注用户数
     * @return [type] [description]
     */
    public function getUserCount()
    {
        return User::find()->where(['scene_id'=>$this->id])->count();
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }
}
