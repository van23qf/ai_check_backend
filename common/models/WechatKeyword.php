<?php

namespace common\models;

use Yii;
// use common\components\ActiveRecord;
// use yii\behaviors\AttributeBehavior;
//

/**
 * This is the model class for table "wechat_keyword".
 *
 * @property integer $id
 * @property integer $config_id
 * @property string $keyword
 * @property string $message
 */
class WechatKeyword extends \common\components\ActiveRecord
{
    //

    public $modelName = '微信关键词';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_keyword';
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
            [['config_id', 'keyword', 'message'], 'required'],
            [['config_id'], 'integer'],
            [['message'], 'string'],
            [['keyword'], 'string', 'max' => 50]
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
            'config_id' => '所属配置',
            'keyword' => '关键词',
            'message' => '回复消息',
        ];
    }
}
