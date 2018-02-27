<?php

namespace app\models;

use Yii;
use app\models\Image;
/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $object_id
 * @property string $image
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file;
    public $file10;
    public $file20;
    public $file40;

    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id'], 'required' , 'message' => 'заполните поле'],
            [['object_id', 'main'], 'integer'],
            [['image', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'object_id' => Yii::t('app', 'Объект'),
            'image' => Yii::t('app', 'Изображение'),
            'file' => Yii::t('app', 'Изображение'),
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
