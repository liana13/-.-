<?php

use yii\helpers\Html;
use app\models\Object;
use app\models\Person;
use app\models\Tarif;
use app\models\Finance;

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppOwnerobjectAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$object = Object::findOne(['id'=>$objectid]);
$person = Person::findOne(['user_id'=>$object->user_id]);
$finances = Finance::find()->where(['object_id'=>$object->id])->orderBy('created_at DESC')->all();

$this->title = Yii::t('app', 'Финансы');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue skin-cabinet sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">
    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>
    <?= $this->render(
        '_left.php',
        ['object' => $object]
    ) ?>
    <div class="content-wrapper">
        <div class="finance-update container">
            <?php if ($object->tarif_id == 0): ?>
                <h3  class="text-left">Текущий объект находится на бесплатном тарифе</h3>
            <?php elseif ($object->tarif_id != 4): ?>
                <?php if ($object->tarif_id == 1): ?>
                    <h3  class="text-left">Текущий объект находится на тарифе 3</h3>
                <?php elseif ($object->tarif_id == 2): ?>
                    <h3  class="text-left">Текущий объект находится на тарифе 2</h3>
                <?php elseif($object->tarif_id == 3): ?>
                    <h3  class="text-left">Текущий объект находится на тарифе 1</h3>
                <?php endif; ?>
            <?php elseif ($object->tarif_id == 4): ?>
                <h3  class="text-left">Текущий объект находится на онлайн бронирование</h3>
            <?php endif;?>
            <?php if (!$person->type): ?>
                <?php if ($object->tarif_id == 4): ?>
                    <h4 class="text-left text-danger">У Вас пока нет выставленных счетов. Возможно Вы не заполнили "Личные данные", необходимые для выставления счета.</h4>
                <?php elseif ($object->tarif_id != 0 && $object->tarif_id != 4): ?>
                    <h3 class="text-center">Для получения договора и платежных документов, пожалуйста, перейдите в раздел <?= Html::a(Yii::t('app', '«Личные данные»'), ['/owner/person'], ['target'=>'_blank']) ?> и заполните соответствующие поля. После заполнения полей Вы сможете увидеть и распечатать документы в этом разделе.</h3>
                <?php endif; ?>
            <?php else: ?>
                <h3  class="text-left">Уникальный номер объекта: <?=$object->user_id."/".$object->id?></h3>
                <?php if ($object->tarif_id != 0 && $object->tarif_id != 4): ?>
                    <div class="finance_div">
                        <p><b>Договор</b> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../dogovor/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                        <div class="result-item s-list col-sm-10">
                            <div class="desc-div pull-left">
                                <?php if ($person->type == 1): ?>
                                    <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../act/$object->id'); w.print();", ['class'=>'underline']) ?>
                                        <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../schet/$object->id'); w.print();", ['class'=>'underline']) ?>
                                    </p>
                                <?php elseif ($person->type == 2): ?>
                                    <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../act/$object->id'); w.print();", ['class'=>'underline']) ?>
                                        <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../schet/$object->id'); w.print();", ['class'=>'underline']) ?>
                                        <span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../qvintacia/$object->id'); w.print();", ['class'=>'underline']) ?>
                                    </p>
                                <?php else: ?>
                                    <p><span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../qvintacia/$object->id'); w.print(); ", ['class'=>'underline']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php elseif($object->tarif_id == 4): ?>
                    <div class="finance_div">
                        <p><b>Договор</b> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../dogovor/$object->id'); w.print();", ['class'=>'underline']) ?></p>
                        <?php if (count($finances) == 0): ?>
                            <div class="result-item s-list col-sm-10">
                                <div class="desc-div pull-left">
                                    <h4 class="text-left text-danger">У Вас пока нет выставленных счетов.</h4>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($finances as $finance): ?>
                                <div class="result-item s-list col-sm-10">
                                    <div class="desc-div pull-left">
                                        <p><b>Счёт</b> на сумму <?=$finance->price?> (<?=$finance->updated_at?>)</p>
                                        <?php if ($person->type == 1): ?>
                                            <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../actonline/$finance->id'); w.print();", ['class'=>'underline']) ?>
                                                <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../schetonline/$finance->id'); w.print();", ['class'=>'underline']) ?>
                                            </p>
                                        <?php elseif ($person->type == 2): ?>
                                            <p><span>Акт</span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../actonline/$finance->id'); w.print();", ['class'=>'underline']) ?>
                                                <span> Счёт </span> <?= Html::a(Yii::t('app', 'печать'), "javascript: w=window.open('../../schetonline/$finance->id'); w.print();", ['class'=>'underline']) ?>
                                                <span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../qvintaciaonline/$finance->id'); w.print();", ['class'=>'underline']) ?>
                                            </p>
                                        <?php else: ?>
                                            <p><span>Платежная квинтация</span><?=Html::a(Yii::t('app','печать'),"javascript:w=window.open('../../qvintaciaonline/$finance->id'); w.print(); ", ['class'=>'underline']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="status-div pull-right">
                                        <?php if ($finance->status == 0): ?>
                                            <p class="text-danger">Не оплачен</p>
                                        <?php else: ?>
                                            <p class="text-success">Оплачен</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <footer class="main-footer">
        <p class="text-center">TvoyRay.ru 2007-2012. <?=Yii::$app->name?> все права защищены &copy; 2012-<?=date('Y')?></p>
    </footer>
</div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
