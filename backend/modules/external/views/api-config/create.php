<?php

use yii\helpers\Html;
use backend\modules\external\models\ApiConfig;


/* @var $this yii\web\View */
/* @var $model backend\modules\external\models\ApiConfig */

$this->title = '新增'.ApiConfig::$modelName;
$this->params['breadcrumbs'][] = ['label' => ApiConfig::$modelName.'管理', 'url' => ['index', 'project_id'=>$_GET['project_id']]];
$this->params['breadcrumbs'][] = '新增';
?>
<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>', ['index', 'project_id'=>$_GET['project_id']], ['class' => 'btn btn-default', 'data-dismiss'=>'modal']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
