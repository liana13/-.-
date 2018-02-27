<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use app\models\User;
use app\models\Servis;
use app\models\Object;
use app\models\Catroom;
use app\models\Image;
/* @var $this yii\web\View */
/* @var $model app\models\Ads */

$catroom = Catroom::findOne(['id'=>$model->catroom_id])->title;
$user =  User::findOne(['id'=>$model->user_id]);
$object = Object::findOne(['id'=>$model->object_id]);
$img = Image::findOne(['object_id'=>$model->object_id]);

$datediff = strtotime($model->to) - strtotime($model->from);
$tarb = floor($datediff / (60 * 60 * 24));
?>
<div class="row">
    <div class="col-lg-10">
        <div class="item-container item-featured reviews-enabled">
            <div class="content bron<?=($model->status == 0)?' incomplete':''?>">
                <label class="activity">
                    <p><b>№<?=$object->id .'-'. $model->id?></b> <?= Html::a('<span class="label label-rounded label-danger" data-toggle="tooltip" title="Распечатайте ваучер на заселение или сохраните на экран мобильного устройства. Предъявите его при заселении. Просьба сохранять ваучер до конца поездки.">Распечатать ваучер на заселение</span>', "javascript: w=window.open('../../default/vaucher/$model->id'); w.print();") ?></p>
                </label>
                <div class="row">
                    <div class="col-sm-offset-7 col-sm-5 action-group">
                        <div class="pull-left">
                            <?=Html::a('<span class="btn btn-default">Отменить</span>',  ['/owner/booking/cancelbron/'.$model->id], ["data-confirm"=>"Вы действительно хотите отменить бронь?", "data-method"=>"post"])?>
                        </div>
                        <div class="pull-left">
                            <?php Modal::begin([
                                'header' => '<h3>Написать письмо</h3>',
                                'size'=>'custom-modal',
                                'toggleButton' => ['label' => 'Написать письмо', 'class' => 'btn btn-modal-open btn-default'],
                            ]);?>
                            <?= $this->render('_message', ['object'=>$object->id, 'model'=>$model]) ?>
                            <?php Modal::end(); ?>
                        </div>
                        <?php $datediff = strtotime(date("Y-m-d")) - strtotime($model->from) ?>
                        <?php if ($model->status == 1 && floor($datediff / (60 * 60 * 24))<=1): ?>
                            <?=Html::a('<span class="btn btn-default">Незаезд</span>',  ['/owner/booking/incomplete/'.$model->id], ['data-toggle'=>"tooltip", 'title'=>"Отмечайте незаезд не позднее окончания даты заезда."])?>
                        <?php elseif($model->status == 0): ?>
                            <span class="btn btn-danger" disabled="disabled">Незаезд</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <table class="table table-striped">
                            <tbody>
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
