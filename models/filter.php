<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use app\models\Catroom;
use app\models\Calendar;
use app\models\Childage;
use app\models\Freeroom;

/**
 * CatroomSearch represents the model behind the search form of `app\models\Catroom`.
 */

class filter extends Catroom
{
    /**
     * @inheritdoc
     */
     public $from;
     public $to;
     public $adult;
     public $child;
     public $age_1;
     public $age_2;
     public $age_3;
     public $age_4;

    public function rules()
    {
        return [
            [['from', 'to', 'adult', 'child', 'age_1', 'age-2', 'age_3', 'age_4'], 'safe'],
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
        $query = Catroom::find();

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
        $object = Object::findOne(['alias'=>Yii::$app->request->get('url')]);
        $query->andFilterWhere([
            'status' => 1,
            'object_id' => $object->id,
        ]);
        if (empty($this->from) && empty($this->to) && !empty($this->adult) && empty($this->child)) {
            $catid = [];
            $catrooms = Catroom::find()->where(['object_id' => $object->id])->all();
            foreach ($catrooms as $cat) {
                if ($cat->adult_count >= $this->adult || $cat->adult_count+$cat->add_count >= $this->adult) {
                    $catid []= $cat->id;
                }
            }
            $query->andFilterWhere(['in','id', $catid]);
        } elseif (empty($this->from) && empty($this->to) && !empty($this->adult) && !empty($this->child)) {
            $catid = [];
            $catrooms = Catroom::find()->where(['object_id' => $object->id])->all();
            foreach ($catrooms as $cat) {
                $childages = Childage::find()->where(['catroom_id'=>$cat->id])->all();
                $ages = []; $chages = [];
                for ($i=1; $i <= $this->child ; $i++) {
                    $a = 'age_'.$i;
                    if (!empty(Yii::$app->request->get('filter')[$a])) {
                        $ages []= Yii::$app->request->get('filter')[$a];
                    } else {
                        $ages []= '0';
                    }
                }
                foreach ($childages as $childage) {
                    $chages []= $childage->child_age;
                }
                while (count($ages)!=0 && count($chages)!=0) {
                    if (min($ages) <= min($chages)) {
                        unset($ages[array_search(min($ages),$ages)]);
                        unset($chages[array_search(min($chages),$chages)]);
                    }else {
                        unset($chages[array_search(min($chages),$chages)]);
                    }
                }
                if ($cat->add_count == 0 && count($ages)==0 && $this->adult <= $cat->adult_count || $cat->add_count == 0 && $this->adult + count($ages) <= $cat->adult_count) {
                    $catid []= $cat->id;
                } elseif ($cat->add_count != 0 && $cat->adult_count >= $this->adult && $cat->child_count >= $this->child && count($ages)==0 ||
                    $cat->add_count != 0 && $cat->adult_count >= $this->adult && $this->adult + count($ages) <= $cat->add_count + $cat->adult_count || $cat->add_count != 0 && $this->adult-$cat->adult_count+count($ages) <= $cat->add_count) {
                    $catid []= $cat->id;
                }
            }
            if (count($catid)==0) {
                $catid = ['0'];
            }
            $query->andFilterWhere(['in','id', $catid]);
        } elseif (!empty($this->from) && !empty($this->to) && !empty($this->adult) && empty($this->child)) {
            $catid=[];
            $from = $this->from; $to = $this->to;
            $freecats = Catroom::find()->where(['object_id' => $object->id])->andFilterWhere(['not in','id', (new Query())->select('catroom_id')->from('calendar')])->all();
            $catrooms = Catroom::find()->where(['object_id' => $object->id])->all();
            foreach ($catrooms as $catroom) {
                $calcount = 0;
                $calendar = Calendar::find()->where(['catroom_id' => $catroom->id])->all();
                foreach ($calendar as $cal) {
                    if ($cal->check_date>$from && $cal->check_date<$to || $cal->check_date==$from && $cal->check_date<$to) {
                        $calcount++;
                    }
                }
                $freeroom = Freeroom::find()->where(['catroom_id' => $item->id])->all();
                foreach ($freeroom as $cal) {
                    if ($cal->check_date>$from && $cal->check_date<$to && $cal->room_count==0 || $cal->check_date==$from && $cal->check_date<$to && $cal->room_count==0) {
                        $calcount++;
                    }
                }
                $childages = Childage::find()->where(['catroom_id'=>$catroom->id])->all();
                $ages = []; $chages = [];

                if ($calcount == 0) {
                    for ($i=1; $i <= $this->child ; $i++) {
                        $a = 'age_'.$i;
                        if (!empty(Yii::$app->request->get('filter')[$a])) {
                            $ages []= Yii::$app->request->get('filter')[$a];
                        } else {
                            $ages []= '0';
                        }
                    }
                    foreach ($childages as $childage) {
                        $chages []= $childage->child_age;
                    }
                    while (count($ages)!=0 && count($chages)!=0) {
                        if (min($ages) <= min($chages)) {
                            unset($ages[array_search(min($ages),$ages)]);
                            unset($chages[array_search(min($chages),$chages)]);
                        }else {
                            unset($chages[array_search(min($chages),$chages)]);
                        }
                    }
                    if ($catroom->add_count == 0 && count($ages)==0 && $this->adult <= $catroom->adult_count || $cat->add_count == 0 && $this->adult + count($ages) <= $cat->adult_count) {
                        $catid []= $catroom->id;
                    } elseif ($catroom->add_count != 0 && $catroom->adult_count >= $this->adult && $catroom->child_count >= $this->child && count($ages)==0
                            || $cat->add_count != 0 && $cat->adult_count >= $this->adult && $this->adult + count($ages) <= $cat->add_count + $cat->adult_count || $catroom->add_count != 0 && $this->adult-$catroom->adult_count+count($ages) <= $catroom->add_count) {
                        $catid []= $catroom->id;
                    }
                }
            }
            foreach ($freecats as $cat) {
                $catroom = Catroom::findOne(['id' => $cat->id]);
                $childages = Childage::find()->where(['catroom_id'=>$catroom->id])->all();
                $ages = []; $chages = [];
                for ($i=1; $i <= $this->child ; $i++) {
                    $a = 'age_'.$i;
                    if (!empty(Yii::$app->request->get('filter')[$a])) {
                        $ages []= Yii::$app->request->get('filter')[$a];
                    } else {
                        $ages []= '0';
                    }
                }
                foreach ($childages as $childage) {
                    $chages []= $childage->child_age;
                }
                while (count($ages)!=0 && count($chages)!=0) {
                    if (min($ages) <= min($chages)) {
                        unset($ages[array_search(min($ages),$ages)]);
                        unset($chages[array_search(min($chages),$chages)]);
                    }else {
                        unset($chages[array_search(min($chages),$chages)]);
                    }
                }
                if ($catroom->add_count == 0 && count($ages)==0 && $this->adult <= $catroom->adult_count || $cat->add_count == 0 && $this->adult + count($ages) <= $cat->adult_count) {
                    $catid []= $catroom->id;
                } elseif ($catroom->add_count != 0 && $catroom->adult_count >= $this->adult && $catroom->child_count >= $this->child && count($ages)==0
                        || $cat->add_count != 0 && $cat->adult_count >= $this->adult && $this->adult + count($ages) <= $cat->add_count + $cat->adult_count || $catroom->add_count != 0 && $this->adult-$catroom->adult_count+count($ages) <= $catroom->add_count) {
                    $catid []= $catroom->id;
                }
            }

            $catid = array_unique($catid);
            if (count($catid)==0) {
                $catid = ['0'];
            }
            $query->andFilterWhere(['in','id', $catid]);
        } elseif (!empty($this->from) && !empty($this->to) && !empty($this->adult) && !empty($this->child)) {
            $catid=[];
            $from = $this->from; $to = $this->to;
            $freecats = Catroom::find()->where(['object_id' => $object->id])->andFilterWhere(['not in','id', (new Query())->select('catroom_id')->from('calendar')])->all();
            $catrooms = Catroom::find()->where(['object_id' => $object->id])->all();
            foreach ($catrooms as $catroom) {
                $calcount = 0;
                $calendar = Calendar::find()->where(['catroom_id' => $catroom->id])->all();
                foreach ($calendar as $cal) {
                    if ($cal->check_date>$from && $cal->check_date<$to || $cal->check_date==$from && $cal->check_date<$to) {
                        $calcount++;
                    }
                }
                $freeroom = Freeroom::find()->where(['catroom_id' => $item->id])->all();
                foreach ($freeroom as $cal) {
                    if ($cal->check_date>$from && $cal->check_date<$to && $cal->room_count==0 || $cal->check_date==$from && $cal->check_date<$to && $cal->room_count==0) {
                        $calcount++;
                    }
                }
                $childages = Childage::find()->where(['catroom_id'=>$catroom->id])->all();
                $ages = []; $chages = [];

                if ($calcount == 0) {
                    for ($i=1; $i <= $this->child ; $i++) {
                        $a = 'age_'.$i;
                        if (!empty(Yii::$app->request->get('filter')[$a])) {
                            $ages []= Yii::$app->request->get('filter')[$a];
                        } else {
                            $ages []= '0';
                        }
                    }
                    foreach ($childages as $childage) {
                        $chages []= $childage->child_age;
                    }
                    while (count($ages)!=0 && count($chages)!=0) {
                        if (min($ages) <= min($chages)) {
                            unset($ages[array_search(min($ages),$ages)]);
                            unset($chages[array_search(min($chages),$chages)]);
                        }else {
                            unset($chages[array_search(min($chages),$chages)]);
                        }
                    }
                    if ($catroom->add_count == 0 && count($ages)==0 && $this->adult <= $catroom->adult_count || $catroom->add_count == 0 && $this->adult + count($ages) <= $catroom->adult_count) {
                        $catid []= $catroom->id;
                    } elseif ($catroom->add_count != 0 && $catroom->adult_count >= $this->adult && $catroom->child_count >= $this->child && count($ages)==0 ||
                        $catroom->add_count != 0 && $catroom->adult_count >= $this->adult && $this->adult + count($ages) <= $catroom->add_count + $catroom->adult_count || $catroom->add_count != 0 && $this->adult-$catroom->adult_count+count($ages) <= $catroom->add_count) {
                        $catid []= $catroom->id;
                    }
                }
            }
            foreach ($freecats as $cat) {
                $catroom = Catroom::findOne(['id' => $cat->id]);
                $childages = Childage::find()->where(['catroom_id'=>$catroom->id])->all();
                $ages = []; $chages = [];
                for ($i=1; $i <= $this->child ; $i++) {
                    $a = 'age_'.$i;
                    if (!empty(Yii::$app->request->get('filter')[$a])) {
                        $ages []= Yii::$app->request->get('filter')[$a];
                    } else {
                        $ages []= '0';
                    }
                }
                foreach ($childages as $childage) {
                    $chages []= $childage->child_age;
                }
                while (count($ages)!=0 && count($chages)!=0) {
                    if (min($ages) <= min($chages)) {
                        unset($ages[array_search(min($ages),$ages)]);
                        unset($chages[array_search(min($chages),$chages)]);
                    }else {
                        unset($chages[array_search(min($chages),$chages)]);
                    }
                }
                if ($catroom->add_count == 0 && count($ages)==0 && $this->adult <= $catroom->adult_count || $cat->add_count == 0 && $this->adult + count($ages) <= $cat->adult_count) {
                    $catid []= $catroom->id;
                } elseif ($catroom->add_count != 0 && $catroom->adult_count >= $this->adult && $catroom->child_count >= $this->child && count($ages)==0
                        || $cat->add_count != 0 && $cat->adult_count >= $this->adult && $this->adult + count($ages) <= $cat->add_count + $cat->adult_count || $catroom->add_count != 0 && $this->adult-$catroom->adult_count+count($ages) <= $catroom->add_count) {
                    $catid []= $catroom->id;
                }
            }

            $catid = array_unique($catid);
            if (count($catid)==0) {
                $catid = ['0'];
            }
            $query->andFilterWhere(['in','id', $catid]);
        }

        return $dataProvider;
    }
}
