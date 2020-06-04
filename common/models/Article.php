<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\behaviors\AttributeBehavior;


/**
 * This is the model class for table "wechat_article".
 *
 * @property integer $id
 * @property integer $column_id
 * @property integer $config_id
 * @property string $title
 * @property string $summary
 * @property string $commend
 * @property string $image
 * @property string $content
 * @property string $created_at
 * @property string $redirect_url
 * @property integer $is_mass_send
 * @property integer $sortnum
 */
class Article extends \common\components\ActiveRecord
{
    public $modelName = '微信文章';
    public $fileAttributes = ['image'];

    const COMMEND_1 = '热点';
    const COMMEND_2 = '推荐';
    const COMMEND_3 = '置顶';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_article';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function ($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public function getColumn()
    {
        return $this->hasOne(Column::className(), ['id' => 'column_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['column_id', 'config_id', 'title', 'summary'], 'required'],
            [['image'], 'required', 'enableClientValidation'=>false],
            [['column_id', 'config_id', 'is_mass_send', 'sortnum'], 'integer'],
            [['content'], 'string'],
            [['created_at'], 'safe'],
            [['title', 'image'], 'string', 'max' => 100],
            [['commend'], 'string', 'max' => 10],
            [['summary'], 'string', 'max' => 200],
            [['redirect_url'], 'string', 'max' => 255],
            [['content'],'required','when'=>function($model){
                return empty($model->redirect_url) ;
            },'whenClient' => "function(attribute,value){
                return $('#article-redirect_url').val() == '' }"
            ],
            [['project_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'column_id' => '所属栏目',
            'config_id' => '所属配置ID',
            'title' => '文章标题',
            'summary' => '文章摘要',
            'image' => '标题图片',
            'content' => '文章内容',
            'created_at' => '发布时间',
            'is_mass_send' => '是否群发',
            'commend' => '推荐类型',
            'sortnum' => '排序数字',
            'redirect_url' => '跳转URL',
            'project_id' => '所属子项目'
        ];
    }

    // 获取图片列表
    public function getImages()
    {
        $regex = '%<img[^>]*?src="(.*?)"[^>]*?>%i';
        $matches = [];
        $content=preg_match_all($regex, $this->content, $matches);
        $images = $matches[1];
        foreach($images as $key=>$image){
            if(strpos($image, 'http://')===false){
                $images[$key] = Yii::$app->request->hostInfo.$image;
            }
        }

        return $images;
    }

    public function prepare($wechat)
    {
        //上传图片素材
        $filename = Yii::getAlias('@webroot'.$this->image);
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
        }, $this->content);

        $article = new \EasyWeChat\Kernel\Messages\Article([
            'thumb_media_id'=>$media_id,
            'author'=>$config->merchant->name,
            'title'=>$this->title,
            'content_source_url'=>Yii::$app->params['wechat.url']. \yii\helpers\Url::toRoute(['/article/view', 'id'=>$this->id, 'config_id'=>$this->config_id]),
            'content'=>$content,
            'digest'=>$this->summary,
            'show_cover_pic'=>1,
        ]);

        $result = $wechat->material->uploadArticle($article);
        return $result['media_id'];
    }

    // 发送预览
    public function preview($towxname)
    {
        $wechat = $this->column->config->wechatApp;
        $media_id =$this->prepare($wechat);
        return $wechat->broadcasting->previewNewsByName($media_id, $towxname);
    }

    public function send()
    {
        $wechat = $this->column->config->wechatApp;
        $media_id =$this->prepare($wechat);
        return $wechat->broadcasting->sendNews($media_id);
    }

    public function getIsFav($user_id)
    {
        return ArticleFavorite::find()->where(['user_id'=>$user_id, 'article_id'=>$this->id])->exists();
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['content'] = function ($model) {
            return str_replace('src="/upload/', 'src="' . Yii::$app->params['api.url'] . '/upload/', $model->content);
        };

        return $fields;
    }

    public function extraFields()
    {
        return ['images','column'];
    }

    
}
