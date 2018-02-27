<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\models\User;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use app\models\PasswordResetRequestForm;
use app\models\Regform;
use app\models\Regformadmin;
use app\models\LoginForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
$login = new LoginForm;
$modelreset = new PasswordResetRequestForm;
$registerform = new Regform;
$registeradminform = new Regformadmin;
AppAsset::register($this);
Modal::begin([
    'id'=>'regform-modal',
    'size'=>'custom-modal',
    'header' => '<h3 class="modal-title text-center">Регистрация как пользователь портала '.Yii::$app->name.'</h3>',
]);?>
<div id="regform-content">
    <?php $form = ActiveForm::begin([
        'id'=>'regform',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'action'=>['/site/registration'],
    ]); ?>

        <?= $form->field($registerform, 'username')->textInput(['placeholder' => "Введите ник ..."])->label("Этот ник будет виден всем при написании Вами отзывов") ?>

        <?= $form->field($registerform, 'email')->textInput(['placeholder' => "Введите эл. почту ..."])->label('Введите название электронной почты, на которую Вам придет ссылка для подтверждения регистрации') ?>

        <?= $form->field($registerform, 'newpasswordadmin')->passwordInput(['placeholder' => "Введите пароль ..."])->label("Введите пароль, это может быть сочетание букв, цифр, или букв и цифр") ?>

        <?= $form->field($registerform, 'passwordconfirmadmin')->passwordInput(['placeholder' => "Повторите пароль ..."])->label(false) ?>

        <?= $form->field($registerform, 'type')->hiddenInput(['value' => 2])->label(false) ?>

        <div class="form-group text-center">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-login btn-common', 'name' => 'reg-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::end();
Modal::begin([
    'id'=>'regformadmin-modal',
    'size'=>'custom-modal',
    'header' => '<h3 class="modal-title text-center">Регистрация как aдминистратор объекта</h3>',
]);?>
<div id="regadminform-content">
    <?php $form = ActiveForm::begin([
        'id'=>'regadminform',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'action'=>['/site/registrationadmin'],
    ]); ?>
        <?= $form->field($registeradminform, 'username')->textInput(['placeholder' => "Введите логин ..."])->label("Придумайте уникальный логин, это может быть сочетание букв, цифр, или букв и цифр") ?>
        <?= $form->field($registeradminform, 'email')->textInput(['placeholder' => "Введите эл. почту ..."])->label('Введите название электронной почты, на которую Вам придет ссылка для подтверждения регистрации') ?>
        <?= $form->field($registeradminform, 'newpassword')->passwordInput(['placeholder' => "Введите пароль ..."])->label("Введите пароль, это может быть сочетание букв, цифр, или букв и цифр") ?>
        <?= $form->field($registeradminform, 'passwordconfirm')->passwordInput(['placeholder' => "Повторите пароль ..."])->label(false) ?>
        <?= $form->field($registeradminform, 'type')->hiddenInput(['value' => 3])->label(false) ?>
        <div class="form-group text-center">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-login btn-common', 'name' => 'reg-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::end();
Modal::begin([
    'id'=>'checkreg-modal',
    'size'=>'custom-modal',
    'header' => '<h3 class="modal-title text-center">Выберите как Вы хотите зарегистрироваться</h3>',
]);?>
<div id="checkreg-content">
    <div class="row">
        <div class="col-sm-6">
            <?= Html::button('Пользователь портала '.Yii::$app->name, ['class'=>'open-regform btn btn-login btn-common'])?>
            <p>(Для бронирования объектов, переписки с администрацией отелей, возможности оставить отзыв об объекте в котором Вы отдыхали).</p>
        </div>
        <div class="col-sm-6">
            <?= Html::button('Администратор объекта',['class'=>'open-regadminform btn btn-login btn-common'])?>
            <p>(Для размещения информации о своем объекте на портале <?=Yii::$app->name?>).</p>
        </div>
    </div>
</div>
<?php
Modal::end();
Modal::begin([
    'id'=>'login-modal',
    'size'=>'modal-sm',
    'header' => '<h4 class="modal-title">Вход на сайт</h4>',
]);?>
<div id="loginn-content">
    <?php $form = ActiveForm::begin([
        'id'=>'logintocab',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'action'=>['/site/login'],
    ]); ?>
        <?= $form->field($login, 'email')->textInput(['placeholder' => "Введите логин ..."])->label(false) ?>
        <?= $form->field($login, 'password')->passwordInput(['placeholder' => "Введите пароль ..."])->label(false) ?>
        <?= Html::a('Забыли пароль?',['/site/request-password-reset'], ['class'=>'a-href reset-pass'])?>
        <?= Html::a('Ещё нет учетной записи?',['/site/registration-btn'], ['class'=>'a-href go-reg'])?>
        <div class="form-group text-center">
            <?= Html::submitButton('Вход', ['class' => 'btn btn-login btn-common', 'name' => 'login-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
    Modal::end();
    Modal::begin([
        'id'=>'reset-modal',
        'size'=>'modal-sm',
        'header' => '<h4 class="modal-title">Восстановить пароль</h4>',
    ]);?>
    <div id="reset-content">
        <?php $form = ActiveForm::begin([
            'id'=>'resetpassword',
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
            'action'=>['/site/request-password-reset'],
        ]); ?>
        <?= $form->field($modelreset, 'email')->textInput(['autofocus' => true]) ?>
        <div class="form-group text-center">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-login btn-common', 'name' => 'login-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php
    Modal::end();
?>
<div class="site-login">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img(Yii::$app->request->baseUrl."/images/logo/ray.jpg"),
        'brandUrl' => "/",
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);?>
    <div class="google-translate">
        <div id="google_translate_element"></div><script type="text/javascript">
        function googleTranslateElementInit() {
          new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }
        </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </div>
    <?php
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Translate google', 'url' => ['/site/index'],'options'=>['class'=>'displaye-google']],
            ['label' => 'Регистрация', 'url' => ['/site/register'],'options'=>['class'=>'registration-btn btn-head']],
            ['label' => 'Вход', 'url' => ['/site/login'],'options'=>['class'=>'login-btn btn-head']],
        ],
    ]);
    ?>
    <div class="search-input">
        <input type="search" class="form-control" placeholder="Поиск..." name="search-term" id="search-term" onchange="functsearch($(this).val())">
        <i class="fa fa-search" aria-hidden="true" onclick="functsearch($('#search-term').val())"></i>
    </div>
    <?php NavBar::end();?>
    <div class="container wrap">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <h1><?= Html::encode($this->title) ?></h1>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
            ]); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= Html::a('Забыли пароль?',['/site/request-password-reset'], ['class'=>'a-href reset-pass'])?>
                <?= Html::a('Ещё нет учетной записи?',['/site/registration-btn'], ['class'=>'a-href go-reg'])?>

                <div class="col-sm-6 col-sm-offset-3">
                    <?= Html::submitButton('Вход', ['class' => 'btn btn-login btn-common', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<footer class="footer text-center footertop">
    <div class="container">
        <p><?=Yii::$app->name?> все права защищены &copy; 2017-<?=date('Y')?></p>
    </div>
</footer>
</div>
