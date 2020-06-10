<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\external\models\ApiAccess */
use backend\modules\external\models\ApiAccess;

$this->title = '修改' .ApiAccess::$modelName. ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '管理'.ApiAccess::$modelName, 'url' => ['index', 'project_id' => $model->project_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['index', 'project_id' => $model->project_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="box box-info">
    <div class="box-header">
        <?=  Html::a('<i class="fa fa-trash-o"></i>', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除该项目吗？',
                'method' => 'post',
            ],
        ]) ?>
        <!-- /.btn-group -->
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
