<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Object;

/**
 * ObjectSearch represents the model behind the search form of `app\models\Object`.
 */
class ObjectsallSearch extends Object
{
    /**
     * @inheritdoc
     */
    public $search;

    public function rules()
    {
        return [
            // [['id', 'user_id', 'locality_id', 'rating', 'price', 'tarif_id', 'allow_review', 'country_id', 'region_id'], 'integer'],
            [['title','description', 'general', 'address', 'status', 'search'], 'safe'],
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
        $query = Object::find()->where(['active'=>1]);

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

        $query->andFilterWhere(['like', 'title', $this->search])
            ->orFilterWhere(['like', 'description', $this->search])
            ->orFilterWhere(['like', 'general', $this->search])
            ->orFilterWhere(['like', 'address', $this->search]);

        return $dataProvider;
    }
}
