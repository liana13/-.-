<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Booking;
use app\models\Object;

/**
 * BookingSearch represents the model behind the search form of `app\models\Booking`.
 */
class BookingSearch extends Booking
{
    /**
     * @inheritdoc
     */
     public $title;
    public function rules()
    {
        return [
            [['title'], 'safe'],
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
        $query = Booking::find()->where(['cancel' => 0])->andWhere(['status' => 1]);

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

        if (!empty($this->title)) {
            $objid = [];
            $objects = Object::find()->where(['tarif_id' => 4])->all();
            foreach ($objects as $object) {
                if (mb_stripos($object->title, $this->title) !== false) {
                    $objid []= $object->id;
                }
            }
            if (count($objid)==0) {
                $objid = ['0'];
            }
            $query->andFilterWhere(['in','object_id', $objid]);
        }

        return $dataProvider;
    }
}
