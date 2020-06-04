<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\behaviors\AttributeBehavior;
//

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $serverId
 * @property string $filepath
 * @property string $created_at
 */
class Image extends \common\components\ActiveRecord
{
    public $modelName = '图片';
    public $fileAttributes = ['filepath'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serverId', 'filepath'], 'required'],
            [['created_at'], 'safe'],
            [['serverId', 'filepath'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serverId' => 'serverId',
            'filepath' => '文件路径',
            'created_at' => '上传时间',
        ];
    }
}
