<?php

namespace common\models;

use Yii;

// use common\components\ActiveRecord;
// use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "wechat_menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $config_id
 * @property integer $sortnum
 * @property string $name
 * @property string $type
 * @property string $view_url
 * @property string $event_key
 */
class WechatMenu extends \common\components\ActiveRecord
{
    public $modelName = '微信菜单';

    public $types = [
        'sub_button' => '弹出子菜单',
        // 'notice' => '通知公告',
        // 'slides' => '幻灯播放',
        'column' => '文章栏目',
        'click' => '点击推事件',
        'view' => '跳转URL',
        'scancode_push' => '扫一扫',
        'miniprogram' => '跳转小程序',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_menu';
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->flush();
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        Yii::$app->cache->flush();
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'sortnum', 'name', 'type'], 'required'],
            [['config_id', 'parent_id', 'sortnum', 'model_id'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['type'], 'string', 'max' => 20],
            [['view_url', 'pagepath', 'miniurl'], 'string', 'max' => 200],
            // [['view_url'], 'url'],
            [['event_key', 'miniappid'], 'string', 'max' => 50],
            ['view_url', function ($attribute) {
                if ($this->type == 'view' && empty($this->view_url)) {
                    $this->addError('view_url', '跳转地址不能为空');
                }
            }, 'skipOnEmpty' => false, 'skipOnError' => false],
            ['event_key', function ($attribute) {
                if ($this->type == 'click' && empty($this->event_key)) {
                    $this->addError('event_key', '事件关键字不能为空');
                }
            }, 'skipOnEmpty' => false, 'skipOnError' => false],
            ['model_id', function ($attribute) {
                if (($this->type == 'column' || $this->type == 'slides' || $this->type == 'promotion') && empty($this->model_id)) {
                    $this->addError('model_id', '对象ID不能为空');
                }
            }, 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }

    public function getParent()
    {
        return $this->hasOne(WechatMenu::className(), ['id' => 'parent_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '上级菜单',
            'config_id' => '所属配置',
            'sortnum' => '排序数字',
            'name' => '菜单名称',
            'type' => '菜单类型',
            'view_url' => '跳转地址',
            'event_key' => '事件关键字',
            'model_id' => '对象ID',
            'miniappid' => '小程序ID',
            'pagepath' => '小程序页面路径',
            'miniurl' => '小程序备用链接',
        ];
    }
}
