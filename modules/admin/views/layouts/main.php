<?php
use yii\helpers\Html;
use app\models\User;
use app\models\Object;
use app\models\Booking;
use app\models\Finance;
use app\models\Tarif;
use app\models\Coefficient;

/* @var $this \yii\web\View */
/* @var $content string */

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppAdminAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
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
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>

    <?= $this->render(
        'left.php',
        ['directoryAsset' => $directoryAsset]
    )
    ?>

    <?= $this->render(
        'content.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
