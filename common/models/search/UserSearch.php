<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    public $subscribe_at_from, $subscribe_at_to;
    public $level = 1; // 推荐级别

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'config_id', 'groupid', 'scene_id', 'from_user_id', 'from_share_id', 'level'], 'integer'],
            [['openid', 'subscribe_at', 'unsubscribe_at', 'nickname', 'realname', 'sex', 'birthdate', 'city', 'province', 'country', 'headimgurl', 'remark', 'mobile', 'subscribe_at_from', 'subscribe_at_to', 'rank', 'is_teacher'], 'safe'],
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
        $query = User::find();

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
            'subscribe_at' => $this->subscribe_at,
            'unsubscribe_at' => $this->unsubscribe_at,
            'birthdate' => $this->birthdate,
            'groupid' => $this->groupid,
            'scene_id' => $this->scene_id,
            'rank' => $this->rank,
            'is_teacher' => $this->is_teacher,
            'from_share_id' => $this->from_share_id,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'headimgurl', $this->headimgurl])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);

        $query->andFilterWhere(['>=', 'subscribe_at', $this->subscribe_at_from])
            ->andFilterWhere(['<=', 'subscribe_at', $this->subscribe_at_to]);

        if($this->from_user_id){
            if($this->level == 1){
                $query->andWhere(['from_user_id'=>$this->from_user_id]);
            }
            else{
                $query->andWhere(['IN', 'from_user_id', (new \yii\db\Query())->select('id')->from('wechat_user')->where(['from_user_id'=>$this->from_user_id])]);
            }
        }

        return $dataProvider;
    }
}
