<?php

namespace common\models;

use Yii;
// use common\components\ActiveRecord;
// use yii\behaviors\AttributeBehavior;


/**
 * This is the model class for table "wechat_column".
 *
 * @property integer $id
 * @property integer $config_id
 * @property string $name
 * @property string $summary
 */
class Column extends \common\components\ActiveRecord
{
    public $modelName = '微信栏目';
    public $fileAttributes = ['image'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_column';
    }

    // public function behaviors()
    // {
    //     return [
    //         [
    //             'class' => AttributeBehavior::className(),
    //             'attributes' => [
    //                 ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
    //             ],
    //             'value' => function ($event) {
    //                 return date('Y-m-d H:i:s');
    //             },
    //         ],
    //     ];
    // }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'name', 'summary'], 'required'],
            [['image'], 'required', 'enableClientValidation'=>false],
            [['config_id'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['summary', 'image'], 'string', 'max' => 100]
        ];
    }

    public function getConfig()
    {
        return $this->hasOne(WechatConfig::className(), ['id' => 'config_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_id' => '所属配置',
            'name' => '栏目名称',
            'summary' => '栏目介绍',
            'image' => '栏目图片',
        ];
    }

    public function prepare($wechat, $limit=7)
    {
        $articles = [];

        //上传图片素材
        $filename = Yii::getAlias('@webroot'.$this->image);
        $result = $wechat->material->uploadImage($filename);
        $media_id = $result['media_id'];
        $articles[] = new \EasyWeChat\Kernel\Messages\Article([
            'thumb_media_id'=>$media_id,
            'author'=>$this->config->merchant->name,
            'title'=>$this->name,
            'content_source_url'=>Yii::$app->params['wechat.url']. \yii\helpers\Url::toRoute(['/article/index', 'column_id'=>$this->id, 'config_id'=>$this->config_id]),
            'content'=>$this->summary,
            'digest'=>$this->summary,
            'show_cover_pic'=>1,
        ]);

        $models = Article::find()->where(['column_id'=>$this->id])->limit($limit)->orderBy(['id'=>SORT_DESC])->all();
        foreach($models as $model){
            //上传图片素材
            $filename = Yii::getAlias('@webroot'.$model->image);
            $result = $wechat->material->uploadImage($filename);
            $media_id = $result['media_id'];

            // 文章内容中图片的上传
            $regex = '%<img[^>]*?src="/(.*?)"[^>]*?>%i';
            $content=preg_replace_callback($regex, function ($matches) use ($wechat) {
                $src = $matches[1];
                $filename = Yii::getAlias('@webroot/'.$src);
                $result = $wechat->material->uploadArticleImage($filename);
                $replace = "<img src=\"{$result['url']}\"/>";
                return $replace;
            }, $model->content);

            $articles[] = new \EasyWeChat\Kernel\Messages\Article([
                'thumb_media_id'=>$media_id,
                'author'=>$this->config->merchant->name,
                'title'=>$model->title,
                'content_source_url'=>Yii::$app->params['wechat.url']. \yii\helpers\Url::toRoute(['/article/view', 'id'=>$model->id, 'config_id'=>$model->config_id]),
                'content'=>$content,
                'digest'=>$model->summary,
                'show_cover_pic'=>1,
            ]);

        }
        $result = $wechat->material->uploadArticle($articles);
        return $result['media_id'];
    }

    // 发送预览
    public function preview($towxname)
    {
        $wechat = $this->config->wechatApp;
        $media_id =$this->prepare($wechat);
        return $wechat->broadcasting->previewNewsByName($media_id, $towxname);
    }

    public function send($limit)
    {
        $wechat = $this->config->wechatApp;
        $media_id =$this->prepare($wechat, $limit);
        return $wechat->broadcasting->sendNews($media_id);
    }
}
