<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Object;

/**
 * ContactForm is the model behind the contact form.
 */
class AdminForm extends Model
{
    public $email;
    public $body;
    public $objectid;
    public $object;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'body', 'objectid', 'object'], 'required', 'message'=>'заполните поле'],
            ['email', 'email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Ваш адрес электронной почты',
            'body' => 'Текст сообщения',
            'verifyCode' => 'Проверочный код',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        $url = Yii::$app->urlManager->createAbsoluteUrl(['/'.Object::findOne(['id'=>$this->objectid])->alias]);
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                ->setSubject('Сообщение об ошибке в '.Yii::$app->name)
                ->setHtmlBody('От '.$this->email.'<br>Сообщение: '.$this->body.' <br>(Объект - <a href="'.$url.'">'.$this->object.'</a>)')
                ->send();

            return true;
        }
        return false;
    }
}
