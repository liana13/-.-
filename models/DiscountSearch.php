<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Discount;

/**
 * DiscountSearch represents the model behind the search form of `app\models\Discount`.
 */
class DiscountSearch extends Discount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'catroom_id', 'object_id', 'user_id'], 'integer'],
            [['age', 'percent'], 'safe'],
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
        $query = Discount::find();

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
            'catroom_id' => $this->catroom_id,
            'object_id' => $this->object_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'age', $this->age])
            ->andFilterWhere(['like', 'percent', $this->percent]);

        return $dataProvider;
    }
}
