<?php

namespace app\models;

use Yii;
use app\models\Catroom;
/**
 * This is the model class for table "childage".
 *
 * @property int $id
 * @property int $catroom_id
 * @property int $child_count
 * @property int $child_age
 */
class Childage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'childage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catroom_id', 'child_count', 'child_age'], 'required' , 'message' => 'заполните поле'],
            [['catroom_id', 'child_count', 'child_age'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'catroom_id' => Yii::t('app', 'Категория номеров'),
            'child_count' => Yii::t('app', 'Число детей'),
            'child_age' => Yii::t('app', 'Детский возраст'),
        ];
    }
    public static function get_message_catroom($catroom_id){
        $model = Catroom::find()->where(["id" => $catroom_id])->one();
        if(!empty($model)){
            return  $model->title;
        }

        return null;
      }
}
