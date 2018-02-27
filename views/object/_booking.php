<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Booking;
use app\models\User;
use app\models\Price;
use app\models\Curency;
use app\models\Calendar;
use yii\db\Query;

$booking = new Booking();
$from = Yii::$app->request->get('filter')['from'];
$to = Yii::$app->request->get('filter')['to'];
$user = User::findOne(['id'=>Yii::$app->user->getId()]);

$ages = "";
if (Yii::$app->request->get('filter')['child'] == 4) {
    $ages .= Yii::$app->request->get('filter')['age_1'].",".Yii::$app->request->get('filter')['age_2'].",".Yii::$app->request->get('filter')['age_3'].",".Yii::$app->request->get('filter')['age_4'];
} elseif (Yii::$app->request->get('filter')['child'] == 3) {
    $ages .= Yii::$app->request->get('filter')['age_1'].",".Yii::$app->request->get('filter')['age_2'].",".Yii::$app->request->get('filter')['age_3'];
} elseif (Yii::$app->request->get('filter')['child'] == 2) {
    $ages .= Yii::$app->request->get('filter')['age_1'].",".Yii::$app->request->get('filter')['age_2'];
} elseif (Yii::$app->request->get('filter')['child'] == 1) {
    $ages .= Yii::$app->request->get('filter')['age_1'];
}
?>
<div class="booking-form">
    <?php $form = ActiveForm::begin([
        'action'=>['/booking/create']
    ]); ?>
    <?= $form->field($booking, 'user_id')->hiddenInput(['value'=>$user->id])->label(false) ?>
    <?= $form->field($booking, 'object_id')->hiddenInput(['value'=>$model])->label(false) ?>
    <?= $form->field($booking, 'catroom_id')->hiddenInput(['value'=>$catroom->id])->label(false) ?>
    <?= $form->field($booking, 'adult_count')->hiddenInput(['value'=>Yii::$app->request->get('filter')['adult']])->label(false) ?>
    <?= $form->field($booking, 'child_count')->hiddenInput(['value'=>Yii::$app->request->get('filter')['child']])->label(false) ?>
    <?= $form->field($booking, 'childs_ages')->hiddenInput(['value'=>$ages])->label(false) ?>
    <?= $form->field($booking, 'from')->hiddenInput(['value'=>Yii::$app->request->get('filter')['from']])->label(false) ?>
    <?= $form->field($booking, 'to')->hiddenInput(['value'=>Yii::$app->request->get('filter')['to']])->label(false) ?>
    <?= $form->field($booking, 'price')->hiddenInput(['value'=>$money])->label(false) ?>
    <div class="row">
       <div class="col-sm-6">
           <?= $form->field($booking, 'surname')->textInput(['value'=>($user->name)?explode(' ',$user->name)[1]:''])->label("*Фамилия") ?>
       </div>
       <div class="col-sm-6">
           <?= $form->field($booking, 'username')->textInput(['value'=>($user->name)?explode(' ',$user->name)[0]:''])->label("*Имя") ?>
       </div>
   </div>
   <div class="row">
       <div class="col-sm-6">
           <?= $form->field($booking, 'phone')->textInput(['value'=>($user->phone)?$user->phone:''])->label("*Телефон") ?>
       </div>
       <div class="col-sm-6">
           <?= $form->field($booking, 'email')->textInput(['value'=>($user->email)?$user->email:''])->label("*E-mail") ?>
       </div>
   </div>
    <div class="row">
    <hr>
        <div class="value-default">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Количество человек:</label>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label><?=Yii::$app->request->get('filter')['adult']?> взрослых<?=(Yii::$app->request->get('filter')['child'])?', '.Yii::$app->request->get('filter')['child'].' детей ('.$ages.' лет)':''?></label>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Даты заезда:</label>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>с <?=Yii::$app->request->get('filter')['from']?> по <?=Yii::$app->request->get('filter')['to']?></label>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Сумма за все дни:</label>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label><?=$money?> руб.</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <hr>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($booking, 'comment')->textarea()->label('Комментарий к заказу', ['data-toggle'=>'tooltip', 'title'=>'Напишите Ваши комментарии к заказу']) ?>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-sm-offset-3 col-sm-6">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Бронировать'), ['class' => 'btn btn-common']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
