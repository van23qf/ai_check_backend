<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\components\ActiveRecord;
use common\components\behaviors\DatetimeBehavior;
use common\components\behaviors\SerializeAttributesBehavior;

/**
 * This is the model class for table "carousel".
 *
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $url
 * @property integer $sortnum
 * @property string $created_at
 * @property string $updated_at
 */
class Carousel extends \common\components\ActiveRecord
{
    public $modelName = '轮播项';
    public $fileAttributes = ['image'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'carousel';
    }
    public function behaviors()
    {
        return [
            DatetimeBehavior::className(),
            [
                'class' => SerializeAttributesBehavior::className(),
                'convertAttr' => ['group' => 'set']
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['image'], 'required', 'enableClientValidation'=>false],
            [['sortnum'], 'integer'],
            [['created_at', 'updated_at', 'group'], 'safe'],
            [['title', 'image'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '轮播标题',
            'image' => '轮播图片',
            'url' => '链接地址',
            'sortnum' => '排序数字',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'group' => '所属分组',
        ];
    }

    public function getGroups()
    {
        $groups = explode(',', Yii::$app->config->get('carousel_groups'));
        return array_combine($groups, $groups);
    }
}
