<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\CarouselSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="carousel-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options'=>['class'=>'form-inline', 'role'=>'form'],
        'fieldConfig'=>[
            'template'=>"{label}\n{input}\n",
            'labelOptions'=>['class'=>'sr-only'],
        ],
    ]); ?>
     <?= $form->field($model, 'id', ['labelOptions'=>['class'=>'sr-only'], 'inputOptions'=>['class'=>'form-control', 'placeholder'=>'ID']]) ?>
     <?= $form->field($model, 'title', ['labelOptions'=>['class'=>'sr-only'], 'inputOptions'=>['class'=>'form-control', 'placeholder'=>'轮播标题']]) ?>
     <?= $form->field($model, 'image', ['labelOptions'=>['class'=>'sr-only'], 'inputOptions'=>['class'=>'form-control', 'placeholder'=>'轮播图片']]) ?>
     <?php // echo $form->field($model, 'url', ['labelOptions'=>['class'=>'sr-only'], 'inputOptions'=>['class'=>'form-control', 'placeholder'=>'链接地址']]) ?>
     <?php // echo $form->field($model, 'sortnum', ['labelOptions'=>['class'=>'sr-only'], 'inputOptions'=>['class'=>'form-control', 'placeholder'=>'排序数字']]) ?>
     <?php // echo $form->field($model, 'created_at', ['labelOptions'=>['class'=>'sr-only'], 'inputOptions'=>['class'=>'form-control', 'placeholder'=>'添加时间']]) ?>
     <?php // echo $form->field($model, 'updated_at', ['labelOptions'=>['class'=>'sr-only'], 'inputOptions'=>['class'=>'form-control', 'placeholder'=>'更新时间']]) ?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
