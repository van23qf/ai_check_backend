<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sms;

/**
 * SmsSearch represents the model behind the search form about `common\models\Sms`.
 */
class SmsSearch extends Sms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'config_id', 'user_id', 'verifycode', 'retry_times'], 'integer'],
            [['mobile', 'content', 'type', 'created_at', 'status', 'send_at', 'sign'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Sms::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['id'=> SORT_DESC]],
            'pagination'=>['validatePage'=>false],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'config_id' => $this->config_id,
            'user_id' => $this->user_id,
            'verifycode' => $this->verifycode,
            'created_at' => $this->created_at,
            'send_at' => $this->send_at,
            'retry_times' => $this->retry_times,
        ]);

        $query->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'sign', $this->sign]);

        return $dataProvider;
    }
}
