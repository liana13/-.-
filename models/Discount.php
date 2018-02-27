<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property int $catroom_id
 * @property int $object_id
 * @property string $age
 * @property string $percent
 */
class Discount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * @inheritdoc
     */
     public $age1;
     public $age2;
     public $age3;
     public $age4;
     public $age5;
     public $age6;
     public $age7;
     public $age8;
     public $age9;
     public $age10;

     public $fromage1;
     public $fromage2;
     public $fromage3;
     public $fromage4;
     public $fromage5;
     public $fromage6;
     public $fromage7;
     public $fromage8;
     public $fromage9;
     public $fromage10;

     public $percent1;
     public $percent2;
     public $percent3;
     public $percent4;
     public $percent5;
     public $percent6;
     public $percent7;
     public $percent8;
     public $percent9;
     public $percent10;
    public function rules()
    {
        return [

            [['object_id', 'age', 'fromage', 'percent'], 'required'],
            [['object_id','catroom_id'], 'integer'],
            [['age', 'percent', 'age1', 'age2', 'age3', 'age4', 'age5', 'age6', 'age7', 'age8', 'age9', 'age10',
            'fromage1', 'fromage2', 'fromage3', 'fromage4', 'fromage5', 'fromage6', 'fromage7', 'fromage8', 'fromage9', 'fromage10',
            'percent1', 'percent2', 'percent3', 'percent4', 'percent5', 'percent6', 'percent7', 'percent8', 'percent9', 'percent10'], 'string', 'max' => 255],
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
        ];
    }
}
