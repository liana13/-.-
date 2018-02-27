<?php

namespace app\models;

use Yii;
use app\models\Object;
use app\models\Childage;
/**
 * This is the model class for table "catroom".
 *
 * @property int $id
 * @property string $title
 * @property int $object_id
 * @property int $room_count
 * @property int $adult_count
 * @property int $add_count
 * @property string $description
 * @property int $status
 */
class Catroom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catroom';
    }

    /**
     * @inheritdoc
     */
     public $file;
     public $child_age1;
     public $child_age2;
     public $child_age3;
     public $child_age4;

    public function rules()
    {
        $fields = [];
        for ($i=1; $i <=50 ; $i++) {
            $fields[]='room_name'.$i;
        }

        return [
            [$fields, 'safe'],
            [['title', 'object_id', 'room_count', 'adult_count', 'description', 'work_day', 'weekend', 'count_rooms'], 'required' , 'message' => 'заполните поле'],
            [['object_id', 'room_count', 'adult_count', 'add_count', 'user_id', 'status',
                'child_count', 'child_age1','child_age2','child_age3','child_age4','count_rooms',
                'food_id', 'work_day', 'weekend', 'work_add', 'weekend_add'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['file'], 'image'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Название'),
            'object_id' => Yii::t('app', 'Объект'),
            'room_count' => Yii::t('app', 'Количество номеров'),
            'adult_count' => Yii::t('app', 'Количество взрослых'),
            'add_count' => Yii::t('app', 'Доп. количество'),
            'description' => Yii::t('app', 'Описание'),
            'status' => Yii::t('app', 'Статус'),
            'food_id' => Yii::t('app', 'Питания'),
        ];
    }
    public static function get_message_object($object_id){
        $model = Object::find()->where(["id" => $object_id])->one();
        if(!empty($model)){
            return  $model->title;
        }

        return null;
      }
}
