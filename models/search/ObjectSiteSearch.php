<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Object;
use yii\db\Query;
/**
 * ObjectSearch represents the model behind the search form of `app\models\Object`.
 */
class ObjectSiteSearch extends Object
{
    public $cat_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
         return [
             [['cat_id'], 'integer'],
             [['service'], 'safe'],
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
             'pagination' => [
                'pageSize' => 500,
            ],
         ]);

         $this->load($params);

         if (!$this->validate()) {
             // uncomment the following line if you do not want to return any records when validation fails
             // $query->where('0=1');
             return $dataProvider;
         }
         // grid filtering conditions
         if (!empty($this->cat_id)) {
             $loc = 'to'.$this->cat_id;
             $query->andFilterWhere([
                 'id' => $this->id,
                 'locality_id' => $this->locality_id,
                 'region_id' => $this->region_id,
                 'country_id' => $this->country_id,
             ])->andFilterWhere(['in','locality_id', (new Query())->select(['id'])->from('locality')->where([$loc=>1])]);
         } else {
             $query->andFilterWhere([
                 'id' => $this->id,
                 'locality_id' => $this->locality_id,
                 'region_id' => $this->region_id,
                 'country_id' => $this->country_id,
             ]);
         }
         $query->andFilterWhere(['like', 'service', $this->service]);

         return $dataProvider;
     }
}
