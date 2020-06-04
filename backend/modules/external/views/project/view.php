<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\external\models\Project;

/* @var $this yii\web\View */
/* @var $model backend\modules\external\models\Project */

$this->title = Project::$modelName.'详情 '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'id', 'url' => ['index']];
$this->params['breadcrumbs'][] = Project::$modelName.'详情';
?>

<div class="box">
    <div class="box-header">
        <?=  Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="20%">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            'id',
            'project_name',
            'secret',
        ],
    ]) ?>

    </div>
    <!-- /.box-body -->
</div>