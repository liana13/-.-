<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Price;

class PriceSearch extends Price
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'catroom_id', 'object_id', 'user_id'], 'integer'],
            [['from', 'to', 'work_day', 'weekend'], 'safe'],
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
        $query = Price::find()->where(['object_id'=>Yii::$app->request->get('id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'catroom_id' => $this->catroom_id,
            'object_id' => $this->object_id,
            'from' => $this->from,
            'to' => $this->to,
        ]);

        $query->andFilterWhere(['like', 'work_day', $this->work_day])
            ->andFilterWhere(['like', 'weekend', $this->weekend]);

        return $dataProvider;
    }
}
