<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Person;

/**
 * PersonSearch represents the model behind the search form of `app\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type'], 'integer'],
            [['priming', 'login', 'name_org_1', 'name_org_2', 'address', 'inn', 'phone', 'fio', 'address_mestozhitelstvo', 'tphone', 'email', 'mails'], 'safe'],
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
        $query = Person::find();

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
            'user_id' => $this->user_id,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'priming', $this->priming])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'name_org_1', $this->name_org_1])
            ->andFilterWhere(['like', 'name_org_2', $this->name_org_2])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'inn', $this->inn])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'address_mestozhitelstvo', $this->address_mestozhitelstvo])
            ->andFilterWhere(['like', 'tphone', $this->tphone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'mails', $this->mails]);

        return $dataProvider;
    }
}
