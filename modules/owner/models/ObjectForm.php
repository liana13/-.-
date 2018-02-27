<?php

namespace app\modules\owner\models;

use Yii;
use app\models\Object;
use app\models\Properties;
use app\models\Image;
use app\models\User;
use yii\web\UploadedFile;
use app\models\Servis;
use app\models\Person;
use app\models\Tarif;
use app\models\Field;
use app\models\Config;

class ObjectForm extends Object
{
    public $user_id;
    public $address_config;
    public $type;
    public $nameorg1;
    public $address;
    public $inn;
    public $email;
    public $fio;
    public $maddress;
    public $object_id;
    public $field_id;
    public $field_value;
    public $field222;
    public $field333;
    public $field444;
    public $image;
    public $file;
    public $minprice;
    public $curency;
    public $food;
    public $phone;
    public $phonesms;
    public $person_email;
    public $phoneperson;
    public $emailsms;
    public $phone_booking;
    public $email_booking;
    public $rayon_id;
    public $general0;
    public $general4;
    public $generalt1;
    public $generalt2;
    public $generalt3;
    public $value_image;
    public $main_image;
    public $propertygprs;
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
    public $website;
    public $price_property;
    public $zaezdto;
    public $viezd;

    public function rules()
    {
        $generalfree1 = Tarif::findOne(['id'=>1])->text;
        $generalonline1 = Tarif::findOne(['id'=>5])->text;
        $generalt11 = Tarif::findOne(['id'=>2])->text;
        $generalt21 = Tarif::findOne(['id'=>3])->text;
        $generalt31 = Tarif::findOne(['id'=>4])->text;
        $fields = [];
        for ($i=1; $i <=200 ; $i++) {
            $fields[]='field'.$i;
        }

        return [
            [$fields, 'safe'],
            [['title', 'service','address','general'], 'required', 'message' => 'заполните поле'],
            [['service', 'user_iphoned', 'tarif_id', 'minprice','propertygprs','field_id',], 'integer'],
            [['file', 'type','address', 'inn', 'phoneperson', 'fio', 'maddress', 'nameorg1', 'person_email', 'allow_review','field222', 'field333', 'field444', 'value_image', 'main_image'], 'safe'],
            [['title', 'phone', 'address', 'curency', 'field_value', 'field111', 'food', 'phonesms', 'emailsms','email_booking','phone_booking'], 'string', 'max' => 255, 'message' => 'значение должно содержать не более 255 символов.'],
            [['file'], 'image'],
            [['price_property','field31', 'description', 'address_config'], 'string'],
            [['general'], 'string', 'max' => 200, 'message' => 'значение должно содержать не более 200 символов.'],
            [['email',], 'email'],
            [['website','zaezdto', 'viezd', 'deletecatroom','curency_id'], 'string'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Номер объекта'),
            'user_id' => Yii::t('app', 'Имя пользователя'),
            'title' => Yii::t('app', 'Название*'),
            'tarif_id' => Yii::t('app', 'Тариф'),
            'service' => Yii::t('app', 'Сервис*'),
            'general' => Yii::t('app', 'Описание'),
            'address' => Yii::t('app', 'Адрес*'),
            'allow_review' => Yii::t('app', 'Разрешить писать отзывы об этом объекте'),
            'file' => Yii::t('app', 'Изображение'),
            'main_image' => Yii::t('app', 'Главное'),
            'general0' => Yii::t('app', 'Описание'),
            'general4' => Yii::t('app', 'Описание'),
            'generalt1' => Yii::t('app', 'Описание'),
            'generalt2' => Yii::t('app', 'Описание'),
            'generalt3' => Yii::t('app', 'Описание'),
        ];
    }

    public function create()
    {
        $object = new Object();
        $object->title = $this->title;
        $object->tarif_id = 0;
        if ($this->tarif_id != 0) {
            $object->act_oplata = "0";
            $object->new_tarif = $this->tarif_id;
        }
        $object->user_id = Yii::$app->user->getId();
        if (Person::findOne(['user_id'=>Yii::$app->user->getId()])) {
            $object->person_id = Person::findOne(['user_id'=>Yii::$app->user->getId()])->id;
        } else {
            $object->person_id = Yii::$app->user->getId();
        }
        $object->service = $this->service;
        $object->general = $this->general;
        $object->description = $this->description;
        $object->address = Config::findOne(["id"=>1])->address.", ".Config::findOne(["id"=>1])->title.", ".$this->address;
        $object->curency_id = $this->curency;
        $object->food_id = $this->food;
        $object->allow_review = $this->allow_review;
        $object->login = User::findOne(['id'=>Yii::$app->user->getId()])->username;
        $title = implode("-", explode(' ',$this->title));
        $service = implode(" ", explode('-',Servis::findOne(['id'=>$this->service])->aliastwo));
        $object->full_title = $service ." ". $this->title;

        $locality = Config::findOne(['id'=>1])->title;
        $object->alias = Servis::findOne(['id'=>$this->service])->aliastwo ."-". $title ."-". implode('-',explode(' ',$locality));
        $object->save();
        if ($object->save()) {
            $image = new Image();
            $image->file = UploadedFile::getInstances($this, 'file');
           if(!empty($image->file)) {
               mkdir('upload/images/'.$object->id, 0777, true);
               $v=0;$m=0;
               foreach ($image->file as $imagefile) {
                   $newimage = new Image();
                   $newimage->object_id = $object->id;
                   if ($this->main_image[$m] == 1) {
                       $newimage->main = 1;
                   } else {
                       $newimage->main = 0;
                   }
                   if ($object->new_tarif != 0) {
                       $newimage->value = $this->value_image[$v];
                   } else {
                       $newimage->value = $this->value_image;
                   }
                   $imageName = $object->id.'-'.rand(10000, 50000);
                   $imagefile->saveAs('upload/images/'.$object->id."/".$imageName . '.' . $imagefile->extension);
                   $newimage->image = "upload/images/".$object->id."/".$imageName . '.' . $imagefile->extension;
                   $newimage->save();
                   $v++;$m++;
                   $img_width = 800 ;
                   $img_height = 600 ;
                   $tmp_large=imagecreatetruecolor($img_width, $img_height);
                    $transparency = imagecolorallocatealpha($tmp_large, 255, 255, 255, 127);
                    imagefill($tmp_large, 0, 0, $transparency);
                   list($width,$height) = getimagesize($newimage->image);
                   $percent = $width/$height;
                   if ($width>=$height) {
                        $new_width = 800;
                        $new_height = 800/$percent;
                   } else {
                        $new_width = 600*$percent;
                        $new_height = 600;
                   }
                   if (explode(".", $newimage->image)[1] == "png") {
                       $im = imagecreatefrompng($newimage->image);
                       imagecopyresampled($tmp_large,$im,(800-$new_width)/2, (600-$new_height)/2, 0, 0,$new_width, $new_height, $width, $height);
                       if (imagepng($tmp_large, $newimage->image)) {
                       }
                   } elseif (explode(".", $newimage->image)[1] == "jpg" || explode(".", $newimage->image)[1] == "jpeg") {
                       $im = imagecreatefromjpeg($newimage->image);
                       imagecopyresampled($tmp_large,$im,(800-$new_width)/2, (600-$new_height)/2, 0, 0,$new_width, $new_height, $width, $height);
                       if (imagejpeg($tmp_large, $newimage->image)) {
                       }
                   }
                   $imgs = Image::findOne(['image'=>$newimage->image])->image;
                   if ($wimg = Config::findOne(['id'=>1])->watermark) {
                       $watermark = $wimg;
                       if (is_file($imgs)) {
                           if (explode(".", $imgs)[1] == "png") {
                               $im = imagecreatefrompng($imgs);
                           } elseif (explode(".", $imgs)[1] == "jpg" || explode(".", $imgs)[1] == "jpeg") {
                               $im = imagecreatefromjpeg($imgs);
                           }
                           if (explode(".", $wimg)[1] == "png") {
                               $stamp = imagecreatefrompng($wimg);
                           } elseif (explode(".", $wimg)[1] == "jpg" || explode(".", $imgs)[1] == "jpeg") {
                               $stamp = imagecreatefromjpeg($wimg);
                           }
                           $stamp = imagecreatefrompng($watermark);
                           $marge_right = 0;
                           $marge_bottom = 0;
                           $sx = imagesx($stamp);
                           $sy = imagesy($stamp);
                           imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
                           if (explode(".", $imgs)[1] == "png") {
                               if (imagepng($im,$imgs)) {
                                   imagedestroy($im);
                               }
                           } elseif (explode(".", $imgs)[1] == "jpg" || explode(".", $imgs)[1] == "jpeg") {
                               if (imagejpeg($im,$imgs)) {
                                   imagedestroy($im);
                               }
                           }
                       }
                   }
               }
           }
            $properties = Field::find()->where(['!=', 'class', ""])->all();
            foreach ($properties as $property) {
               $property_field = new Properties();
               $x= 'field'.$property->id;
               if (!empty($this->$x)) {
                   $property_field->field_value = $this->$x;
                   $property_field->field_id = $property->id;
                   $property_field->object_id = $object->id;
                   $property_field->save();
               }
            }
            $websitefield= new Properties();
            $websitefield->object_id = $object->id;
            $websitefield->field_id = 9;
            $websitefield->field_value = $this->website;
            $websitefield->save();

            $field6= new Properties();
            $field6->object_id = $object->id;
            $field6->field_id = 6;
            $field6->field_value = $this->field6;
            $field6->save();

            $zaezdfield= new Properties();
            $zaezdfield->object_id = $object->id;
            $zaezdfield->field_id = 49;
            $zaezdfield->field_value = $this->zaezdto;
            $zaezdfield->save();

            $viezdfield= new Properties();
            $viezdfield->object_id = $object->id;
            $viezdfield->field_id = 50;
            $viezdfield->field_value = $this->viezd;
            $viezdfield->save();

            $pricepropfield= new Properties();
            $pricepropfield->object_id = $object->id;
            $pricepropfield->field_id = 31;
            $pricepropfield->field_value = $this->price_property;
            $pricepropfield->save();

            $fieldprice= new Properties();
            $fieldprice->object_id = $object->id;
            $fieldprice->field_id = 12;
            $fieldprice->field_value = $this->minprice;
            $fieldprice->save();

            $fieldsgprs= new Properties();
            $fieldsgprs->object_id = $object->id;
            $fieldsgprs->field_id = 4;
            $fieldsgprs->field_value = $this->propertygprs;
            $fieldsgprs->save();

            $fields1= new Properties();
            $fields1->object_id = $object->id;
            $fields1->field_id = 32;
            $fields1->field_value = $this->field111;
            $fields1->save();

            $fields2= new Properties();
            $fields2->object_id = $object->id;
            $fields2->field_id = 1;
            $fields2->field_value = $this->field222;
            $fields2->save();

            $fields3= new Properties();
            $fields3->object_id = $object->id;
            $fields3->field_id = 34;
            $fields3->field_value = $this->field333;
            $fields3->save();

            $fields4 = new Properties();
            $fields4->object_id = $object->id;
            $fields4->field_id = 2;
            $fields4->field_value = $this->field444;
            $fields4->save();

            $fields11 = new Properties();
            $fields11->object_id = $object->id;
            $fields11->field_id = 11;
            $fields11->field_value = $this->field11;
            $fields11->save();

            $fieldemail = new Properties();
            $fieldemail->object_id = $object->id;
            $fieldemail->field_id = 38;
            $fieldemail->field_value = $this->email_booking;
            $fieldemail->save();

            $fieldphone = new Properties();
            $fieldphone->object_id = $object->id;
            $fieldphone->field_id = 36;
            $fieldphone->field_value = $this->phonesms;
            $fieldphone->save();

            $fieldphonesms = new Properties();
            $fieldphonesms->object_id = $object->id;
            $fieldphonesms->field_id = 7;
            $fieldphonesms->field_value = $this->phone;
            $fieldphonesms->save();

            $fieldemailsms = new Properties();
            $fieldemailsms->object_id = $object->id;
            $fieldemailsms->field_id = 39;
            $fieldemailsms->field_value = $this->emailsms;
            $fieldemailsms->save();

            $fieldaddress = new Properties();
            $fieldaddress->object_id = $object->id;
            $fieldaddress->field_id = 5;
            $fieldaddress->field_value = Config::findOne(['id'=>1])->address . ", ". Config::findOne(['id'=>1])->title.", ".$this->address;
            $fieldaddress->save();

            return true;
        }
        return false;
    }
}
