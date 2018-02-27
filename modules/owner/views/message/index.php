<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Message;
use app\models\User;
use app\models\Object;
use app\models\Servis;
use app\models\Config;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Сообщения');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div id="exTab2" class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#1" data-toggle="tab">Все сообщения</a></li>
            <li><a href="#2" data-toggle="tab">Непрочитанные<?=(count($unread) != 0) ? " <span class='text-danger'>!</span>": ""?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="1">
                <div class="row">
                    <div class="col-lg-10">
                        <?php foreach ($dialogue as $dialog): ?>
                            <?php $all = Message::find()->where(['dialogue_id'=>$dialog->dialogue_id])->orderBy('id DESC')->all();
                                $object = Object::findOne(['id'=>$dialog->object_id]);
                                $servicetitle = "";
                                foreach (explode('-', Servis::findOne(['id'=>$object->service])->aliastwo) as $stitle) {
                                    $servicetitle .= $stitle . " ";
                                }
                                $locality = Config::findOne(['id'=>1])->title;
                                if ($all[0]->user_one==Yii::$app->user->getId()) {
                                    $user = User::findOne(['id'=>$all[0]->user_two]);
                                } else {
                                    $user = User::findOne(['id'=>$all[0]->user_one]);
                                }
                            ?>
                            <div class="item-container item-featured reviews-enabled">
                                <div class="content message">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="mess-image">
                                                <?php if (!is_null($user->avatar)): ?>
                                                    <img class="img-mes" src="<?=Yii::$app->request->baseUrl.'/'.$user->avatar?>">
                                                <?php else: ?>
                                                    <img class="img-mes" src="<?=Yii::$app->request->baseUrl?>/images/avatar-user.png">
                                                <?php endif; ?>
                                                <div class="MessageFlowItem-sender">
                                                    <h5><?=Html::a($object->full_title." ".$locality, ['/'.$object->alias], ['target'=>'_blank'])?></h5>
                                                    <h6 class="Message-date"><?=explode(' ', $all[0]->created_at)[0]?></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <p class="marg-t"><?=$all[0]->text?></p>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="item-data">
                                                <div class="item-body">
                                                    <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i>', ['delete', 'id' => $all[0]->dialogue_id], [
                                                        'class' => 'btn btn-danger remove-button pull-right',
                                                        'data' => [
                                                            'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот диалог?'),
                                                            'method' => 'post',
                                                        ],
                                                    ]) ?>
                                                    <div class="MessageFlowItem-options pull-right">
                                                        <?=Html::a('<span class="btn btn-warning">Перейти к диалогу</span>',  ['message/dialog/'.$all[0]->dialogue_id])?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="2">
                <div class="row">
                    <div class="col-lg-10">
                        <?php if (count($unread) != 0): ?>
                            <?php foreach ($unread as $unr): ?>
                                <?php
                                    $object = Object::findOne(['id'=>$unr->object_id]);
                                    $servicetitle = "";
                                    foreach (explode('-', Servis::findOne(['id'=>$object->service])->aliastwo) as $stitle) {
                                        $servicetitle .= $stitle . " ";
                                    }
                                    $locality = Config::findOne(['id'=>1])->title;
                                    $all = Message::find()->where(['dialogue_id'=>$unr->dialogue_id])->orderBy('id DESC')->all();
                                    if ($all[0]->user_one==Yii::$app->user->getId()) {
                                        $user = User::findOne(['id'=>$all[0]->user_two]);
                                    } else {
                                        $user = User::findOne(['id'=>$all[0]->user_one]);
                                    }
                                ?>
                                <div class="item-container item-featured reviews-enabled">
                                    <div class="content message">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <?php if ($user->avatar): ?>
                                                    <img class="img-mes" src="<?=Yii::$app->request->baseUrl.'/'.$user->avatar?>">
                                                <?php else: ?>
                                                    <img class="img-mes" src="<?=Yii::$app->request->baseUrl?>/images/avatar-user.png">
                                                <?php endif; ?>
                                                <div class="MessageFlowItem-sender">
                                                    <h5><?=Html::a($object->full_title." ".$locality, ['/'.$object->alias], ['target'=>'_blank'])?></h5>
                                                    <h6 class="Message-date"><?=explode(' ', $all[0]->created_at)[0]?></h6>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <p class="marg-t"><?=$all[0]->text?></p>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="item-data">
                                                    <div class="item-body">
                                                        <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i>', ['delete', 'id' => $all[0]->dialogue_id], [
                                                            'class' => 'btn btn-danger remove-button pull-right',
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот диалог?'),
                                                                'method' => 'post',
                                                            ],
                                                        ]) ?>
                                                        <div class="MessageFlowItem-options pull-right">
                                                            <?=Html::a('<span class="btn btn-warning">Перейти к диалогу</span>',  ['message/dialog/'.$all[0]->dialogue_id])?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
            </div>
        </div>
    </div>
</div>
