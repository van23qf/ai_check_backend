<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '文章上传';
$files = Yii::$app->session['files'];
print_r($files);
?>
<script type="text/javascript">
    UPLOADER_UPLOAD_URL = '<?php echo Url::to(['upload-article']); ?>';
    FORM_DATA = {'<?=Yii::$app->request->csrfParam?>': '<?=Yii::$app->request->csrfToken?>', };
</script>
<style>
    #claimapplication-categories .checkbox {float:left; padding-right:20px;}
    .wu-example:after {
        content: "word文档上传";
    }
    #uploader .placeholder {
        min-height: 350px;
    }
</style>
<div class="gray-bd">
    <div class="container">
        <div class="row">
            <?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'options' => ['class' => false],
        'template' => "{label}\n<div class=\"col-sm-3\">{input}\n{hint}\n{error}</div>",
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
    ],
]);?>
            <div class="col-md-10 col-sm-9">
                <div class="mainbody">
                    <div class="page-header">
                        <h4>会员登录</h4>
                    </div>
                <?php if (Yii::$app->user->isGuest): ?>
                    <div class="form-group">
                        <?=$form->field($model, 'mobile')->label('手机号码')->textInput(['type' => 'tel'])?>
                        <div class="form-group">
                            <button class="btn btn-default" id="code">获取验证码</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <?=$form->field($model, 'verifycode')->textInput(['type' => 'number'])?>
                    </div>
                <?php else: ?>
                    <div class="">
                        欢迎您， <?=Yii::$app->user->identity->name ? Yii::$app->user->identity->name : Yii::$app->user->identity->user->nickname?>（手机号码：<?=Yii::$app->user->identity->mobile?>）
                    </div>
                        <?=Html::button('退出', ['class' => 'btn btn-default', 'name' => 'logout', 'value' => 'logout', 'id' => 'logout'])?>
                    <div class="form-group">
                        <?=$form->field($model, 'entity_id')->dropdownlist(api\modules\earticle\models\Entity::instantiate([])->getListData('id', 'name'), ['prompt' => '', 'data-placeholder' => '选择病种分类', 'class' => 'form-control select2'])?>
                    </div>
                    <div class="form-group">
                        <?=$form->field($model, 'special')->textInput(['maxlength' => true])?>
                        <?=Html::button('新建专题', ['class' => 'btn btn-default', 'name' => 'special', 'value' => 'special', 'id' => 'create-special'])?>
                    </div>
                    <div class="form-group">
                        <?=$form->field($model, 'special_id')->dropdownlist(api\modules\earticle\models\Special::instantiate([])->getListData('id', 'name'), ['prompt' => '', 'data-placeholder' => '选择专题', 'class' => 'form-control select2'])?>
                    </div>
                    <div class="form-group">
                        <?=$form->field($model, 'amount')->textInput(['maxlength' => true, 'type' => 'number'])?>
                    </div>
                    <!-- <div class="form-group">
                        <?=$form->field($model, 'task_id')->dropdownlist(api\modules\eaccount\models\Task::instantiate([])->getListData('id', 'remark', null, '', ['status' => api\modules\eaccount\models\Task::STATUS_NEW, 'account_id' => $account->id]), ['prompt' => '普通上传', 'data-placeholder' => '选择任务', 'class' => 'form-control select2'])?>
                    </div> -->
                    <div class="page-header">
                        <h4>文档上传 <small>（仅支持docx格式）</small></h4>
                    </div>
                    <div class="ymt-article">
                        <div class="article-bd">
                            <div class="article-cont">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div id="uploader" class="wu-example">
                                            <div class="queueList">
                                                <div id="dndArea" class="placeholder">
                                                    <div id="filePicker"></div>
                                                    <p>或将文档拖到这里，可选择多个</p>
                                                </div>
                                            </div>
                                            <div class="statusBar" style="display:none;">
                                                <div class="progress">
                                                    <span class="text">0%</span>
                                                    <span class="percentage"></span>
                                                </div><div class="info"></div>
                                                <div class="btns">
                                                    <div id="filePicker2"></div><div class="uploadBtn">开始上传</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    <div class="ymt-article">
                        <div class="article-bd">
                            <div class="row">
                                <div class="col-sm-offset-4 col-sm-4">
                                    <button type="submit" class="btn btn-info btn-lg btn-block">提交</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end();?>
        </div>

    </div>
</div>

<script>
    $('#logout').click(function(event){
       $.ajax({
                url: "<?php echo Url::to(['logout']) ?>",
                type: 'POST',
                data: {},
                success: function (data) {

                }
            })
    })
    $('#create-special').click(function(event){
        if($('#upload-special').val()){
            var name = $.trim($("#upload-special").val());
            $.ajax({
                url: "<?php echo Url::to(['create-special']) ?>",
                type: 'POST',
                data: { name:name },
                success: function (data) {
                    console.log(data)
                },
                error: function(xhr) {
                    var json = JSON.parse(xhr.responseText)
                    alert(json.message);
                }
            })
        }
    })
    $('#code').click(function(event){
        event.preventDefault();
        var $this = $(this);
        var mobileNo = $.trim($("#upload-mobile").val());
        if (/^\d{11}$/.test(mobileNo) == false) {
           $("#upload-mobile").focus();
           alert("请输入手机号码");
           return;
        }

        $this.prop("disabled", true).addClass('disabled');

        $.ajax({
            url: "<?php echo Url::to(['sendverifycode']) ?>",
            type: 'POST',
            data: { mobile: mobileNo, SmsType: "3", '_csrf-frontend': '<?php echo Yii::$app->request->getCsrfToken() ?>'},
            success: function (data) {
                console.log(data)
                if (data.isValid == true) {
                    alert('手机短信验证码已发送');
                    var i = 60;
                    var t = null;

                    function timer() {
                        if (i === 0) {
                            $this.html("点击获取").prop("disabled", false).removeClass('disabled');
                            return;
                        }
                        $this.html(i + " 秒")
                        i--;
                        t = setTimeout(timer, 1000);
                    }

                    timer();

                } else {
                    $this.prop("disabled", false).removeClass('disabled');
                    if (data.resultContent != "") {
                        alert(data.resultContent);
                        return;
                    }
                }
            }
        })
    })
</script>