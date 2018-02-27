<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Config;

/**
 * ConfigSearch represents the model behind the search form of `app\models\Config`.
 */
class ConfigSearch extends Config
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'objectcat_id', 'by_price', 'by_rate', 'by_center', 'by_fromsea', 'by_highsea'], 'integer'],
            [['title', 'logo', 'address'], 'safe'],
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
        $query = Config::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'objectcat_id' => $this->objectcat_id,
            'by_price' => $this->by_price,
            'by_rate' => $this->by_rate,
            'by_center' => $this->by_center,
            'by_fromsea' => $this->by_fromsea,
            'by_highsea' => $this->by_highsea,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
