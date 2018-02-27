<?php
namespace app\modules\cabinet\models;

use Yii;
use yii\base\Model;
use app\models\User;

class PasswordForm extends User {

    public $newPassword;
    public $newPasswordRepeat;

    public function rules(){
        return [
            [['newPassword'], 'required', 'message' => 'заполните пароль'],
            [['newPasswordRepeat'], 'required', 'message' => 'повторите пароль'],
            [['newPassword', 'newPasswordRepeat'],  'string', 'min' => 6],
            [['newPassword', 'newPasswordRepeat'],  'filter', 'filter' => 'trim'],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'пароли должны совпадать'],
        ];
    }
    public function attributeLabels(){
        return [
            'newPassword'=>'Новый пароль',
            'newPasswordRepeat'=>'Повторите новый пароль',
        ];
    }

}
