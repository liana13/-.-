<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use app\models\LoginForm;
use app\models\User;
use app\models\PasswordResetRequestForm;
use app\models\Regform;
use app\models\Regformadmin;
use app\models\Object;
use app\models\Booking;
use app\models\Finance;
use app\models\Tarif;
use app\models\Coefficient;
use app\models\Config;

AppAsset::register($this);
$login = new LoginForm;
$modelreset = new PasswordResetRequestForm;
$registerform = new Regform;
$registeradminform = new Regformadmin;

if ($logo = Config::findOne(['id'=>1])->logo) {
    $brand = Html::img(Yii::$app->request->baseUrl."/".$logo);
} else {
    $brand = "<span class='brandname text-primary'>".Config::findOne(['id'=>1])->title.".рф</span>";
}
if (!Yii::$app->user->isGuest) {
    $type = User::findOne(['id'=>Yii::$app->user->getId()])->type;
    if ($type == 1) {
        $profile = '/admin';
    } elseif ($type == 2) {
        $profile = '/cabinet';
    } elseif ($type == 3) {
        $profile = '/owner';
    }
}

$financeonlines = Object::find()->where(['tarif_id'=> 4])->all();
foreach ($financeonlines as $financeonline) {
    $active_online = $financeonline->active_online;
    $datediff = strtotime(date('Y-m-d')) - strtotime($active_online);
    $tarb = floor($datediff / (60 * 60 * 24));
    $coef_interval = Coefficient::find()->where(['object_id'=>$financeonline->id])->orderBy('id DESC')->one();
    if (!$coef_interval && $tarb==30 || $coef_interval && $tarb >= $coef_interval->interval) {
        $bookings = Booking::find()->where(['object_id'=>$financeonline->id])->andWhere(['>=', 'from', $active_online])
                ->andWhere(['<', 'from', date('Y-m-d')])->andWhere(['cancel'=>0])->andWhere(['status'=>1])->all();
        $pricebook = 0;
        if (count($bookings)!=0) {
            foreach ($bookings as $booking) {
                $coefficient = 0.1;
                $coef = Coefficient::find()->where(['object_id'=>$financeonline->id])->andWhere(['<=','datefrom',$booking->from])->orderBy('datefrom DESC')->one();
                if ($coef) {
                    $coefficient = $coef->percent/100;
                }
                $pricebook+=$booking->price*$coefficient;
            }
            if ($pricebook!=0) {
                if (!Finance::find()->where(['object_id'=>$financeonline->id])->andWhere(['updated_at'=>date('Y-m-d')])->one()) {
                    $financeon = new Finance();
                    $financeon->object_id = $financeonline->id;
                    $financeon->tarif_id = $financeonline->tarif_id;
                    $financeon->user_id = $financeonline->user_id;
                    $financeon->price = $pricebook.'руб.';
                    $financeon->status = 0;
                    if ($financeon->save()) {
                        $useremail = User::findOne(['id'=>$financeon->user_id])->email;
                        $url = Yii::$app->urlManager->createAbsoluteUrl(['/update/finance/'.$financeon->object_id]);
                        Yii::$app->mailer->compose()
                            ->setTo($useremail)
                            ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                            ->setSubject('Счет на оплату в ' ." " .Yii::$app->name)
                            ->setHtmlBody('Здравствуйте. Вам выставлен счет за услуги портала '.Yii::$app->name.' на сумму '.$financeon->price.'. <br>Вы можете распечатать платежные документы для его оплаты в личном аккаунте в разделе
                            <a href="'.$url.'">«Финансы» </a>. Если Вам удобно произвести оплату другим способом (на номер карты Сбербанка, Киви кошелек, Яндекс кошелек), то сообщите нам на электронную почту admin@tvoyray.ru или по тел. +7-918-16-19-300.')
                            ->send();
                    }
                }
            }
        }
        $financeonline->active_online = date('Y-m-d');
        $financeonline->save();
    }
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="61cd9e6db2323c22" />
    <link rel="shortcut icon" href="<?=Yii::$app->request->baseUrl?>/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => $brand,
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
    <div class="curency-changer">
        <iframe src='http://rossbanki.ru/get_converter.php?style=light&bg=ffffff&sb=eeeeee&cb=eeeeee&fc=5d5d5d&font=helvetika&c_from=USD&c_to=RUB&width=420px&height=180px&orient=horizontal&sizetype=fixed&' style='width:430px; height:190px; border:none; '></iframe>
    </div>
    <?php
    $menuitems = [
        ['label' => 'Translate google', 'url' => ['#'],'options'=>['class'=>'displaye-google']],
        ['label' => 'Курсы валют', 'url' => ['#'],'options'=>['class'=>'displaye-changer']]
    ];
    if (Yii::$app->user->isGuest) {
        $menuitems []= ['label' => 'Регистрация', 'url' => ['/site/register'],'options'=>['class'=>'registration-btn btn-head']];
        $menuitems []= ['label' => 'Вход', 'url' => ['/site/login'],'options'=>['class'=>'login-btn btn-head']];
    } else {
        $menuitems []= ['label' => 'Личный кабинет', 'url' => [$profile],'options'=>['class'=>'btn-head cabinet-btn']];
        $menuitems []= ['label' => 'Выход', 'url' => ['/site/logout'],'options'=>['class'=>'btn-head'], 'linkOptions'=>['data-method'=>'post']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $menuitems,
    ]); ?>
    <div class="search-input">
        <input type="search" class="form-control" placeholder="Поиск..." name="search-term" id="search-term" onchange="functsearch($(this).val())">
        <i class="fa fa-search" aria-hidden="true" onclick="functsearch($('#search-term').val())"></i>
    </div>
    <?php NavBar::end();?>
    <div class="container">
        <?= $content ?>
    </div>
</div>
<footer class="footer text-center footertop">
    <div class="container">
        <p><?=Yii::$app->name?> все права защищены &copy; 2017-<?=date('Y')?></p>
    </div>
</footer>
<div class="to-top btn btn-common">
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
</div>
<?php if (Yii::$app->session->hasFlash('successreg')){
    Modal::begin([
        'id'=>'notify-modal',
        'size'=>'modal-sm',
        'header' => '<h2 class="modal-title text-center">Уведомление</h2>',
    ]);?>
    <div id="not-content">
        <p class="text-center"><?=Yii::$app->session->getFlash('successreg')?></p>
        <p class="text-center"> Перейдите в ваш почтовый ящик, чтобы активировать ваш аккаунт. </p>
        <?= Html::button('Ok',['class'=>'btn-close btn btn-login btn-common', "data-dismiss"=>"modal", "aria-hidden"=>true])?>
    </div>
    <?php
    Modal::end();
    $jsnotify = "$('#notify-modal').modal('show');$('#notify-modal').find('#not-content').show();";
    $this->registerJs($jsnotify);
}

if (Yii::$app->session->hasFlash('alertmessage')){
    Modal::begin([
        'id'=>'notify-mess-modal',
        'size'=>'modal-sm',
        'header' => '<h2 class="modal-title text-center">Уведомление</h2>',
    ]);?>
    <div id="mess-content">
        <p class="text-center">Здравствуйте, Вам поступило сообщение от администратора объекта размещения. Чтобы увидеть сообщение и ответить на него пожалуйста, перейдите в свой личный кабинет на портале <?=Yii::$app->name?> в раздел «Сообщения»</p>
        <?= Html::button('Ok',['class'=>'btn-close btn btn-login btn-common', "data-dismiss"=>"modal", "aria-hidden"=>true])?>
    </div>
    <?php
    Modal::end();
    $jsnotify = "$('#notify-mess-modal').modal('show');$('#notify-mess-modal').find('#mess-content').show();";
    $this->registerJs($jsnotify);
}

if (Yii::$app->session->hasFlash('notify')){
    Modal::begin([
        'id'=>'notify1-modal',
        'size'=>'modal-sm',
        'header' => '<h2 class="modal-title text-center">Уведомление</h2>',
    ]);?>
    <div id="not1-content">
        <p class="text-center"><?=Yii::$app->session->getFlash('notify')?></p>
        <?= Html::button('Ok',['class'=>'btn-close btn btn-login btn-common', "data-dismiss"=>"modal", "aria-hidden"=>true])?>
    </div>
    <?php
    Modal::end();
    $jsnotify = "$('#notify1-modal').modal('show');$('#notify1-modal').find('#not1-content').show();";
    $this->registerJs($jsnotify);
}
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
        <?= $form->field($registerform, 'username')->textInput(['placeholder' => "Введите ник ..."])->label("Обратите внимание, что данный ник будет использоваться для: Входа в аккаунт; Написании Вами отзывов; Переписке с администраторами объектов. Т.е. это название (Ник) будут видеть и другие пользователи (при чтении Ваших отзывов) и администраторы объектов (при обмене сообщениями)") ?>
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
            <?= Html::button('Пользователь портала ', ['class'=>'open-regform btn btn-login btn-common'])?>
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
<?php $this->endBody() ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter46927350 = new Ya.Metrika({
                    id:46927350,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/46927350" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>
<?php $this->endPage() ?>
<script type="text/javascript">
function functsearch(x){
    window.location.href = "<?=Yii::$app->request->baseUrl?>/site/objects?ObjectsallSearch%5Bsearch%5D="+x;
}
</script>
