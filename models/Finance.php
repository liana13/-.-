<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Finance extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%finance}}';
    }

    public function behaviors()
   {
       return [
           [
               'class' => TimestampBehavior::className(),
               'createdAtAttribute' => 'created_at',
               'updatedAtAttribute' => 'updated_at',
               'value' => new Expression('NOW()'),
           ],
       ];
   }

    public function rules()
    {
        return [
            [['user_id', 'object_id', 'tarif_id'], 'required'],
            [['user_id', 'object_id', 'tarif_id', 'status'], 'integer'],
            [['created_at', 'updated_at', 'price'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'object_id' => Yii::t('app', 'Объект '),
            'tarif_id' => Yii::t('app', 'Тариф'),
            'price' => Yii::t('app', 'Сумма'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Редактировано'),
            'status' => Yii::t('app', 'Статус'),
        ];
    }
}
