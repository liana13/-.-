<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Person;
use app\models\Persontype;

$persons = Person::findOne(['user_id'=>Yii::$app->user->getId()]);
$this->title = Yii::t('app', 'Личные данные');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">

    <h1>Ваши данные</h1>
    <div class="row shadow-out">
        <?php if ($persons): ?>
            <div class="col-md-8">
                <h2 class="h2_title"><?=Persontype::findOne(['id'=>$persons->type])->title?></h2>
                <?php if ($persons->fio): ?>
                    <h3>ФИО</h3>
                    <?=$persons->fio?>
                <?php endif; ?>
                <?php if ($persons->name_org_1): ?>
                    <h3>Наименование организации</h3>
                    <?=$persons->name_org_1?>
                <?php endif; ?>
                <?php if ($persons->inn): ?>
                    <h3>ИНН</h3>
                    <?=$persons->inn?>
                <?php endif; ?>
                <?php if ($persons->phone): ?>
                    <h3>Телефон</h3>
                    <?=$persons->phone?>
                <?php endif; ?>
                <?php if ($persons->email): ?>
                    <h3>Эл. почта</h3>
                    <?=$persons->email?>
                <?php endif; ?>
                <?php if ($persons->address_mestozhitelstvo): ?>
                    <h3>Адрес местожительства</h3>
                    <?=$persons->address_mestozhitelstvo?>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <?= Html::a(Yii::t('app', 'Изменить личные данные'), ['person/update/'.$persons->id], ['class' => 'btn btn-common']) ?>
            </div>
        <?php else: ?>
            <div class="col-md-6">
                <?= Html::a(Yii::t('app', 'Заполнить личные данные'), ['create'], ['class' => 'btn btn-common']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
