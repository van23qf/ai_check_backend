<?php

namespace backend\modules\external\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $project_name
 * @property string $secret
 * @property string $api_access
 * @property string $app_name
 */
class Project extends \yii\db\ActiveRecord
{

    public static $modelName = '应用项目';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_name', 'secret', 'app_name'], 'required'],
            [['project_name', 'secret'], 'string', 'max' => 32],
            [['app_name'], 'string', 'max' => 50],
            [['api_access'], 'string', 'max' => 128],
            [['project_name', 'secret', 'api_access', 'app_name'], 'safe'],
            [['project_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_name' => 'APPID',
            'secret' => 'APP_SECRET',
            'app_name' => '应用名称',
            'api_access' => 'API权限',
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
            'project_name' => $this->project_name,
            'app_name' => $this->app_name,
        ]);

        return $dataProvider;
    }
}
