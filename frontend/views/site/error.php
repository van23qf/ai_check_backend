<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>

<div class="middle-box text-center animated fadeInDown">
    <h1><?= Html::encode($exception->statusCode) ?></h1>
    <h3 class="font-bold"><?= nl2br(Html::encode($message)) ?></h3>

    <div class="error-desc">
        抱歉，发生了一个问题，请联系管理员获得帮助。
    </div>
</div>