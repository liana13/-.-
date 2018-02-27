<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Object;
use app\models\Locality;
use app\models\Region;
use app\models\Country;
use app\models\Tarif;
use app\models\Servis;

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
            [['id', 'service', 'user_id', 'active', 'edit', 'allow_review'], 'integer'],
            [['title', 'alias','tarif_id','description', 'general', 'login', 'act_oplata', 'end_date', 'created_at', 'updated_at', 'address'], 'safe'],
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
        $query = Object::find();

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
            'service' => $this->service,
            'user_id' => $this->user_id,
            'active' => $this->active,
            'edit' => $this->edit,
            'allow_review' => $this->allow_review,
            'act_oplata' => $this->act_oplata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'end_date'=>$this->end_date,
        ]);
        if (!empty($this->tarif_id)) {
            $tid = [];
            $tarifs = Tarif::find()->all();
            foreach ($tarifs as $tarif) {
                if (mb_stripos($tarif->title, $this->tarif_id) !== false) {
                    $tid []= $tarif->tarifid;
                }
            }
            if (count($tid)==0) {
                $tid = ['0'];
            }
            $query->andFilterWhere(['in','tarif_id', $tid]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'general', $this->general])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
