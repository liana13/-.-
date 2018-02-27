<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Booking;
use app\models\Servis;
use app\models\Object;
use execut\widget\TreeView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$object = Object::find()->where(['user_id'=>Yii::$app->user->getId()])->andWhere(['tarif_id'=>4])->all();
$this->title = Yii::t('app', 'Бронирования');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">
    <h1><?= Html::encode($this->title) ?></h1>
    <ul class="book-object">
        <?php foreach ($object as $obj): ?>
            <?php $servicetitle = "";
            foreach (explode('-', Servis::findOne(['id'=>$obj->service])->aliastwo) as $stitle) {
                $servicetitle .= $stitle . " ";
            }
            $objtitle = $servicetitle . $obj->title;
            $books = Booking::find()->where(['object_id'=>$obj->id])->all() ?>
            <?php if ($obj->unread == 1 || count($books) == 0): ?>
                <li><h4><?=$objtitle.', '.$obj->address?><?=Html::a('Выбрать', ['/owner/booking/future/'.$obj->id], ['class'=>'btn btn-primary pull-right'])?></h4></li>
            <?php else: ?>
                <li>
                    <h4><?=$objtitle.', '.$obj->address?> <i class="fa fa-exclamation" aria-hidden="true"></i>
                        <?=Html::a('Выбрать', ['/owner/booking/future/'.$obj->id], ['class'=>'btn btn-primary pull-right'])?>
                    </h4>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
