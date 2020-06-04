<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Carousel */

$this->title = '轮播项详情 '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'id', 'url' => ['index']];
$this->params['breadcrumbs'][] = '轮播项详情';
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
            ['attribute'=>'group', 'value'=>implode('/', $model->group)],
            'title',
            ['attribute'=>'image', 'format'=>'raw', 'value'=>Yii::$app->formatter->asImage($model->image, ['height'=>200])],
            'url:url',
            'sortnum',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    </div>
    <!-- /.box-body -->
</div>