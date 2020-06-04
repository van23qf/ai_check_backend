<?php

namespace backend\modules\external;

/**
 * external module definition class
 */
class Module extends \yii\base\Module
{
    public $name = '外部项目';
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\external\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
