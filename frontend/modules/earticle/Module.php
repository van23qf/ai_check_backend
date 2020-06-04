<?php

namespace frontend\modules\earticle;
use yii;

/**
 * earticle module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\earticle\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->params['brandLabel'] = '医文社';
        Yii::$app->setComponents([
            'user' => [
                'class'=>'yii\web\User',
                'identityClass' => 'api\modules\earticle\models\Member',
                'enableAutoLogin' => true,
                'identityCookie' => ['name' => '_identity-earticle', 'httpOnly' => true],
            ],
        ]);
    }
}
