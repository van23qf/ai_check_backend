<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\components\ActiveRecord;
use common\components\behaviors\DatetimeBehavior;

/**
 * This is the model class for table "xlc_question".
 *
 * @property integer $id
 * @property integer $patient_id
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class Question extends \common\components\ActiveRecord
{
    const SHOW_TRUE = "展示";
    const SHOW_FALSE = "不展示";

    public $modelName = '患者提问';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
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
            [['patient_id', 'content', 'status','config_id'], 'required'],
            [['patient_id'], 'integer'],
            [['answer','is_good','created_at', 'updated_at','status','config_id','patient_id'], 'safe'],
            [['content'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patient_id' => '患者ID',
            'content' => '内容',
            'status' => '是否展示',
            'is_good' => '是否加精',
            'answer' => '回复',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getPatient(){
        return $this->hasOne(\api\modules\xlc\models\Patient::className(),['id'=>'patient_id']);
    }

    public function getUser(){
        return $this->hasOne(\common\models\User::className(), ['id' => 'wechat_user_id'])->via('patient');
    }

    public function extraFields(){
        return ['patient','user'];
    }
}
