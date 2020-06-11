<?php

namespace backend\modules\external\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "api_config".
 *
 * @property int $id
 * @property string $project 项目标识
 * @property string $api_provider 接口提供商
 * @property string $api_name 调用接口名称
 * @property string $appid 接口appid
 * @property string $appsecret 接口密钥
 * @property string $appcode
 */
class ApiConfig extends \yii\db\ActiveRecord
{

    /**
     * @var string[]
     */
    public static $apiTags = [
        'idcard'    =>  '身份证OCR',
        'invoice'    =>  '增值税发票OCR',
        'verify'    =>  '身份证二要素',
        'compare'    =>  '人脸比对',
        'medical_invoice'    =>  '医疗发票OCR',
        'medical_detail'    =>  '医疗费用清单',
        'liveness'    =>  '人脸识别',
    ];

    public static $apiProviders = [
        'faceid'    =>  '旷视',
        'tencent'    =>  '腾讯',
        'deepfinch'    =>  '深源恒际',
        'aliyun'    =>  '阿里',
        'baidu'    =>  '百度',
        'xunfei'    =>  '讯飞',
        'yongyou'    =>  '用友',
    ];

    public static $modelName = '接口权限';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project', 'api_provider', 'api_name', 'appid', 'appsecret'], 'required'],
            [['project'], 'string', 'max' => 32],
            [['api_provider', 'api_name'], 'string', 'max' => 16],
            [['appid', 'appsecret', 'appcode'], 'string', 'max' => 64],
            [['project', 'api_provider', 'api_name'], 'checkUnique'],
        ];
    }

    public function checkUnique($attribute, $params) {
        $query = self::find()->where(['project'=>$this->project, 'api_name'=>$this->api_name, 'api_provider'=>$this->api_provider]);
        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'id', $this->id]);
        }
        $exist = $query->one();
        if ($exist) {
            $this->addError('api_name', '接口重复');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project' => '项目',
            'api_provider' => '接口提供商',
            'api_name' => '接口',
            'appid' => 'Appid',
            'appsecret' => 'Appsecret',
            'appcode' => 'Appcode',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['id'=> SORT_DESC]],
            'pagination'=>['validatePage'=>false],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'project' => $this->project,
            'api_name' => $this->api_name,
            'api_provider' => $this->api_provider,
        ]);

        $data = $query->one();

        return $dataProvider;
    }

    public function getProjectone() {
        return $this->hasOne(Project::className(), ['project_name' => 'project']);
    }

    public static function getProjectData() {
        $all = Project::find()->all();
        $data = [];
        foreach ($all as $val) {
            $data[$val['project_name']] = $val['app_name'];
        }
        return $data;
    }
}
