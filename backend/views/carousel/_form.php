<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Carousel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
    <?php  $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal', 'enctype'=>'multipart/form-data'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class=\"col-sm-10\">{input}\n{hint}\n{error}</div>",
            'labelOptions'=>['class'=>'col-sm-2 control-label'],
        ],
    ]); ?>
     <?= $form->field($model, 'group')->checkboxlist($model->groups, ['itemOptions'=>['class'=>'minimal']]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'image')->widget(backend\widgets\FileInput::classname())->hint('支持JPG、PNG格式，不要超过500KB为宜') ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sortnum')->textInput(['type'=>'number']) ?>
    <div class="box-footer">
        <a href="javascript:history.back();" class="btn btn-default">取消</a>
        <?=  Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
    <?php  ActiveForm::end(); ?>
</div>