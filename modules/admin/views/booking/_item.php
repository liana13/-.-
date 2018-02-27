<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use app\models\User;
use app\models\Servis;
use app\models\Object;
use app\models\Catroom;
use app\models\Image;
use app\models\Locality;
/* @var $this yii\web\View */
/* @var $model app\models\Ads */
$catroom = Catroom::findOne(['id'=>$model->catroom_id])->title;
$user =  User::findOne(['id'=>$model->user_id]);
$object = Object::findOne(['id'=>$model->object_id]);
$img = Image::findOne(['object_id'=>$model->object_id]);
$servicetitle = "";
foreach (explode('-', Servis::findOne(['id'=>$object->service])->aliastwo) as $stitle) {
    $servicetitle .= $stitle . " ";
}
$objtitle = $servicetitle . $object->title;
$datediff = strtotime($model->to) - strtotime($model->from);
$tarb = floor($datediff / (60 * 60 * 24));
?>
<div class="row">
    <div class="col-lg-10">
        <div class="item-container item-featured reviews-enabled">
            <div class="content bron">
                <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th colspan="2" class="text-primary"><?=Html::a($objtitle, ['/'.$object->alias], ['target'=>'_blank'])?></th>
                                </tr>
                                <tr>
                                    <th>№<?=$object->id .'-'. $model->id?></th>
                                    <td><?=$model->from.' - '.$model->to?></td>
                                </tr>
                                <tr>
                                    <th>Категория номера:</th>
                                    <td><?=$catroom?></td>
                                </tr>
                                <tr>
                                    <th>Количество гостей:</th>
                                    <td><?=$model->adult_count?> взр.<?=($model->child_count)?', '.$model->child_count.' детей( '.$model->childs_ages.' лет)':''?></td>
                                </tr>
                                <tr>
                                    <th>Общая сумма за <?=$tarb?> суток</th>
                                    <td><?=$model->price?> руб.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th colspan="2">
                                        <?php if ($model->cancel == 2): ?>
                                            <span class="text-danger">Отменено владельцем</span>
                                        <?php elseif ($model->cancel == 1): ?>
                                            <span class="text-danger">Отменено клиентом</span>
                                        <?php endif; ?>
                                        <a href="/admin/booking/delete?id=<?=$model->id?>" class="pull-right" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>ФИ:</th>
                                    <td><?=$model->name?></td>
                                </tr>
                                <tr>
                                    <th>Тел:</th>
                                    <td><?=$model->phone?></td>
                                </tr>
                                <tr>
                                    <th>E-mail:</th>
                                    <td><?=$model->email?></td>
                                </tr>
                                <tr>
                                    <th>Комментариий к заказу:</th>
                                    <td><?=$model->comment?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
