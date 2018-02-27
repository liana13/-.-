<?php
use app\models\Person;
use app\models\Persontype;
use app\models\Tarif;
use app\models\Finance;

$this->title = "Платежка";
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
    $persontype = "";
} else {
    $persontype = "";
    $persontitle = $person->fio;
}
?>
<div class="qvintacia">
        <div class="table-responsive">
                <table class="tablemy tablefirst">
                    <tbody>
                        <tr>
                        <td class="td_right">И<br>
                            З<br>
                            В<br>
                            Е<br>
                            Щ<br>
                            Е<br>
                            Н<br>
                            И<br>
                            Е<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                            Кассир
                        </td>
                        <td class="td_two">
                            Получатель платежа: ИП Марфелев Константин Васильевич
                            <hr>
                            Учреждение банка КБ "КУБАНЬ КРЕДИТ" ООО г.Краснодар
                            <hr>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>БИК банка получателя:</td>
                                                        <td class="number_table">040349722</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>ИНН получателя:</td>
                                                        <td class="number_table">230300377299</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Счет получателя:</td>
                                    <td class="number_table">40802810300170000120</td>
                                    <td>Корр.счет:</td>
                                    <td class="number_table">30101810200000000722</td>
                                </tr>
                            </tbody>
                        </table>
                        <?=$persontype?> <?=$persontitle?><hr>
                        <table class="tablemy">
                            <tbody>
                                <tr>
                                    <?php if ($person->address): ?>
                                        <td class="td_two">Адрес: <?=$person->address?></td>
                                    <?php else: ?>
                                        <td class="td_two">Адрес:</td>
                                    <?php endif; ?>
                                    <?php if ($person->phone): ?>
                                        <td class="td_two">Контактный тел.: <?=$person->phone?></td>
                                    <?php else: ?>
                                        <td class="td_two">Контактный тел.:</td>
                                    <?php endif; ?>
                                </tr>
                            </tbody>
                        </table>
                        <table class="tablemy">
                            <tbody>
                            <tr>
                                <td class="td_two">
                                    <table class="tablemy tableqv">
                                        <tbody>
                                            <tr>
                                                <td class="number_table widthbig">Вид платежа</td>
                                                <td class="number_table widthbig">Информационные услуги на портале <?=Yii::$app->name?> по договору №<?=$model->user_id.'/'.$model->id?></td>
                                            </tr>
                                            <tr class="trtr">
                                                <td class="td_two">С условиями приема платежа ознакомлен и согласен</td>
                                                <td class="td_two text-right">Ком. сбор</td>
                                            </tr>
                                            <tr>
                                                <td class="td_two">Плательщик:</td>
                                                <td class="td_two text-right">ВСЕГО</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td class="number_table">Сумма</td>
                                <td class="number_table"><?=$pricetarif?></td>
                                <td class="number_table"> </td>
                                <td class="number_table"><?=$pricetarif?></td>
                            </tr>
                        </td>
                    </tr>
                </tbody>
            </table>
        </tbody>
    </table>
    </div>
    <div class="table-responsive">
            <table class="tablemy">
                <tbody>
                    <tr>
                    <td class="td_right">К<br>В<br>И<br>Т<br>А<br>Н<br>Ц<br>И<br>Я<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        Кассир
                    </td>
                    <td class="td_two">
                        Получатель платежа: ИП Марфелев Константин Васильевич
                        <hr>
                        Учреждение банка КБ "КУБАНЬ КРЕДИТ" ООО г.Краснодар
                        <hr>
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>БИК банка получателя:</td>
                                                    <td class="number_table">040349722</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>ИНН получателя:</td>
                                                    <td class="number_table">230300377299</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                    <table>
                        <tbody>
                            <tr>
                                <td>Счет получателя:</td>
                                <td class="number_table">40802810300170000120</td>
                                <td>Корр.счет:</td>
                                <td class="number_table">30101810200000000722</td>
                            </tr>
                        </tbody>
                    </table>
                    <?=$persontype?> <?=$persontitle?><hr>
                    <table class="tablemy">
                        <tbody>
                            <tr>
                                <?php if ($person->address): ?>
                                    <td class="td_two">Адрес: <?=$person->address?></td>
                                <?php else: ?>
                                    <td class="td_two">Адрес:</td>
                                <?php endif; ?>
                                <?php if ($person->phone): ?>
                                    <td class="td_two">Контактный тел.: <?=$person->phone?></td>
                                <?php else: ?>
                                    <td class="td_two">Контактный тел.:</td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                    <table class="tablemy">
                        <tbody>
                        <tr>
                            <td class="td_two">
                                <table class="tablemy tableqv">
                                    <tbody>
                                        <tr>
                                            <td class="number_table widthbig">Вид платежа</td>
                                            <td class="number_table widthbig">Информационные услуги на портале <?=Yii::$app->name?> по договору №<?=$model->user_id.'/'.$model->id?></td>
                                        </tr>
                                        <tr class="trtr">
                                            <td class="td_two">С условиями приема платежа ознакомлен и согласен</td>
                                            <td class="td_two text-right">Ком. сбор</td>
                                        </tr>
                                        <tr>
                                            <td class="td_two">Плательщик:</td>
                                            <td class="td_two text-right">ВСЕГО</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="number_table">Сумма</td>
                            <td class="number_table"><?=$pricetarif?></td>
                            <td class="number_table"> </td>
                            <td class="number_table"><?=$pricetarif?></td>
                        </tr>
                    </td>
                </tr>
            </tbody>
        </table>
    </tbody>
    </table>
</div>
</div>
