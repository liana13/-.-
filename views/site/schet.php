<?php
use app\models\Person;
use app\models\Persontype;
use app\models\Tarif;
use app\models\Finance;

$this->title = "Счет";
$person = Person::findOne(['user_id'=>$model->user_id]);
if ($model->new_tarif == 4) {
    $pricetarif = explode("руб.",$finance->price)[0];
} else {
    $pricetarif = explode("руб.",Tarif::findOne(['tarifid'=>$model->new_tarif])->price)[0];
    $priceletters = Tarif::findOne(['tarifid'=>$model->new_tarif])->price_letters;
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
<div class="schett">
    <p><strong>Исполнитель: ИП Марфелев  Константин Васильевич</strong><br>
	<strong>Адрес: Краснодарский  край г. Белореченск ул. Таманской Армии 114/372</strong></p>

    <p class="text-center"><strong>Образец заполнения  платежного поручения</strong></p>
    <div class="table-responsive">
        <table border="1" cellspacing="0" cellpadding="0" class="tablemy">
        	<tbody>
                <tr>
            		<td>Получатель: <br>ИП Марфелев Константин Васильевич </td>
            		<td>ИНН<br>Сч. №</td>
            		<td>230300377299<br>40802810300170000120</td>
            	</tr>
            	<tr>
            		<td>Банк получателя:<br>КБ «Кубань Кредит»    ООО<br>г. Краснодар</td>
            		<td>БИК<br>Сч. №</td>
            		<td>040349722<br>30101810200000000722</td>
            	</tr>
            </tbody>
        </table>
    </div>
    <p class="text-center"><strong>СЧЁТ  № <?=$model->user_id?>/<?=$model->id?>  от </strong> «___» ____________ 20___ г.</p>
    <p class="text-left"><strong>Заказчик: <?=$persontype?> <?=$persontitle?></strong></p>
    <p class="text-left"><strong>Плательщик: <?=$persontype?> <?=$persontitle?> </strong></p>
    <div class="table-responsive">
        <table border="1" cellspacing="0" cellpadding="0" class="tablemy">
        	<tbody>
                <tr>
            		<td>Наименование    работы <br>(услуги) </td>
            		<td>Единица измерения</td>
            		<td>Количество</td>
            		<td>Цена</td>
            		<td>Сумма</td>
            	</tr>
            	<tr>
            		<td>Информационные    услуги на портале <?=Yii::$app->name?></td>
            		<td><?=($model->new_tarif == 4)?'Усл.':'Год'?></td>
            		<td>1</td>
            		<td><?=$pricetarif?></td>
            		<td><?=$pricetarif?></td>
            	</tr>
            </tbody>
        </table>
    </div>
    <div class="pull-right">
        <p class="text-left"><b>Итого:</b> <?=$pricetarif?></p>
        <p class="text-left"><b> НДС не применяется</b></p>
    </div>
    <br>
    <br>
    <div class="">
        <p class="text-left"><b>Всего на сумму:</b> <?=$pricetarif?> рублей.</p>
        <?php if ($model->new_tarif!=4): ?>
            <p class="text-left"><b>Сумма прописью:</b> <?=$priceletters?> рублей.</p>
        <?php else: ?>
            <p class="text-left"><b>Сумма прописью:</b> _________________________________ </p>
        <?php endif; ?>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-sm-4">
            <p><b>Предприниматель</b> <img src="<?=Yii::$app->request->baseUrl?>/images/pt.jpg" style="width: 100px;"><b>( Марфелев К.В. )</b></p>
            <img src="<?=Yii::$app->request->baseUrl?>/images/pch.jpg" style="width: 180px;height: 180px;">
        </div>
    </div>
</div>
