<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\components\ActiveRecord;
use common\components\behaviors\DatetimeBehavior;

/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property integer $config_id
 * @property string $name
 * @property string $group
 * @property string $filepath
 * @property integer $sortnum
 */
class Document extends \common\components\ActiveRecord
{
    public $modelName = '项目材料';
    public $fileAttributes = ['filepath'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
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
            [['config_id', 'sortnum'], 'integer'],
            [['filepath'], 'required', 'enableClientValidation'=>false],
            [['config_id', 'name', 'group'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['group'], 'string', 'max' => 20],
            [['filepath'], 'file', 'maxSize' => 5*1024*1024, 'extensions'=>['pdf']],
            [['project_id'],'safe']
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
            'name' => '材料名称',
            'group' => '材料分组',
            'filepath' => '文件路径',
            'sortnum' => '排序数字',
            'project_id' => '对应子项目'
        ];
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['is_new'] = function($model){
            if( time() - strtotime($this->updated_at) < 30*24*3600){
                return true;
            }
            return false;
        };
        return $fields;
    }
}
