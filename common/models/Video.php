<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\components\ActiveRecord;
use common\components\behaviors\DatetimeBehavior;

/**
 * This is the model class for table "{{%video}}".
 *
 * @property integer $id
 * @property integer $config_id
 * @property integer $project_id
 * @property string $name
 * @property string $summary
 * @property string $image
 * @property string $filepath
 * @property string $group
 * @property integer $sortnum
 * @property string $created_at
 * @property string $updated_at
 */
class Video extends \common\components\ActiveRecord
{
    public static $modelName = '视频';
    public $fileAttributes = ['image'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%video}}';
    }
    public function behaviors()
    {
        return [
            DatetimeBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'name'], 'required'],
            [['config_id', 'project_id', 'sortnum'], 'integer'],
            [['image'], 'required', 'enableClientValidation'=>false],
            [['image'], 'image', 'extensions'=>['jpg', 'png', 'gif'], 'maxSize'=>2*1024*1024],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['summary'], 'string', 'max' => 200],
            [['image', 'filepath'], 'string', 'max' => 100],
            [['group'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_id' => Yii::t('app', '所属配置'),
            'project_id' => Yii::t('app', '对应子项目'),
            'name' => Yii::t('app', '视频名称'),
            'summary' => Yii::t('app', '视频简介'),
            'image' => Yii::t('app', '视频封面'),
            'filepath' => Yii::t('app', '文件路径'),
            'group' => Yii::t('app', '视频分组'),
            'sortnum' => Yii::t('app', '排序数字'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }
}
