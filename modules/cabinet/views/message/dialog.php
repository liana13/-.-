<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\ActiveForm;
use app\models\Message;
use app\models\User;
use app\models\Object;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new Message;

if ($messages[0]->user_one == Yii::$app->user->getId()) {
    $collocutor = User::findOne(['id'=>$messages[0]->user_two]);
} elseif ($messages[0]->user_two == Yii::$app->user->getId()) {
    $collocutor = User::findOne(['id'=>$messages[0]->user_one]);
}
$object = Object::findOne(['id'=>$messages[0]->object_id]);

$this->title = "Переписка с администратором объекта ".$object->full_title;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="shadow-out">
        <?php foreach ($messages as $message): ?>
            <?php $user = User::findOne(['id'=>$message->user_one]); ?>
            <?php if ($message->user_two == Yii::$app->user->getId()): ?>
                <div class="event-msg event-msg-right">
                    <div class="event-info">
                        <span class="event-name"><?=$object->full_title?></span>
                        <div class="event-sent hidden-xs">
                            <small>Отправлено: <?=explode(" ", $message->created_at)[0]?></small>
                        </div>
                    </div>
                    <?php if ($user->avatar): ?>
                        <img class="img-circle event-img" src="<?=Yii::$app->request->baseUrl.'/'.$user->avatar?>">
                    <?php else: ?>
                        <img class="img-circle event-img" src="<?=Yii::$app->request->baseUrl?>/images/avatar-user.png">
                    <?php endif; ?>
                    <div class="event-text">
                        <p><?=$message->text?></p>
                    </div>
                </div>
            <?php elseif($message->user_one == Yii::$app->user->getId()): ?>
                <div class="event-msg">
                    <div class="event-info">
                        <span class="event-name"><?=$user->username?></span>
                        <div class="event-sent hidden-xs">
                            <small>Отправлено: <?=explode(" ", $message->created_at)[0]?></small>
                        </div>
                        <div class="event-read">
                            <?php if ($message->status == 1): ?>
                                <small>Просмотрено: <span class="hidden-xs"><?=explode(" ", $message->updated_at)[0]?></span></small>
                            <?php else: ?>
                                <small>не просмотрено</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($user->avatar): ?>
                        <img class="img-circle event-img" src="<?=Yii::$app->request->baseUrl.'/'.$user->avatar?>">
                    <?php else: ?>
                        <img class="img-circle event-img" src="<?=Yii::$app->request->baseUrl?>/images/avatar-user.png">
                    <?php endif; ?>
                    <div class="event-text">
                        <p><?=$message->text?></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php $form = ActiveForm::begin(['id' => 'message-form', 'action' => ['/cabinet/message/create']]); ?>
            <?= $form->field($model, 'text')->textarea(['rows' => 4, 'placeholder'=>'Сообщение'])->label(false) ?>
            <?= $form->field($model, 'user_one')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false) ?>
            <?= $form->field($model, 'user_two')->hiddenInput(['value' => $collocutor->id])->label(false) ?>
            <?= $form->field($model, 'dialogue_id')->hiddenInput(['value' => $messages[0]->dialogue_id])->label(false) ?>
            <?= $form->field($model, 'object_id')->hiddenInput(['value' => $messages[0]->object_id])->label(false) ?>
            <?= $form->field($model, 'status')->hiddenInput(['value' => 0])->label(false) ?>
            <div class="form-group col-sm-12 text-right">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-warning', 'name' => 'contact-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
