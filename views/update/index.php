<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Image;
use app\models\Person;
use app\models\Review;
use app\models\User;
use app\models\Properties;
use mirocow\yandexmaps\Canvas as YandexCanvas;
use mirocow\yandexmaps\Map as YandexMap;
use mirocow\yandexmaps\objects\Placemark as Placemark;
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use yii\bootstrap\Modal;
use app\models\Country;
use app\models\Locality;
use app\models\Region;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Object;
use app\models\Field;
use app\models\Servis;

/* @var $this \yii\web\View */
/* @var $content string */

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppOwnerobjectAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$servicetitle = "";
foreach (explode('-', Servis::findOne(['id'=>$model->service])->aliastwo) as $stitle) {
    $servicetitle .= $stitle . " ";
}
$this->title = 'Редактирование - '.$servicetitle.$model->title;
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
<body class="hold-transition skin-blue skin-cabinet sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">
    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>
    <?= $this->render(
        '_left.php',
        ['object' => $model]
    ) ?>
    <div class="content-wrapper">
        <section class="content">
            <?= Alert::widget() ?>
            <?php if ($model->tarif_id == 0 && $model->new_tarif == 0): ?>
                <?= $this->render('_updatefree', ['model' => $model]); ?>
            <?php else: ?>
                <?= $this->render('_updatetarif', ['model' => $model]); ?>
            <?php endif; ?>
        </section>
    </div>

    <footer class="main-footer">
        <p class="text-center">TvoyRay.ru 2007-2012. <?=Yii::$app->name?> все права защищены &copy; 2012-<?=date('Y')?></p>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
