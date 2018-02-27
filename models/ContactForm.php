<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Object;
use app\models\Properties;
use app\models\ContactForm;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $objectemail;
    public $object;
    public $name;
    public $phone;
    public $from;
    public $to;
    public $count;
    public $email;
    public $url;
    public $info;
    public $obj;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'count', 'from', 'to'], 'required' , 'message' => 'заполните поле'],
            ['email', 'email'],
            [['name', 'phone', 'from', 'to', 'count', 'email', 'info', 'object', 'objectemail', 'url'], 'string'],
            [['obj'], 'integer'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Фамилия Имя Отчество',
            'phone' => 'Номер мобильного телефона*',
            'from' => 'Даты пребывания с',
            'to' => 'по',
            'count' => 'Количество человек',
            'email' => 'E-mail',
            'info' => 'Дополнительная информация',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact()
    {
        $body = "<br>Телефон: ".$this->phone."<br> Эл. почта: ".$this->email;
        if ($this->from != '') {
            $body .= "<br>Дата от: ".$this->from;
        }
        if ($this->to != '') {
            $body .= "<br>Дата до: ".$this->to;
        }
        if ($this->count != '') {
            $body .= "<br>Количество человек: ".$this->count;
        }
        if ($this->info != '') {
            $body .= "<br>Дополнительная информация: ".$this->info;
        }
        if ($this->objectemail) {
            Yii::$app->mailer->compose()
                ->setTo($this->objectemail)
                ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                ->setSubject('Заявка на бронирование на'.Yii::$app->name)
                ->setHtmlBody("Пользователь ".$this->name." отправил заявку на бронирование в Вашем объекте <a href ='".$this->url."'>".$this->object."</a>." . $body)
                ->send();
                return true;

        } else {
            return false;
        }
    }
}
