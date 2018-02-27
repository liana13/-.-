<?php

namespace app\models;

use Yii;
use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $dialogue_id
 * @property int $user_one
 * @property int $user_two
 * @property string $text
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
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
   public $contact;
   public $username;
   public $url;
   public $objecttitle;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_one', 'user_two', 'text'], 'required' , 'message' => 'заполните поле'],
            [['object_id', 'dialogue_id', 'user_one', 'user_two', 'status', 'statusowner', 'statuscabinet'], 'integer'],
            [['text', 'contact','username', 'url', 'objecttitle'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dialogue_id' => Yii::t('app', 'Диалог'),
            'user_one' => Yii::t('app', 'Отправитель'),
            'user_two' => Yii::t('app', 'Получатель'),
            'text' => Yii::t('app', 'Сообщение'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Редактировано'),
        ];
    }    
}
