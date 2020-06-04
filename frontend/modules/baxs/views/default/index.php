<?php
use yii\bootstrap\ActiveForm;

$this->title = '公益专员';
print_r($files);
?>
<script type="text/javascript">
    FORM_DATA = {'<?=Yii::$app->request->csrfParam?>': '<?=Yii::$app->request->csrfToken?>', };
</script>
<style>
    #claimapplication-categories .checkbox {float:left; padding-right:20px;}
    .wu-example:after {
        content: "";
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
                        <h4>公益专员登录</h4>
                    </div>
                <?php if (Yii::$app->user->isGuest): ?>
                    <div class="form-group">
                        <?=$form->field($model, 'name')->label('姓名')?>
                    </div>

                    <div class="form-group">
                        <?=$form->field($model, 'mobile')->label('手机号码')->textInput(['type' => 'tel'])?>
                    </div>
                     <button type="submit" class="btn btn-info btn-lg btn-block">登录</button>
                <?php else: ?>
                    <div class="">
                        欢迎您， <?=Yii::$app->user->identity->name?>
                    </div>
                <?php endif;?>
                    <div class="form-group">
                        <ol>
                            <?php foreach ($patients as $key => $v) {
    ?>
                                <li><?php echo $v['name']; ?> <?php echo $v['mobile']; ?></li>
                            <?php
}?>
                        </ol>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>

<script>

</script>