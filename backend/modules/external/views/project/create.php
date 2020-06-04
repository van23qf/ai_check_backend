<?php

use yii\helpers\Html;
use backend\modules\external\models\Project;


/* @var $this yii\web\View */
/* @var $model backend\modules\external\models\Project */

$this->title = '新增'.Project::$modelName;
$this->params['breadcrumbs'][] = ['label' => Project::$modelName.'管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '新增';
?>
<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-default', 'data-dismiss'=>'modal']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
