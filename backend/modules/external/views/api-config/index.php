<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\modules\external\models\ApiConfig;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\external\models\ApiConfig */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ApiConfig::$modelName.'管理';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->pagination->pageSize= Yii::$app->config->get('backend_pagesize', 20);
?>


<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <!-- Check all button -->
                <!-- <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button> -->
                <div class="btn-group">
                    <?= Html::a('<i class="fa fa-pencil-square-o"></i>', ['create?project_id='.$_GET['project_id']], ['class' => 'btn btn-primary']) ?>
                </div>
                <!-- /.btn-group -->
                <a type="button" class="btn btn-default" href="javascript:window.location.reload()"><i class="fa fa-refresh"></i></a>
                <div class="visible-lg-block pull-right">
                    <!-- <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div> -->
                                        <?=  $this->render('_search', [
                        'model' => $searchModel,
                    ]); ?>
                                        <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
            </div>
            <!-- /.box-header -->
            <?=  GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "<div class=\"box-body table-responsive\">{items}</div>\n<div class=\"box-footer clearfix\"><div class=\"row\"><div class=\"col-xs-12 col-sm-7\">{pager}</div><div class=\"col-xs-12 col-sm-5 text-right\">{summary}</div></div></div>",
                'tableOptions'=>['class'=>'table table-bordered table-hover'],
                'summary'=>'第{page}页，共{pageCount}页，当前第{begin}-{end}项，共{totalCount}项',
                'filterModel'=>null,
                'pager'=>[
                    'class'=>'backend\widgets\LinkPager',
                    'options' => [
                        'class' => 'pagination pagination-sm no-margin',
                    ],
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    ['label'=>'项目ID',  'value' => 'projectone.project_name' ],
                    ['label'=>'应用名称',  'value' => 'projectone.app_name' ],
                    ['label'=>'接口名称',  'value' => function($model){
                        return ApiConfig::$apiTags[$model->api_name];
                    }],
                    'appid',
                    'appsecret',
                    'appcode',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'操作',
                        'headerOptions'=>['style'=>'width:150px'],
                        'buttonOptions'=>['class'=>'btn btn-default btn-sm'],
                        'template'  =>  '{update} {delete}',
                    ],
            ],
        ]); ?>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>