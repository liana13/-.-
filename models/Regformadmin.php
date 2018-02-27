<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\Usertype;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $type
 * @property string $auth_key
 * @property string $password
 * @property string $password_reset_token
 * @property string $email
 * @property string $username
 * @property int $status
 */
 class Regformadmin extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $newpassword;
    public $passwordconfirm;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
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
    public function rules()
    {
        return [
            [['email', 'username', 'newpassword', 'passwordconfirm'], 'required', 'message' => 'заполните поле.'],
            [['status', 'type'], 'integer'],
            [['username', 'password', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            ['email', 'email'],
            ['username', 'unique',
                'targetClass' => '\app\models\User',
                'message' => 'Пользователь с таким ником уже зарегистрирован на сайте, пожалуйста, укажите другой ник.'
            ],
            ['email', 'unique',
                'targetClass' => '\app\models\User',
                'message' => ' Пользователь с такой электронной почтой уже зарегистрирован на сайте, пожалуйста, укажите другую электронную почту.'
            ],
            [['password_reset_token'], 'unique'],
            [['is_agent', 'newpassword', 'passwordconfirm'], 'safe'],
            ['newpassword', 'string', 'min' => 6],
            ['passwordconfirm', 'compare', 'compareAttribute' => 'newpassword'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password' => Yii::t('app', 'Пароль'),
            'newpassword' => Yii::t('app', 'Пароль'),
            'passwordconfirm' => Yii::t('app', 'Повторите пароль'),
            'password_reset_token' => Yii::t('app', 'Восстановить пароль'),
            'email' => Yii::t('app', 'Эл. почта'),
            'type' => Yii::t('app', 'Тип пользователя'),
            'username' => Yii::t('app', 'Имя пользователя'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Дата регистрации'),
        ];
    }

    public function beforeSave($insert)
    {
        if($this->newpassword != null){
           $this->setPassword($this->newpassword);
        }
        return parent::beforeSave($insert);
    }

    public function setPassword($password)
    {
       $this->password = Yii::$app->security->generatePasswordHash($password);
    }
}
