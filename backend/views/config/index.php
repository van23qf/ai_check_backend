<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '系统设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation'=> false,
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'options'=>['role'=>'form']
            ]);?>
            <fieldset>
                <legend>APP设置</legend>
                <div class="form-group">
                    <label class="col-lg-3 control-label">ICONFONT CSS URL</label>
                    <div class="col-lg-9">
                        <input type="text" name="iconfont_css_url" class="form-control" value="<?php echo $config['iconfont_css_url'] ?>">
                        <p class="help-block">iconfont.cn 的css的URL</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">ICONFONT JS URL</label>
                    <div class="col-lg-9">
                        <input type="text" name="iconfont_js_url" class="form-control" value="<?php echo $config['iconfont_js_url'] ?>">
                        <p class="help-block">iconfont.cn 的js的URL</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-3 control-label">IOS最新版本</label>
                    <div class="col-lg-9">
                        <input type="text" name="ios_version" class="form-control" value="<?php echo $config['ios_version'] ?>">
                        <p class="help-block">已在appstore审核发布的最新构建版本号</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">IOS下载地址</label>
                    <div class="col-lg-9">
                        <input type="text" name="ios_appstore_url" class="form-control" value="<?php echo $config['ios_appstore_url'] ?>">
                        <p class="help-block">appstore的应用页面URL</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">ANDROID最新版本</label>
                    <div class="col-lg-9">
                        <input type="text" name="android_version" class="form-control" value="<?php echo $config['android_version'] ?>">
                        <p class="help-block">填写最新构建版本号</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">APK下载地址</label>
                    <div class="col-lg-9">
                        <input type="text" name="android_apk_url" class="form-control" value="<?php echo $config['android_apk_url'] ?>">
                        <p class="help-block">填写apk的下载地址</p>
                    </div>
                </div>
            </fieldset>

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-9">
                    <button type="submit" class="btn btn-sm btn-primary">保存设置</button>
                </div>
            </div>
        <?php ActiveForm::end();?>
    </div>
</div>