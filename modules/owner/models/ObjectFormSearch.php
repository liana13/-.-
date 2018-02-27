<?php

namespace app\modules\owner\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Object;
use app\models\User;

/**
 * ObjectSearch represents the model behind the search form of `app\models\Object`.
 */
class ObjectSearch extends Object
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'locality_id', 'service', 'user_id', 'sort', 'tarif_id', 'act', 'edit', 'allow_review', 'country_id', 'region_id', 'price', 'rating'], 'integer'],
            [['title', 'alias', 'description', 'general', 'login', 'act_oplata', 'end', 'phone', 'created_at', 'updated_at', 'address'], 'safe'],
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
        $query = Object::find()->where(['user_id'=>Yii::$app->user->getId()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => [
            //     'pageSize' => '',
            // ]
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
            'locality_id' => $this->locality_id,
            'service' => $this->service,
            'user_id' => $this->user_id,
            'sort' => $this->sort,
            'tarif_id' => $this->tarif_id,
            'act' => $this->act,
            'edit' => $this->edit,
            'allow_review' => $this->allow_review,
            'country_id' => $this->country_id,
            'region_id' => $this->region_id,
            'price' => $this->price,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'general', $this->general])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'act_oplata', $this->act_oplata])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
