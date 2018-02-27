<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Persontype;
use app\models\User;
use yii\helpers\ArrayHelper;
use borales\extensions\phoneInput\PhoneInput;

$user = User::findOne(['id'=>Yii::$app->user->getId()]);
?>

<div class="person-form shadow-out">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['value'=>$user->id])->label(false);?>
    <?= $form->field($model, 'login')->hiddenInput(['value'=>$user->username])->label(false);?>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <?php  $list = ['1'=> 'Для юридического лица', '2' => 'Для индивидуального предпринимателя', '3'=>"Для физического лица"]; ?>
            <?= $form->field($model, 'type')->dropDownList($list, ['prompt' => 'Выберите тип', 'onchange'=>"displayrow($(this).val())", 'id'=>"persontype"])->label(false);?>
        </div>
        <div id="display-type-1" class="displaynone col-sm-12">
            <h1 id="h1person"></h1>
            <div class="col-sm-6 onlyone">
                <?= $form->field($model, 'name_org_1')->textInput()->label('Наименование организации*',['data-toggle'=>'tooltip', 'title'=>'Обязательно для заполнения. Используется для идентификации платежа и автоматического заполнения документов']) ?>
            </div>
            <div id="two_and_three" class="displaynone">
                <div class="col-sm-6">
                    <?= $form->field($model, 'fio')->textInput()->label('ФИО*',['data-toggle'=>'tooltip', 'title'=>'Обязательно для заполнения. Используется для идентификации платежа и автоматического заполнения документов']) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'address')->textInput()->label('Адрес местожительства*',['data-toggle'=>'tooltip', 'title'=>'Обязательно для заполнения. Используется для идентификации платежа и автоматического заполнения документов']) ?>
                </div>
            </div>
                <div class="col-sm-6 one_and_two displaynone">
                    <?= $form->field($model, 'inn')->textInput()->label('ИНН*',['data-toggle'=>'tooltip', 'title'=>'Обязательно для заполнения. Используется для идентификации платежа']) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'phone')->textInput()->label('Телефон',['data-toggle'=>'tooltip', 'title'=>'Телефон для связи с нами']);?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'email')->textInput(['placeholder' => "Эл. почта"])->widget(\yii\widgets\MaskedInput::className(), [
                        'clientOptions' => [
                        'alias' =>  'email',
                        ],
                        ])->label('Эл. почта', ['data-toggle'=>'tooltip', 'title'=>'E-mail для связи с нами']); ?>
               </div>
               <div class="text-center">
                   <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-common']) ?>
               </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
function displayrow(x) {
    if (!x) {
        $("#display-type-1").hide();
    } else {
        $("#display-type-1").show();
    }
    if (x==1) {
        $("#h1person").html("Для юридического лица");
        $(".one_and_two").show();
        $("#two_and_three").hide();
        $(".onlyone").show();
        $("#two_and_three .form-control").each(function(){
            $(this).val("");
        });
    } else if (x==2) {
        $("#h1person").html("Для индивидуального предпринимателя");
        $("#two_and_three").show();
        $(".one_and_two").show();
        $(".onlyone").hide();
        $(".onlyone .form-control").each(function(){
            $(this).val("");
        });
    }else if (x==3) {
        $("#h1person").html("Для физического лица");
        $(".one_and_two").hide();
        $("#two_and_three").show();
        $(".onlyone").hide();
        $(".onlyone .form-control").each(function(){
            $(this).val("");
        });
        $(".one_and_two .form-control").each(function(){
            $(this).val("");
        });
    }
}
</script>
