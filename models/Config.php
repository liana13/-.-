<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $title
 * @property string $logo
 * @property int $objectcat_id
 * @property string $address
 * @property int $by_price
 * @property int $by_rate
 * @property int $by_center
 * @property int $by_fromsea
 * @property int $by_highsea
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */

    public $file;
    public $water;
    public function rules()
    {
        return [
            [['title'], 'required', 'message'=>'заполните поле'],
            [['objectcat_id'], 'integer'],
            [['file', 'water'], 'file'],
            [['title', 'logo', 'logo', 'watermark', 'address', 'alias', 'alias_two', 'alias_three'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Название сайта (нас. пункт)'),
            'logo' => Yii::t('app', 'Лого'),
            'objectcat_id' => Yii::t('app', 'Тип нас. пункта'),
            'address' => Yii::t('app', 'Начало адреса'),
            'alias' => Yii::t('app', 'Псевдоним заголовка'),
            'alias_two' => Yii::t('app', 'Псевдоним заголовка (Родительный падеж)'),
            'alias_three' => Yii::t('app', 'Псевдоним заголовка (Винительный падеж)'),
        ];
    }
}
