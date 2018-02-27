<?php

namespace app\models;

use Yii;
use app\models\Persontype;
/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property int $type
 * @property string $name_org_1
 * @property string $name_org_2
 * @property string $address
 * @property string $inn
 * @property string $phone
 * @property string $fio
 * @property string $address_mestozhitelstvo
 * @property string $tphone
 * @property string $email
 * @property string $mails
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required' , 'message' => 'заполните поле'],
            [['type', 'user_id'], 'integer'],
            [['name_org_1', 'name_org_2', 'address', 'inn', 'phone', 'fio', 'address_mestozhitelstvo'], 'string'],
            [['tphone', 'email', 'mails', 'login', 'priming'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'user_id' => 'ID',
            'name_org_1' => 'Наимен. орг.',
            'name_org_2' => 'Полное наимен. орг.',
            'address' => 'Юрид. адрес',
            'inn' => 'ИНН',
            'phone' => 'Тел.',
            'fio' => 'ФИО',
            'address_mestozhitelstvo' => 'Адрес мест.',
            'tphone' => 'Тел(доп.)',
            'email' => 'Эл. почта',
            'mails' => 'Эл. почта (доп.)',
            'login'=> 'Логин',
            'priming'=> 'Примечание',
        ];
    }
    public static function get_message_type($id){
        $model = Persontype::find()->where(["id" => $id])->one();
        if(!empty($model)){
            return $model->title;
        } else {
            return "";
        }
      }
}
