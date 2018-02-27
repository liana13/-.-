<?php

use yii\helpers\Html;
use app\models\Object;
use app\models\Person;
use app\models\Tarif;

$this->title = Yii::t('app', 'Финансы');
$object = Object::findOne(['id'=>$objectid]);
$person = Person::findOne(['user_id'=>$object->user_id]);
?>
<div class="finance-update row">
    
    <?php if ($person): ?>
        <?php if ($object->new_tarif == 0): ?>
            <h3  class="text-left">Текущий объект находится на бесплатном тарифе</h3>
            <h3  class="text-left">Уникальный номер объекта: <?=$object->user_id."/".$object->id?> </h3>
        <?php elseif ($object->new_tarif == 1): ?>
            <h3  class="text-left">Текущий объект находится на тарифе 3</h3>
            <h3  class="text-left">Уникальный номер объекта: <?=$object->user_id."/".$object->id?> </h3>
            <div class="finance_div">
                <p><b>Договор</b> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../dogovor/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                <div class="result-item s-list col-sm-10">
                    <div class="desc-div pull-left">
                        <?php if ($person->type == 1): ?>
                            <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../act/$object->id'); w.print();", ['class'=>'underline']) ?> <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../schet/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                        <?php elseif ($person->type == 2): ?>
                            <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../act/$object->id'); w.print();", ['class'=>'underline']) ?> <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../schet/$object->id'); w.print();", ['class'=>'underline']) ?> <span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../../qvintacia/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                        <?php else: ?>
                            <p><span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../../qvintacia/$object->id'); w.print(); ", ['class'=>'underline']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php elseif ($object->new_tarif == 2): ?>
            <h3  class="text-left">Текущий объект находится на тарифе 2</h3>
            <h3  class="text-left">Уникальный номер объекта: <?=$object->user_id."/".$object->id?> </h3>
            <div class="finance_div">
                <p><b>Договор</b> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../dogovor/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                <div class="result-item s-list col-sm-10">
                    <div class="desc-div pull-left">
                        <?php if ($person->type == 1): ?>
                            <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../act/$object->id'); w.print();", ['class'=>'underline']) ?> <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../schet/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                        <?php elseif ($person->type == 2): ?>
                            <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../act/$object->id'); w.print();", ['class'=>'underline']) ?> <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../schet/$object->id'); w.print();", ['class'=>'underline']) ?> <span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../../qvintacia/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                        <?php else: ?>
                            <p><span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../../qvintacia/$object->id'); w.print(); ", ['class'=>'underline']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php elseif ($object->new_tarif == 3): ?>
            <h3  class="text-left">Текущий объект находится на тарифе 1</h3>
            <h3  class="text-left">Уникальный номер объекта: <?=$object->user_id."/".$object->id?> </h3>
            <div class="finance_div">
                <p><b>Договор</b> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../dogovor/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                <div class="result-item s-list col-sm-10">
                    <div class="desc-div pull-left">
                        <?php if ($person->type == 1): ?>
                            <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../act/$object->id'); w.print();", ['class'=>'underline']) ?> <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../schet/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                        <?php elseif ($person->type == 2): ?>
                            <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../act/$object->id'); w.print();", ['class'=>'underline']) ?> <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../schet/$object->id'); w.print();", ['class'=>'underline']) ?> <span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../../qvintacia/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                        <?php else: ?>
                            <p><span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../../qvintacia/$object->id'); w.print(); ", ['class'=>'underline']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php elseif ($object->new_tarif == 4): ?>
            <h3  class="text-left">Текущий объект находится на онлайн бронирование</h3>
            <h3  class="text-left">Уникальный номер объекта: <?=$object->user_id."/".$object->id?> </h3>
            <br>
            <h4 class="text-left text-danger">У Вас пока нет выставленных счетов. Счет на оплату Вам будет выставляться в конце каждого месяца. Оплата составляет 10% от суммы бронирований за месяц.</h4>
            <div class="finance_div">
                <p><b>Договор</b> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../dogovor/$object->id'); w.print();", ['class'=>'underline']) ?></p>
            </div>
        <?php endif; ?>
    <?php elseif (!$person && $object->new_tarif == 4): ?>
            <h3  class="text-left">Текущий объект находится на онлайн бронирование</h3>
            <h3  class="text-left">Уникальный номер объекта: <?=$object->user_id."/".$object->id?> </h3>
            <br>
            <h4 class="text-left text-danger">У Вас пока нет выставленных счетов. Счет на оплату Вам будет выставляться в конце каждого месяца. Оплата составляет 10% от суммы бронирований за месяц. Для получения договора, пожалуйста, перейдите в раздел <?= Html::a(Yii::t('app', '«Личные данные»'), ['/owner/person']) ?> и заполните соответствующие поля. После заполнения полей Вы сможете распечатать договор в этом разделе. Также личные данные необходимо заполнить, если Вам в дальнейшем для оплаты счетов потребуются платежные документы.</h4>
            <div class="finance_div">
                <p><b>Договор</b> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../dogovor/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                <div class="result-item s-list col-sm-10">
                    <div class="desc-div pull-left">
                        <p><b>Счёт</b> <?= explode(" ",$model->created_at)[0]?>  на сумму <?= Tarif::findOne(['tarifid'=>$model->tarif_id])->price?></p>
                        <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../act/$object->id'); w.print();", ['class'=>'underline']) ?> <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../schet/$object->id'); w.print(); ", ['class'=>'underline']) ?> <span>Платежная квинтация </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../../qvintacia/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                    </div>
                    <div class="status-div pull-right text-danger">
                        <?php if ($model->status == 0): ?>
                            <p>Не оплачен</p>
                        <?php else: ?>
                            <p>Оплачен</p>
                        <?php endif; ?>
                    </div>
                </div>
    <?php else: ?>
        <h3 class="text-center">Для получения договора и платежных документов, пожалуйста, перейдите в раздел <?= Html::a(Yii::t('app', '«Личные данные»'), ['/owner/person']) ?> и заполните соответствующие поля. После заполнения полей Вы сможете увидеть и распечатать документы в этом разделе.</h3>
    <?php endif; ?>
</div>
