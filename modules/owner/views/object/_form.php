<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Object;
use app\models\Locality;
use app\models\Country;
use app\models\Region;
use app\models\Tarif;
;
use app\models\Servis;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Object */
/* @var $form yii\widgets\ActiveForm */
$tarifs = Tarif::find()->orderBy('tarifid desc')->all();
?>
<div class="object-form">
    <div class="tarif">
        <h3>Пожалуйста, выберите тариф, на котором Вы бы хотели разместить Ваш объект:</h3>
        <?php foreach ($tarifs as $tarif): ?>
            <div class="pricing-col tarif-<?=$tarif->tarifid?>">
                <div class="pricing-table">
                    <div class="pricing-heading">
                        <h2><?= $tarif->title ?></h2>
                    </div>
                    <div class="content">
                        <div class="price">
                            <span class="amount"><?=$tarif->price?></span>
                            <span class="period"><?=$tarif->time?></span>
                        </div>
                        <ul class="pricing-items list-unstyled text-center">
                            <?php if ($tarif->id != 1): ?>
                                <li class="item"><span class="text-primary">Текстовая информация:</span> не ограничено </li>
                            <?php else: ?>
                                <li class="item"><span class="text-primary">Текстовая информация:</span> до <?=$tarif->text?> символов</li>
                            <?php endif; ?>
                            <?php if ($tarif->id != 1 && $tarif->id != 5): ?>
                                <li class="item"><span class="text-primary">Количество фото:</span> до <?=$tarif->photo?></li>
                            <?php elseif ($tarif->id == 5): ?>
                                <li class="item"><span class="text-primary">Количество фото:</span> до <?=explode(",", $tarif->photo)[0]?> фото+по <?=explode(",", $tarif->photo)[1]?> фото каждого номера</li>
                            <?php else: ?>
                                <li class="item"><span class="text-primary">Количество фото:</span> <?=$tarif->photo?></li>
                            <?php endif; ?>
                            <li class="item"><span class="text-primary">Отметка на карте:</span> <?=($tarif->id==1)?"нет":"да"?></li>
                            <li class="item"><span class="text-primary">Местонахождение в списках:</span> <?= strtolower($tarif->list_place)?></li>
                            <li class="pricing-button">
                                <?=Html::a('Выбрать', ['/owner/object/add/'.$tarif->id], ['class'=>"btn btn-common btn-square btn-raised"])?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>
