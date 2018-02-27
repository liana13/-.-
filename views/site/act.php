<?php
use app\models\Person;
use app\models\Persontype;
use app\models\Tarif;
use app\models\Finance;

$this->title = "АКТ";
$person = Person::findOne(['user_id'=>$model->user_id]);
if ($model->new_tarif == 4) {
    $pricetarif = explode("руб.",$finance->price)[0];
} else {
    $pricetarif = explode("руб.",Tarif::findOne(['tarifid'=>$model->new_tarif])->price)[0];
}
if ($person->type == 1) {
    $persontitle = $person->name_org_1;
    $persontype = "";
} elseif ($person->type == 2) {
    $persontitle = $person->fio;
    $persontype = "ИП";
} else {
    $persontype = "";
    $persontitle = $person->fio;
}
?>
<div class="act">
    <p class="text-left">
        <strong>Исполнитель: ИП  Марфелев Константин Васильевич</strong><br>
    	<strong>Адрес: Краснодарский  край г. Белореченск ул. Таманской Армии 114/372</strong>
    </p>
    <p class="text-center">
    	<strong> АКТ № <?=$model->user_id?>/<?=$model->id?> от</strong><strong> </strong><strong> «___» ____________ 20___ г</strong><br>
    </p>
    <p class="text-left"><strong>Заказчик:  <?=$persontype?> <?=$persontitle?></strong></p>
    <div class="table-responsive">
        <table border="1" cellspacing="0" cellpadding="0" class="tablemy">
            <tbody>
                <tr>
                    <td>Наименование работы (услуги)</td>
                    <td>Единица измерения</td>
                    <td>Количество</td>
                    <td>Цена</td>
                    <td>Сумма</td>
                </tr>
                <tr>
                    <td>Информационные услуги на портале <?=Yii::$app->name?></td>
                    <td><?=($model->new_tarif == 4)?'Усл.':'Год'?></td>
                    <td><strong>1</strong></td>
                    <td><strong><?=$pricetarif?></strong></td>
                    <td><strong><?=$pricetarif?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="text-right"><strong>Итого: <?=$pricetarif?></strong></p>
    <p class="text-left"><strong>Всего оказано услуг  на сумму:</strong> <?=$pricetarif?> рублей</p>
    <p class="text-left">Вышеперечисленные услуги выполнены полностью и в срок.  Заказчик претензий по объему, качеству и срокам оказания услуг не имеет.</p>
    <div class="row">
        <div style="width:50%; padding: 0 15px; float:left;">
            <p>Исполнитель:  <img src="<?=Yii::$app->request->baseUrl?>/images/pt.jpg" class="imgact"> <span>подпись</span></p>
            <span>М. П.</span>
            <img src="<?=Yii::$app->request->baseUrl?>/images/pch.jpg" style="width: 180px; height: 180px;">
        </div>
        <div style="width:50%; padding: 0 15px; float:left;">
            <p>Заказчик: <span style="margin-left:100px;">подпись</span></p>
            <span>М. П.</span>
        </div>
    </div>

</div>
