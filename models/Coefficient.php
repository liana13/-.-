<?php

namespace app\models;

use Yii;
use app\models\Object;
/**
 * This is the model class for table "coefficient".
 *
 * @property int $id
 * @property int $object_id
 * @property int $percent
 */
class Coefficient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coefficient';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'percent'], 'required' , 'message' => 'заполните поле'],
            [['object_id', 'percent', 'interval'], 'integer'],
            [['datefrom', 'dateto'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'object_id' => Yii::t('app', 'Объект'),
            'percent' => Yii::t('app', 'Процент'),
            'datefrom' => Yii::t('app', 'От'),
            'dateto' => Yii::t('app', 'До'),
            'interval' => Yii::t('app', 'Интервал выставления счета (дней)'),

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
