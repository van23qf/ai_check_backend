<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WechatNotification;

/**
 * WechatNotificationSearch represents the model behind the search form about `common\models\WechatNotification`.
 */
class WechatNotificationSearch extends WechatNotification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'config_id', 'user_id'], 'integer'],
            [['type', 'openid', 'data', 'status', 'created_at', 'send_at', 'retry_times'], 'safe'],
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
        $query = WechatNotification::find();

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
            'created_at' => $this->created_at,
            'send_at' => $this->send_at,
            'openid' => $this->openid,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'retry_times', $this->retry_times]);

        return $dataProvider;
    }
}
