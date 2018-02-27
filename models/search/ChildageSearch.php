<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Childage;

/**
 * ChildageSearch represents the model behind the search form of `app\models\Childage`.
 */
class ChildageSearch extends Childage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'catroom_id', 'child_count', 'child_age'], 'integer'],
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
        $query = Childage::find();

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
            'child_count' => $this->child_count,
            'child_age' => $this->child_age,
        ]);

        return $dataProvider;
    }
}
