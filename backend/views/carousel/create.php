<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Carousel */

$this->title = '新增轮播项';
$this->params['breadcrumbs'][] = ['label' => '轮播项管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '新增';
?>
<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
