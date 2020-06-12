<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use backend\modules\external\models\ApiAccess;


/* @var $this yii\web\View */
/* @var $model backend\modules\external\models\ApiAccess */
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
    <?= Html::hiddenInput('ApiAccess[project_id]', $model->project_id) ?>
    <?= $form->field($model, 'api_tag')->dropDownList(\backend\modules\external\models\ApiConfig::$apiTags) ?>
    <?= $form->field($model, 'is_enabled')->dropDownList(['1' => '启用', '0'=>'关闭']) ?>
    <?= $form->field($model, 'expired_at', ['inputOptions'=>['class'=>'form-control']])->textInput() ?>

    <div class="box-footer">
        <a data-dismiss="modal" href="javascript:history.back();" class="btn btn-default">取消</a>
        <?=  Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
    <?php  ActiveForm::end(); ?>
</div>
<script>
    $(function () {
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            laydate.render({
                elem: '#apiaccess-expired_at'
                ,type: 'datetime'
            });
        });
    });
</script>