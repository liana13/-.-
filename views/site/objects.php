<?php
use yii\widgets\ListView;
?>
<div class="objects-div">
    <h2 class="title text-center">Поиск: <span class="blue-span"><?=$searchModel->search?></span></h2>
    <?php echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'result-item s-list'],
        'summary'=>'',
        'itemView'=>'_object',
    ]); ?>
</div>
