<?php

namespace app\models;

use Yii;
use app\models\Object;
use app\models\Servis;

/**
 * This is the model class for table "booking".
 *
 * @property int $id
 * @property int $user_id
 * @property int $object_id
 * @property int $catroom_id
 * @property string $from
 * @property string $to
 * @property int $status
 * @property int $cancel
 * @property int $price
 * @property int $adult_count
 * @property int $child_count
 * @property string $childs_ages
 * @property int $currency_id
 * @property string $arrival_time
 * @property string $departure_time
 * @property string $comment
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * @inheritdoc
     */
    public $from_date;
    public $objid;
    public $type;
    public function rules()
    {
        return [
            [['user_id', 'object_id', 'catroom_id', 'status', 'cancel', 'adult_count', 'child_count', 'currency_id'], 'integer'],
            [['surname', 'username', 'phone', 'email'], 'required', 'message' => 'заполните поле'],
            [['from', 'to', 'arrival_time', 'departure_time', 'from_date', 'objid', 'type'], 'safe'],
            [['childs_ages', 'name', 'phone', 'email', 'username', 'surname'], 'string', 'max' => 255],
            [['comment'], 'string'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'object_id' => Yii::t('app', 'Object ID'),
            'catroom_id' => Yii::t('app', 'Room ID'),
            'from' => Yii::t('app', 'Дата заезда'),
            'to' => Yii::t('app', 'Дата выезда'),
            'status' => Yii::t('app', 'Status'),
            'cancel' => Yii::t('app', 'Cancel'),
            'price' => Yii::t('app', 'Стоимость'),
            'adult_count' => Yii::t('app', 'Adult Count'),
            'child_count' => Yii::t('app', 'Child Count'),
            'childs_ages' => Yii::t('app', 'Childs Ages'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'arrival_time' => Yii::t('app', 'Arrival Time'),
            'departure_time' => Yii::t('app', 'Departure Time'),
            'comment' => Yii::t('app', 'Comment'),
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

    public static function get_object_title($object_id)
    {
        $model = Object::find()->where(["id" => $object_id])->one();
        if(!empty($model)){
            $servicetitle = "";
            foreach (explode('-', Servis::findOne(['id'=>$model->service])->aliastwo) as $stitle) {
                $servicetitle .= $stitle . " ";
            }
            return $servicetitle ." ". $model->title;
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
