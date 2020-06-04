<?php

namespace frontend\modules\baxs;

use yii;

/**
 * earticle module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\baxs\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->params['brandLabel'] = '博爱新生';
        Yii::$app->setComponents([
            'user' => [
                'class' => 'yii\web\User',
                'identityClass' => 'api\modules\baxs\models\Volunteer',
                'enableAutoLogin' => true,
                'identityCookie' => ['name' => '_identity-baxs', 'httpOnly' => true],
            ],
        ]);
    }
}
