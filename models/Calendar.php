<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "calendar".
 *
 * @property int $id
 * @property int $object_id
 * @property int $catroom_id
 * @property int $check_date
 * @property int $to_date
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'catroom_id', 'check_date'], 'required'],
            [['object_id', 'catroom_id', 'status', 'book_id'], 'integer'],
            [['check_date'], 'string'],
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
            'check_date' => Yii::t('app', 'From Date'),
        ];
    }
}
