<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Object;

class Tarifend extends Model
{

    public $id;
    public $end;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            ['end', 'safe'],
        ];
    }

    public function change()
    {
        if (!$this->validate()) {
            return null;
        }
        $object = Object::findOne(['id'=>$this->id]);
        $object->end_date = explode("T", explode(",",$this->end)[0])[0]." ".explode("T", explode(",",$this->end)[0])[1];
        $object->save();
        if($object->save()){
            return ($object->save()) ? $object : null;
        } else {
            return false;
        }
    }
}
