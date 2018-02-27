<?php

namespace app\models;

use Yii;
use app\models\Field;
use app\models\Rate;
use app\models\Object;

class Object extends \yii\db\ActiveRecord
{
    public $weekdays;
    public $deletecatroom;
    public $address_config;
    public $emailuser;
    public $email;
    public $mails;
    public $file;
    public $img;
    public $field1;
    public $field2;
    public $field3;
    public $field4;
    public $field5;
    public $field6;
    public $field7;
    public $field8;
    public $field9;
    public $field10;
    public $field11;
    public $field12;
    public $field13;
    public $field14;
    public $field15;
    public $field16;
    public $field17;
    public $field18;
    public $field19;
    public $field20;
    public $field21;
    public $field22;
    public $field23;
    public $field24;
    public $field25;
    public $field26;
    public $field27;
    public $field28;
    public $field29;
    public $field30;
    public $field31;
    public $field32;
    public $field33;
    public $field34;
    public $field35;
    public $field36;
    public $field37;
    public $field38;
    public $field39;
    public $field40;
    public $field41;
    public $field42;
    public $field43;
    public $field44;
    public $field45;
    public $field46;
    public $field47;
    public $field48;
    public $field49;
    public $field50;
    public $field51;
    public $field52;
    public $field53;
    public $field54;
    public $field55;
    public $field56;
    public $field57;
    public $field58;
    public $field59;
    public $field60;
    public $field61;
    public $field62;
    public $field63;
    public $field64;
    public $field65;
    public $field66;
    public $field67;
    public $field68;
    public $field69;
    public $field70;
    public $field71;
    public $field72;
    public $field73;
    public $field74;
    public $field75;
    public $field76;
    public $field77;
    public $field78;
    public $field79;
    public $field80;
    public $field81;
    public $field82;
    public $field83;
    public $field84;
    public $field85;
    public $field86;
    public $field87;
    public $field88;
    public $field89;
    public $field90;
    public $field91;
    public $field92;
    public $field93;
    public $field94;
    public $field95;
    public $field96;
    public $field97;
    public $field98;
    public $field99;
    public $field100;
    public $field101;
    public $field102;
    public $field103;
    public $field104;
    public $field105;
    public $field106;
    public $field107;
    public $field108;
    public $field109;
    public $field110;
    public $field111;
    public $field112;
    public $field113;
    public $field114;
    public $field115;
    public $field116;
    public $field117;
    public $field118;
    public $field119;
    public $field120;
    public $field121;
    public $field122;
    public $field123;
    public $field124;
    public $field125;
    public $field126;
    public $field127;
    public $field128;
    public $field129;
    public $field130;
    public $field131;
    public $field132;
    public $field133;
    public $field134;
    public $field135;
    public $field136;
    public $field137;
    public $field138;
    public $field139;
    public $field140;
    public $field141;
    public $field142;
    public $field143;
    public $field144;
    public $field145;
    public $field146;
    public $field147;
    public $field148;
    public $field149;
    public $field150;
    public $field151;
    public $field152;
    public $field153;
    public $field154;
    public $field155;
    public $field156;
    public $field157;
    public $field158;
    public $field159;
    public $field160;
    public $field161;
    public $field162;
    public $field163;
    public $field164;
    public $field165;
    public $field166;
    public $field167;
    public $field168;
    public $field169;
    public $field170;
    public $field171;
    public $field172;
    public $field173;
    public $field174;
    public $field175;
    public $field176;
    public $field177;
    public $field178;
    public $field179;
    public $field180;
    public $field181;
    public $field182;
    public $field183;
    public $field184;
    public $field185;
    public $field186;
    public $field187;
    public $field188;
    public $field189;
    public $field190;
    public $field191;
    public $field192;
    public $field193;
    public $field194;
    public $field195;
    public $field196;
    public $field197;
    public $field198;
    public $field199;
    public $field200;
    public $emailsms;
    public $phone_booking;
    public $email_booking;
    public $propertygprs;
    public $website;
    public $price_property;
    public $zaezdto;
    public $viezd;
    public $address_show;
    public $price;

    public static function tableName()
    {
        return 'object';
    }

    public function rules()
    {
        $fields = [];
        for ($i=1; $i <=200 ; $i++) {
            $fields[]='field'.$i;
        }
        return [
            [$fields, 'safe'],
            ['weekdays','safe'],
            [['title', 'general', 'service', 'user_id'], 'required' , 'message' => 'заполните поле'],
            [['description', 'general', 'price_property', 'price'], 'string'],
            [['service', 'user_id', 'tarif_id', 'active', 'edit', 'allow_review', 'person_id', 'food_id', 'unread'], 'integer'],
            [['created_at','zaezdto', 'viezd', 'updated_at','website', 'emailuser', 'email', 'mails', 'file', 'img', 'email_booking', 'deletecatroom','curency_id', 'end_date','new_tarif','propertygprs', 'phone_booking', 'emailsms'], 'safe'],
            [['title', 'alias', 'login', 'act_oplata', 'address', 'full_title', 'address_config'], 'string', 'max' => 255],
            ['mails', 'email'],
            ['alias', 'unique'],
            [['end_date','active_online', 'phone'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Номер объекта'),
            'login' => Yii::t('app', 'Логин'),
            'user_id' => Yii::t('app', 'Имя пользователя'),
            'title' => Yii::t('app', 'Название'),
            'tarif_id' => Yii::t('app', 'Тариф'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Редактировано'),
            'description' => Yii::t('app', 'Описание'),
            'service' => Yii::t('app', 'Сервис'),
            'general' => Yii::t('app', 'Общее'),
            'address' => Yii::t('app', 'Адрес'),
            'allow_review' => Yii::t('app', 'Разрешить писать отзыв'),
            'alias' => Yii::t('app', 'Алиас'),
            'mails' => Yii::t('app', 'Эл. почта'),
        ];
    }

    public static function get_object_title($objid){
        $model = Object::find()->where(["id" => $objid])->one();
        if(!empty($model)){
            return $model->full_title;
        }
        return null;
    }

    public static function get_object_alias($objid){
        $model = Object::find()->where(["id" => $objid])->one();
        if(!empty($model)){
            return $model->alias;
        }
        return null;
    }

    public static function get_message_user($user){
        $model = User::find()->where(["id" => $user])->one();
        if(!empty($model)){
            return $model->username;
        }
        return null;
    }

    public static function get_message_tarif($tarif){
        $model = Tarif::find()->where(["tarifid" => $tarif])->one();
        if(!empty($model)){
            return  $model->title;
        }
        return null;
    }

    public function getRate()
    {
        return $this->hasMany(Rate::className(), ['object_id' => 'id']);
    }

    public function getCenter()
    {
        return $this->hasMany(Properties::className(), ['object_id' => 'id'])
            ->from(['center' => Properties::tableName()])->andOnCondition(['center.field_id' => 2]);
    }

    public function getFromsea()
    {
        return $this->hasMany(Properties::className(), ['object_id' => 'id'])
            ->from(['fromsea' => Properties::tableName()])->andOnCondition(['fromsea.field_id' => 1]);
    }

    public function getHighsea()
    {
        return $this->hasMany(Properties::className(), ['object_id' => 'id'])
            ->from(['highsea' => Properties::tableName()])->andOnCondition(['highsea.field_id' => 34]);
    }

    public function getPrice()
    {
        return $this->hasMany(Properties::className(), ['object_id' => 'id'])
            ->from(['price' => Properties::tableName()])->andOnCondition(['price.field_id' => 12]);
    }
}
