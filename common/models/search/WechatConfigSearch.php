<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WechatConfig;

/**
 * ConfigSearch represents the model behind the search form about `common\models\WechatConfig`.
 */
class WechatConfigSearch extends WechatConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'kind', 'appid', 'appsecret', 'token', 'encodingAesKey', 'username', 'pay_mch_id', 'pay_key', 'apiclient_cert', 'apiclient_key', 'created_at', 'welcome_message', 'remark', 'mpmsg_templates'], 'safe'],
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
        $query = WechatConfig::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['id'=> SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'kind', $this->kind])
            ->andFilterWhere(['like', 'appid', $this->appid])
            ->andFilterWhere(['like', 'appsecret', $this->appsecret])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'encodingAesKey', $this->encodingAesKey])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'pay_mch_id', $this->pay_mch_id])
            ->andFilterWhere(['like', 'pay_key', $this->pay_key])
            ->andFilterWhere(['like', 'apiclient_cert', $this->apiclient_cert])
            ->andFilterWhere(['like', 'apiclient_key', $this->apiclient_key])
            ->andFilterWhere(['like', 'welcome_message', $this->welcome_message])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'mpmsg_templates', $this->mpmsg_templates]);

        return $dataProvider;
    }
}
