<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Coefficient;
use app\models\Object;

/**
 * CoefficientSearch represents the model behind the search form of `app\models\Coefficient`.
 */
class CoefficientSearch extends Coefficient
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'percent', 'interval'], 'integer'],
            [['object_id', 'datefrom'], 'safe'],
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
        $query = Coefficient::find();

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
            'percent' => $this->percent,
            'interval' => $this->interval,
        ]);

        if (!empty($this->object_id)) {
            $objid = [];
            $objects = Object::find()->all();
            foreach ($objects as $object) {
                if (mb_stripos($object->title, $this->object_id) !== false) {
                    $objid []= $object->id;
                }
            }
            if (count($objid)==0) {
                $objid = ['0'];
            }
            $query->andFilterWhere(['in','object_id', $objid]);
        }
        $query->andFilterWhere(['like', 'datefrom', $this->datefrom]);

        return $dataProvider;
    }
}
