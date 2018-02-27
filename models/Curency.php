<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curency".
 *
 * @property int $id
 * @property string $title
 */
class Curency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'curency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required' , 'message' => 'заполните поле'],
            [['title', 'mini_title', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Название'),
            'mini_title' => Yii::t('app', 'Короткое название'),
            'value' => Yii::t('app', 'Стоимость'),
        ];
    }
}
