<?php

namespace app\models;

use Yii;
use app\models\Object;
use app\models\Field;

/**
 * This is the model class for table "properties".
 *
 * @property int $id
 * @property int $object_id
 * @property int $field_id
 * @property string $field_value
 */
class Properties extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'properties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'field_id', 'field_value'], 'required' , 'message' => 'заполните поле'],
            [['object_id', 'field_id'], 'integer'],
            [['field_value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'object_id' => Yii::t('app', 'Объект'),
            'field_id' => Yii::t('app', 'Свойства объекта'),
            'field_value' => Yii::t('app', 'Недвижимость'),
        ];
    }
    public static function get_message_object($object){
      $model = Object::find()->where(["id" => $object])->one();
      if(!empty($model)){
          return  $model->title;
      }
      return null;
    }
    public static function get_message_field($field){
      $model = Field::find()->where(["id" => $field])->one();
      if(!empty($model)){
          return  $model->title;
      }
      return null;
    }
}
