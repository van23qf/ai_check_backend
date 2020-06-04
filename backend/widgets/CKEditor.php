<?php
namespace backend\widgets;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

/**
 * 为CKEditor加入ckfinder支持
 */
class CKEditor extends \dosamigos\ckeditor\CKEditor
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->clientOptions = ArrayHelper::merge([
            'filebrowserUploadUrl' => $asset->baseUrl.'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            'filebrowserImageUploadUrl'=>  $asset->baseUrl.'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            'filebrowserFlashUploadUrl'=>  $asset->baseUrl.'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
            'font_names'=>'宋体/SimSun;新宋体/NSimSun;仿宋_GB2312/FangSong_GB2312;楷体_GB2312/KaiTi_GB2312;黑体/SimHei;微软雅黑/Microsoft YaHei;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS, cursive;Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;Verdana/Verdana, Geneva, sans-serif',
        ], $this->clientOptions);
    }
}