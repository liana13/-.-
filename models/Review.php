<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\Object;
use app\models\User;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property int $object_id
 * @property int $user_id
 * @property int $rate
 * @property string $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review';
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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'user_id', 'rate', 'description'], 'required', 'message' => 'заполните поле.'],
            [['object_id', 'user_id', 'rate', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['locality'], 'string', 'max'=>255],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'object_id' => Yii::t('app', 'Объект'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'rate' => Yii::t('app', 'Бал'),
            'description' => Yii::t('app', 'Описание'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Редактировано'),
        ];
    }

    public static function get_message_object($object_id)
    {
        $model = Object::find()->where(["id" => $object_id])->one();
        if(!empty($model)){
            return  $model->alias;
        }
        return null;
    }

    public static function get_message_user($user)
    {
        $model = User::find()->where(["id" => $user])->one();
        if(!empty($model)){
            return  $model->username;
        }
        return null;
    }
}
