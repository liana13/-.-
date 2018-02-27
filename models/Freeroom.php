<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "freeroom".
 *
 * @property int $id
 * @property int $object_id
 * @property int $catroom_id
 * @property string $check_date
 * @property int $room_count
 */
class Freeroom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'freeroom';
    }

    /**
     * @inheritdoc
     */
    public $from;
    public $to;

    public function rules()
    {
        return [
            [['object_id', 'catroom_id', 'check_date', 'room_count'], 'required'],
            [['object_id', 'catroom_id', 'room_count'], 'integer'],
            [['check_date', 'from', 'to'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'object_id' => Yii::t('app', 'Object ID'),
            'catroom_id' => Yii::t('app', 'Catroom ID'),
            'check_date' => Yii::t('app', 'Check Date'),
            'room_count' => Yii::t('app', 'Room Count'),
        ];
    }
}
