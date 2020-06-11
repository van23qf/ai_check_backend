<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use backend\modules\external\models\ApiConfig;


/* @var $this yii\web\View */
/* @var $model backend\modules\external\models\ApiConfig */
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
    <?= $form->field($model, 'project')->dropDownList(ApiConfig::getProjectData()) ?>
    <?= $form->field($model, 'api_name')->dropDownList(ApiConfig::$apiTags) ?>
    <?= $form->field($model, 'api_provider')->dropDownList(ApiConfig::$apiProviders) ?>
    <?= $form->field($model, 'appid')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'appsecret')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'appcode')->textInput(['maxlength' => true]) ?>
    <div class="box-footer">
        <a data-dismiss="modal" href="javascript:history.back();" class="btn btn-default">取消</a>
        <?=  Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
    <?php  ActiveForm::end(); ?>
</div>
<script>
    $(function () {
        $("#apiaccess-expired_at").datepicker();
    });
</script>