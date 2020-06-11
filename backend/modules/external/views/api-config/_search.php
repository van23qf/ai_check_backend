<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\external\models\ApiConfig;

/* @var $this yii\web\View */
/* @var $model backend\modules\external\models\ApiConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => [''],
        'options'=>['class'=>'form-inline', 'role'=>'form'],
        'fieldConfig'=>[
            'template'=>"{label}\n{input}\n",
            'labelOptions'=>['class'=>'sr-only'],
        ],
    ]); ?>
     <?= $form->field($model, 'api_name', ['labelOptions'=>['class'=>'sr-only'], 'inputOptions'=>['empty'=>'','class'=>'form-control', 'placeholder'=>'接口名称']])->dropDownList(ApiConfig::$apiTags) ?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
