<?php
use app\models\Catroom;
?>
<div class="dogovor">
    <div class="col-sm-offset-2 col-sm-8 item-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Номер брони </th>
                    <th>Дата заезда </th>
                    <th>Дата отъезда </th>
                    <th>Кол-во гостей</th>
                    <th>Категория</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody class="">
                <?php foreach ($booking as $book): ?>
                    <tr>
                        <td>№ <?=$book->object_id.'-'. $book->id?></td>
                        <td><?=$book->from?></td>
                        <td><?=$book->to?></td>
                        <td><?=$book->adult_count?> взр.<?=($book->child_count)?', '.$book->child_count.' детей( '.$book->childs_ages.' лет)':''?></td>
                        <td><?=Catroom::findOne(['id'=>$book->catroom_id])->title?></td>
                        <td><?=$book->price?> руб.</td>
                        <?php if ($book->status == 0): ?>
                            <td>незаезд</td>
                        <?php else: ?>
                            <?php if ($book->cancel == 2): ?>
                                <td>Отменено владельцем</td>
                            <?php elseif ($book->cancel == 1): ?>
                                <td>Отменено пользователем</td>
                            <?php else: ?>
                                <td></td>
                            <?php endif; ?>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="part_two">
    </div>
</div>
<script>
setTimeout(function () {
    if(confirm('Распечатать документ?')){
        window.print();
    }
}, 1000)
</script>
