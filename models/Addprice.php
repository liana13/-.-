<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "addprice".
 *
 * @property int $id
 * @property int $object_id
 * @property int $catroom_id
 * @property string $check_date
 * @property int $work_day
 * @property int $weekend
 */
class Addprice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $from;
    public $to;
    public $price;
    public static function tableName()
    {
        return 'addprice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'catroom_id'], 'required'],
            [['object_id', 'catroom_id', 'work_day', 'weekend', 'price'], 'integer'],
            [['check_date'], 'safe'],
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
}
