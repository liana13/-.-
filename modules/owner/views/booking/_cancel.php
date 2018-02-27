<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use app\models\User;
use app\models\Servis;
use app\models\Object;
use app\models\Image;
use app\models\Catroom;
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
            <div class="content bron">
                <label class="activity">
                    <p><b>№<?=$object->id .'-'. $model->id?></b> <?= Html::a('<span class="label label-rounded label-danger" data-toggle="tooltip" title="Распечатайте ваучер на заселение или сохраните на экран мобильного устройства. Предъявите его при заселении. Просьба сохранять ваучер до конца поездки.">Распечатать ваучер на заселение</span>', "javascript: w=window.open('../../default/vaucher/$model->id'); w.print();") ?></p>
                </label>
                <div class="row">
                    <div class="col-sm-offset-7 col-sm-5 action-group">
                        <div class="pull-left">
                            <?php Modal::begin([
                                'header' => '<h3>Написать письмо</h3>',
                                'size'=>'custom-modal',
                                'toggleButton' => ['label' => 'Написать письмо', 'class' => 'btn btn-modal-open btn-default'],
                            ]);?>
                            <?= $this->render('_message', ['object'=>$object->id, 'model'=>$model]) ?>
                            <?php Modal::end(); ?>
                        </div>
                        <?php if ($model->cancel == 2): ?>
                            <span class="btn btn-default" disabled="disabled">Отменено владельцем</span>
                        <?php else: ?>
                            <span class="btn btn-default" disabled="disabled">Отменено клиентом</span>
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
                                    <td><?=$model->adult_count?> взр.<?=$model->adult_count?><?=($model->child_count)?', '.$model->child_count.' детей( '.$model->childs_ages.' лет)':''?></td>
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
