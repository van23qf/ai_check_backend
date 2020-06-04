<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\behaviors\AttributeBehavior;


/**
 * This is the model class for table "wechat_page".
 *
 * @property integer $id
 * @property integer $config_id
 * @property string $title
 * @property string $summary
 * @property string $image
 * @property string $icon
 * @property string $content
 * @property string $remark 
 * @property string $user_id 
 */
class Page extends \common\components\ActiveRecord
{
    public $modelName = '微信单页';
    public $fileAttributes = ['image'];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_page';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['user_id'],
                ],
                'value' => function ($event) {
                    return Yii::$app->user->id;
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
            [['config_id', 'title', 'summary', 'content'], 'required'],
            [['image'], 'required', 'enableClientValidation'=>false],
            [['config_id'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['summary'], 'string', 'max' => 200],
            [['image', 'icon', 'remark'], 'string', 'max' => 100],
            [['project_id'],'safe'],
        ];
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_id' => '所属配置ID',
            'title' => '页面标题',
            'summary' => '页面摘要',
            'image' => '标题图片',
            'icon' => '图标',
            'content' => '页面内容',
            'remark' => '备注',
            'project_id' => '对应子项目',
        ];
    }

    // 获取图片列表
    public function getImages()
    {
        $regex = '%<img[^>]*?src="(.*?)"[^>]*?>%i';
        $matches = [];
        $content=preg_match_all($regex, $this->content, $matches);
        $images = $matches[1];
        foreach($images as $key=>$image){
            if(strpos($image, 'http://')===false){
                $images[$key] = Yii::$app->request->hostInfo.$image;
            }
        }

        return $images;
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['content'] = function ($model) {
            return str_replace('src="/upload/', 'src="' . Yii::$app->params['api.url'] . '/upload/', $model->content);
        };

        return $fields;
    }

    public function extraFields()
    {
        return ['images'];
    }
}
