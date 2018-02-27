<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rp".
 *
 * @property int $id
 * @property string $page
 * @property int $iditem
 * @property string $date
 * @property int $number
 */
class Rp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page', 'iditem', 'date', 'number'], 'required', 'message' => 'заполните поле.'],
            [['iditem', 'number'], 'integer'],
            [['date'], 'safe'],
            [['page'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '№'),
            'page' => Yii::t('app', 'Страница'),
            'iditem' => Yii::t('app', '№ Объекта'),
            'date' => Yii::t('app', 'Дата окончания'),
            'number' => Yii::t('app', 'Положение'),
        ];
    }
}
