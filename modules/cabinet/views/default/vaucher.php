<?php
use app\models\User;
use app\models\Servis;
use app\models\Catroom;
use app\models\Childage;
use app\models\Properties;

$catroom_title = Catroom::findOne(['id'=>$model->catroom_id])->title;
$catroom = $catroom_title;
$user =  User::findOne(['id'=>$model->user_id]);
$from = $model->from;
$to = $model->to;
$datediff = strtotime($to) - strtotime($from);
$tarb = floor($datediff / (60 * 60 * 24));

$servicetitle = "";
foreach (explode('-', Servis::findOne(['id'=>$object->service])->aliastwo) as $stitle) {
    $servicetitle .= $stitle . " ";
}
$objtitle = $object->full_title;
if (Properties::findOne(['object_id'=>$model->object_id, 'field_id'=>7])) {
    $phone = Properties::findOne(['object_id'=>$model->object_id, 'field_id'=>7])->field_value;
} else {
    $phone = "";
}
if (Properties::findOne(['object_id'=>$model->object_id, 'field_id'=>6])) {
    $gps = Properties::findOne(['object_id'=>$model->object_id, 'field_id'=>6])->field_value;
} else {
    $gps = "";
}
$datediff = strtotime($model->to) - strtotime($model->from);
?>
<div class="dogovor">
    <h3 class="text-center">Бронирование №<?=$object->id .'-'. $model->id?></h3>
    <div class="col-sm-offset-2 col-sm-8 item-body">
        <table class="table table-striped">
            <tbody class="">
                <tr>
                    <th>ФИ: </th>
                    <td><?=$model->name?></td>
                </tr>
                <tr>
                    <th>Объект: </th>
                    <td><?=$objtitle?></td>
                </tr>
                <tr>
                    <th>Адрес: </th>
                    <td><?=$object->address?></td>
                </tr>
                <tr>
                    <th>Телефон: </th>
                    <td><?=$phone?></td>
                </tr>
                <tr>
                    <th>GPS координаты: </th>
                    <td><?=$gps?></td>
                </tr>
                <tr>
                    <th>Общая сумма за <?=$tarb?> суток: </th>
                    <td><?=$model->price?> руб.</td>
                </tr>
                <tr>
                    <th>Категория номера: </th>
                    <td><?=$catroom?></td>
                </tr>
                <tr>
                    <th>Дата заезда: </th>
                    <td>
                        <?=$model->from?>
                        <?php if (Properties::findOne(['field_id'=>49, 'object_id'=>$model->object_id])): ?>
                            <b style="margin-left:30px;"> Заезд с:</b> <?=Properties::findOne(['field_id'=>49, 'object_id'=>$model->object_id])->field_value?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Дата выезда: </th>
                    <td>
                        <?=$model->to?>
                        <?php if (Properties::findOne(['field_id'=>50, 'object_id'=>$model->object_id])): ?>
                            <b style="margin-left:30px;"> Выезд до:</b> <?=Properties::findOne(['field_id'=>50, 'object_id'=>$model->object_id])->field_value?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Взрослых: </th>
                    <td><?=$model->adult_count?></td>
                </tr>
                <?php if ($model->child_count): ?>
                    <tr>
                        <th>Детей: </th>
                        <td><?=$model->child_count?> (<?=$model->childs_ages?> лет)</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="part_two">
    </div>
</div>
