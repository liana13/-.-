<?php

namespace app\models;

use Yii;
use app\models\Catroom;
use app\models\Object;
use app\models\Curency;

/**
 * This is the model class for table "price".
 *
 * @property int $id
 * @property int $catroom_id
 * @property int $object_id
 * @property int $price
 * @property int $currency_id
 * @property string $from
 * @property string $to
 * @property string $work_day
 * @property string $weekend
 */
class Price extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price';
    }

    /**
     * @inheritdoc
     */
    public $from;
    public $to;
    public $price;
    public function rules()
    {
        return [
            [['catroom_id', 'object_id'], 'required' , 'message' => 'заполните поле'],
            [['catroom_id', 'object_id', 'work_day', 'weekend', 'price'], 'integer'],
            [['check_date'], 'string'],
            [['from', 'to'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'catroom_id' => Yii::t('app', 'Категория номера'),
            'object_id' => Yii::t('app', 'Объект'),
            'from' => Yii::t('app', 'От'),
            'to' => Yii::t('app', 'До'),
            'work_day' => Yii::t('app', 'Рабочий день'),
            'weekend' => Yii::t('app', 'Выходные'),
        ];
    }
    public static function get_message_catroom($catroom){
      $model = Catroom::find()->where(["id" => $catroom])->one();
      if(!empty($model)){
          return  $model->title;
      }
      return null;
    }
    public static function get_message_object($object){
      $model = Object::find()->where(["id" => $object])->one();
      if(!empty($model)){
          return  $model->title;
      }
      return null;
    }
    public static function get_message_currency($currency){
      $model = Curency::find()->where(["id" => $currency])->one();
      if(!empty($model)){
          return  $model->title;
      }
      return null;
    }
}
