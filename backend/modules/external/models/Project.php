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
            [['project_name', 'secret'], 'required'],
            [['project_name', 'secret'], 'string', 'max' => 32],
            [['api_access'], 'string', 'max' => 128],
            [['project_name', 'secret', 'api_access'], 'safe'],
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
        $query = \backend\modules\external\models\Project::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['id'=> SORT_DESC]],
            'pagination'=>['validatePage'=>false],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'project_name' => $this->project_name,
        ]);

        return $dataProvider;
    }
}
