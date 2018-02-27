<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "servis".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $aliastwo
 * @property int $parent_id
 * @property int $sort
 */
class Servis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required', 'message' => 'заполните поле.'],
            [['parent_id', 'sort'], 'integer'],
            [['title', 'alias', 'aliastwo'], 'string', 'max' => 255],
            ['description', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent_id' => Yii::t('app', 'Родительская категория'),
            'title' => Yii::t('app', 'Загаловок'),
            'alias' => Yii::t('app', 'Псевдоним заголовка'),
            'aliastwo' => Yii::t('app', 'Псевдоним заголовка (Единственное число)'),
            'sort' => Yii::t('app', 'Сортировка'),
        ];
    }
}
