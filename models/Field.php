<?php

namespace app\models;

use Yii;
use app\models\Tarif;
/**
 * This is the model class for table "field".
 *
 * @property int $id
 * @property string $tarif_id
 * @property string $title
 */
class Field extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required' , 'message' => 'заполните поле'],
            [['title'], 'unique'],
            [['tarif_id', 'title', 'type', 'class'], 'string', 'max' => 255],
            ['sort', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tarif_id' => Yii::t('app', 'Тариф'),
            'title' => Yii::t('app', 'Название'),
            'type' => Yii::t('app', 'Тип'),
            'sort' => Yii::t('app', 'Сортировка'),

        ];
    }

    public static function get_message_tarif($tarif_id){
        $model = Tarif::find()->where(["id" => $tarif_id])->one();
        if(!empty($model)){
            return  $model->title;
        }

        return null;
    }
}
