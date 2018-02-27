<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use app\models\Object;
use app\models\Review;
use app\models\Rate;

class Changetarif extends Model
{

    public $id;
    public $tarif_id;

    public function rules()
    {
        return [
            [['id', 'tarif_id'], 'integer'],
        ];
    }

    public function change()
    {
        if (!$this->validate()) {
            return null;
        }
        $object = Object::findOne(['id'=>$this->id]);
        $object->tarif_id = $this->tarif_id;
        $object->new_tarif = $this->tarif_id;
        $oldend = date("Y-m-d");
        $end = strtotime($oldend);
        $newend = strtotime('+ 1 year', $end);
        $newend = date("Y-m-d", $newend);
        if ($object->tarif_id == 0 || $object->tarif_id == 4) {
            $object->end_date = Null;
            $object->act_oplata = $oldend;
            $object->updated_at = new Expression('NOW()');
            if ($object->tarif_id == 4) {
                $object->active_online = $oldend;
            }
        } else {
            $object->end_date = explode(" ", $newend)[0];
            $object->act_oplata = $oldend;
            $object->updated_at = new Expression('NOW()');
        }
        $object->act_oplata = $oldend;
        $object->save();
        if($object->save()){
            if ($object->tarif_id == 4) {
                $reviews = Review::find()->where(['object_id'=>$object->id])->andWhere(['status'=>1])->all();
                foreach ($reviews as $review) {
                    $rateint += $review->rate;
                }
                if (count($reviews) != 0) {
                    $rateint = round($rateint/count($reviews), '1');
                } else {
                    $rateint = 0;
                }
                if (Rate::findOne(['object_id'=>$object->id])) {
                    $rate = Rate::findOne(['object_id'=>$object->id]);
                    $rate->rate = $rateint;
                    $rate->save();
                } else {
                    $rate = new Rate();
                    $rate->object_id = $object->id;
                    $rate->rate = $rateint;
                    $rate->save();
                }
            } else {
                if (Rate::findOne(['object_id'=>$object->id])) {
                    $rate = Rate::findOne(['object_id'=>$object->id]);
                    $rate->rate = 0;
                    $rate->save();
                }
            }
            return ($object->save()) ? $object : null;
        } else {
            return false;
        }
    }

    public function changerequest()
    {
        if (!$this->validate()) {
            return null;
        }
        $object = Object::findOne(['id'=>$this->id]);
        $object->new_tarif = $this->tarif_id;
        $object->act_oplata = "0";
        $object->save();
        if($object->save()){
            return ($object->save()) ? $object : null;
        } else {
            return false;
        }
    }
}
