<?php

namespace common\models;

use common\components\ActiveRecord;
use common\components\behaviors\DatetimeBehavior;

/**
 * This is the model class for table "update_log".
 *
 * @property integer $id
 * @property integer $module_id
 * @property string $model
 * @property string $before_update
 * @property string $after_update
 * @property string $created_at
 * @property string $updated_at
 */
class UpdateLog extends \common\components\ActiveRecord
{
    public $modelName = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'update_log';
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
            [['before_update', 'after_update'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['model', 'user_name', 'action'], 'string', 'max' => 50],
            [['module_id'], 'string', 'max' => 20],
            [['user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module_id' => '模块ID',
            'model' => '模型',
            'before_update' => '修改前',
            'after_update' => '修改后',
            'user_id' => '操作人ID',
            'user_name' => '操作人',
            'action' => '行为',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static function create($module_id, $model_name, $before_update, $after_update, $action = '', $user_name = "guest", $user_id = 0)
    {
        $log = new UpdateLog;
        $log->module_id = $module_id;
        $log->model = $model_name;
        $log->before_update = $before_update;
        $log->after_update = $after_update;
        $log->user_name = $user_name;
        $log->user_id = $user_id;
        $log->action = $action;
        $log->save();
    }
}
