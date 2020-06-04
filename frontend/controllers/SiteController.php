<?php

namespace frontend\controllers;

use  Yii;
use frontend\components\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }
}
