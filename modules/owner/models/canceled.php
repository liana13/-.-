<?php

namespace app\modules\owner\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Booking;

/**
 * BookingSearch represents the model behind the search form of `app\models\Booking`.
 */
class canceled extends Booking
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['from', 'to', 'id'], 'safe'],
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
        $query = Booking::find()->where(['object_id' => Yii::$app->request->get('id')])->andWhere(['cancel' => 1])
                        ->orWhere(['cancel' => 2])->andWhere(['object_id' => Yii::$app->request->get('id')])->orderBy('from DESC');

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

        if (empty($this->from) && empty($this->to) && !empty($this->id)) {
            $bookid = [];
            $bookings = Booking::find()->where(['object_id' => Yii::$app->request->get('id')])->andWhere(['!=', 'cancel', 0])->all();
            foreach ($bookings as $booking) {
                if (mb_stripos($booking->name, $this->id) !== false || mb_stripos($booking->object_id . '-' . $booking->id,$this->id) !== false) {
                    $bookid []= $booking->id;
                }
            }
            if (count($bookid)==0) {
                $bookid = ['0'];
            }
            $query->andFilterWhere(['in','id', $bookid]);
        } elseif (!empty($this->from) && empty($this->to)) {
            $bookid = [];
            $bookings = Booking::find()->where(['object_id' => Yii::$app->request->get('id')])->andWhere(['!=', 'cancel', 0])->andWhere(['>=','from',$this->from])->all();
            foreach ($bookings as $booking) {
                if (empty($this->id)) {
                    $bookid []= $booking->id;
                } elseif (mb_stripos($booking->name, $this->id) !== false || mb_stripos($booking->object_id . '-' . $booking->id,$this->id) !== false) {
                    $bookid []= $booking->id;
                }
            }
            if (count($bookid)==0) {
                $bookid = ['0'];
            }
            $query->andFilterWhere(['in','id', $bookid]);
        } elseif (empty($this->from) && !empty($this->to)) {
            $bookid = [];
            $bookings = Booking::find()->where(['object_id' => Yii::$app->request->get('id')])->andWhere(['!=', 'cancel', 0])->andWhere(['<=','to',$this->to])->all();
            foreach ($bookings as $booking) {
                if (empty($this->id)) {
                    $bookid []= $booking->id;
                } elseif (mb_stripos($booking->name, $this->id) !== false || mb_stripos($booking->object_id . '-' . $booking->id,$this->id) !== false) {
                    $bookid []= $booking->id;
                }
            }
            if (count($bookid)==0) {
                $bookid = ['0'];
            }
            $query->andFilterWhere(['in','id', $bookid]);
        } elseif (!empty($this->from) && !empty($this->to)) {
            $bookid = [];
            $bookings = Booking::find()->where(['object_id' => Yii::$app->request->get('id')])->andWhere(['!=', 'cancel', 0])->andWhere(['between','from',$this->from,$this->to])->all();
            foreach ($bookings as $booking) {
                if (empty($this->id)) {
                    $bookid []= $booking->id;
                } elseif (mb_stripos($booking->name, $this->id) !== false || mb_stripos($booking->object_id . '-' . $booking->id,$this->id) !== false) {
                    $bookid []= $booking->id;
                }
            }
            if (count($bookid)==0) {
                $bookid = ['0'];
            }
            $query->andFilterWhere(['in','id', $bookid]);
        }

        return $dataProvider;
    }
}
