<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\Usertype;
use app\models\Person;

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
 class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $file;
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
            [['email', 'username'], 'required', 'message' => 'заполните поле.'],
            [['status', 'type'], 'integer'],
            [['username', 'password', 'password_reset_token','email', 'name', 'avatar', 'phone'], 'string', 'max' => 255],
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
            [['newpassword', 'passwordconfirm', 'lastvisited_at'], 'safe'],
            ['newpassword', 'string', 'min' => 6],
            ['passwordconfirm', 'compare', 'compareAttribute' => 'newpassword'],
            [['created_at', 'updated_at'], 'safe'],
            [['auth_key', 'password_reset_token'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('app', 'Номер телефона'),
            'id' => Yii::t('app', 'ID'),
            'password' => Yii::t('app', 'Пароль'),
            'name' => Yii::t('app', 'Имя'),
            'newpassword' => Yii::t('app', 'Пароль'),
            'passwordconfirm' => Yii::t('app', 'Повторите пароль'),
            'password_reset_token' => Yii::t('app', 'Восстановить пароль'),
            'email' => Yii::t('app', 'Эл. почта'),
            'type' => Yii::t('app', 'Тип пользователя'),
            'username' => Yii::t('app', 'Логин'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Дата регистрации'),
            'name_org_1' => Yii::t('app', 'Название1'),
            'name_org_2' => Yii::t('app', 'Название2'),
            'address' => Yii::t('app', 'Адрес'),
            'inn' => Yii::t('app', 'ИНН'),
            'phone' => Yii::t('app', 'Телефон'),
            'address_mestozhitelstvo' => Yii::t('app', ' Адрес(местожителство)'),
            'tphone' => Yii::t('app', 'Тел.'),
            'mails' => Yii::t('app', 'Эл. почта (доп.)'),
            'note' => Yii::t('app', 'Примечание'),
            'lastvisited_at' => Yii::t('app', 'Дата последнего входа'),
        ];
    }

    public function beforeSave($insert)
    {
        if($this->newpassword != null){
           $this->setPassword($this->newpassword);
        }
        return parent::beforeSave($insert);
    }

    public static function findIdentity($id)
    {
       return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

   /**
    * @inheritdoc
    */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

   /**
    * Finds user by email
    *
    * @param string $email
    * @return static|null
    */
    public static function findByUsername($email)
    {
        return static::find()->where(['email' => $email, 'status' => self::STATUS_ACTIVE])->orWhere(['username' => $email, 'status' => self::STATUS_ACTIVE])->one();
    }

   /**
    * Finds user by password reset token
    *
    * @param string $token password reset token
    * @return static|null
    */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

   /**
    * Finds out if password reset token is valid
    *
    * @param string $token password reset token
    * @return boolean
    */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
    * @inheritdoc
    */
    public function getId()
    {
       return $this->getPrimaryKey();
    }

    /**
    * @inheritdoc
    */
    public function getAuthKey()
    {
       return $this->auth_key;
    }

    /**
    * @inheritdoc
    */
    public function validateAuthKey($authKey)
    {
       return $this->getAuthKey() === $authKey;
    }

    /**
    * Validates password
    *
    * @param string $password password to validate
    * @return boolean if password provided is valid for current user
    */
    public function validatePassword($password)
    {
       return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
    * Generates password hash from password and sets it to the model
    *
    * @param string $password
    */
    public function setPassword($password)
    {
       $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
    * Generates "remember me" authentication key
    */
    public function generateAuthKey()
    {
       $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
    * Generates new password reset token
    */
    public function generatePasswordResetToken()
    {
       $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
    * Removes password reset token
    */
    public function removePasswordResetToken()
    {
       $this->password_reset_token = null;
    }

    public static function get_message_org1($id){
        $model = Person::find()->where(["user_id" => $id])->one();
        if(!empty($model)){
            return $model->name_org_1;
        } else {
            return "";
        }
    }

    public static function get_message_org2($id){
        $model = Person::find()->where(["user_id" => $id])->one();
        if(!empty($model)){
            return $model->name_org_2;
        } else {
            return "";
        }
    }

    public static function get_message_inn($id){
        $model = Person::find()->where(["user_id" => $id])->one();
        if(!empty($model)){
            return $model->inn;
        } else {
            return "";
        }
    }

    public static function get_message_phons($id){
        $model = Person::find()->where(["user_id" => $id])->one();
        if(!empty($model)){
            return $model->phone;
        } else {
            return "";
        }
    }

    public static function get_message_fio($id){
        $model = Person::find()->where(["user_id" => $id])->one();
        if(!empty($model)){
            return $model->fio;
        } else {
            return "";
        }
    }

    public static function get_message_email($id){
        $model = Person::find()->where(["user_id" => $id])->one();
        if(!empty($model)){
            return  $model->email;
        }
        return "";
    }

    public static function get_message_mesto($id){
        $model = Person::find()->where(["id" => $id])->one();
        if(!empty($model)){
            return  $model->address_mestozhitelstvo;
        }
        return "";
    }

    public static function get_message_username($id){
        $model = User::find()->where(["id" => $id])->one();
        if(!empty($model)){
            return  $model->username;
        }
        return "";
    }

    public static function get_message_regdate($id){
        $model = User::find()->where(["id" => $id])->one();
        if(!empty($model)){
            return  $model->created_at;
        }
        return "";
    }
}
