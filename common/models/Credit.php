<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "credit".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property string $remark
 * @property integer $credit
 * @property string $created_at
 */
class Credit extends \common\components\ActiveRecord
{

    public $modelName = '微信积分历史';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'credit';
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
            [['user_id', 'amount'], 'integer'],
            [['amount', 'remark'], 'required'],
            [['created_at'], 'safe'],
            [['remark'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '所属微信用户',
            'amount' => '变动积分数量',
            'remark' => '变动原因',
            'credit' => '当时积分余额',
            'created_at' => '发生时间',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->credit = Yii::$app->db->createCommand("SELECT credit FROM wechat_user WHERE id= {$this->user_id} FOR UPDATE")->queryScalar();
            $this->credit += $this->amount;
            return true;
        } else {
            return false;
        }
    }

    // 调整微信用户余额
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert){
            Yii::$app->db->createCommand("UPDATE wechat_user SET credit = credit + {$this->amount} WHERE id= {$this->user_id}")->execute();
        }
    }
}
