<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weekdays".
 *
 * @property int $id
 * @property int $object_id
 * @property string $week_days
 */
class Weekdays extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weekdays';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id'], 'required'],
            [['object_id'], 'integer'],
            [['week_days'], 'string', 'max' => 255],
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
            'week_days' => Yii::t('app', 'Week Days'),
        ];
    }
}
