<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif".
 *
 * @property int $id
 * @property string $title
 * @property int $price
 * @property string $time
 * @property string $desc_small
 */
class Tarif extends \yii\db\ActiveRecord
{
    public $photodo;
    public $photopo;

    public static function tableName()
    {
        return 'tarif';
    }

    public function rules()
    {
        return [
            [['title'], 'required', 'message' => 'заполните поле.'],
            [['title', 'time', 'text','list_place', 'price', 'photo', 'photodo', 'photopo', 'price_letters'], 'string', 'max' => 255],
            ['tarifid', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Название'),
            'price' => Yii::t('app', 'Цена'),
            'time' => Yii::t('app', 'Время'),
            'text' => Yii::t('app', 'Описание'),
            'price_letters' => Yii::t('app', 'Цена прописью'),
        ];
    }
}
