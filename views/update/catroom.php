<?php
use yii\helpers\Html;
use app\models\Image;
use app\models\Person;
use app\models\Review;
use app\models\User;
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Object;
use app\models\Catroom;
use app\models\Field;

$catrooms = Catroom::find()->where(['object_id'=> $model->id])->all();
if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppOwnerobjectAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$this->title = Yii::t('app', 'Категории номеров');
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'id'=>'delete-modal',
    'size'=>'modal-sm',
    'header' => '<h3 class="modal-title text-center">Уведомление</h3>',
]);?>
<div id="delete-content">
    <?php $form = ActiveForm::begin([
        'id'=>'deleteform',
        'action'=>['/update/deletecatroom/'.$model->id],
    ]); ?>
        <?= $form->field($model, 'deletecatroom')->hiddenInput(['id'=>'hiddenimg'])->label(false) ?>
        <h3 class="text-center">Вы уверены,что хотите удалить?</h3>
        <div class="form-group text-center">
            <?= Html::submitButton('ДА', ['class' => 'btn btn-common', 'name' => 'delete-button']) ?>
            <?= Html::button('НЕТ', ['class' => 'btn btn-common', 'onclick' => 'deletebutton()']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Modal::end();?>
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
        <section class="content catroom-index">
            <?= Alert::widget() ?>
            <h1><?= Html::encode($this->title) ?></h1>
            <p>
                <?= Html::a(Yii::t('app', 'Добавить категорию'), ['update/catroomcreate/'.$model->id], ['class' => 'btn btn-common']) ?>
            </p>
            <div class="row">
                <?php foreach ($catrooms as $catroom): ?>
                    <div class="list-view col-sm-4">
                        <div class="result-item s-list text-center">
                            <div class="img-div">
                                <?php if (explode(",", $catroom->photo)[0]): ?>
                                    <?= Html::a(Html::img(Yii::$app->request->baseUrl."/upload/catroom/".explode(",", $catroom->photo)[0]),['update/catroomupdate/'.$catroom->id])?><i class="fa fa-trash text-danger" aria-hidden="true" onclick="alertfunction(<?=$catroom->id?>)" ></i>
                                    <?php else: ?>
                                    <?= Html::a(Html::img(Yii::$app->request->baseUrl."/upload/images/default/default.jpg"),['update/catroomupdate/'.$catroom->id])?><i class="fa fa-trash text-danger" aria-hidden="true" onclick="alertfunction(<?=$catroom->id?>)" ></i>
                                <?php endif; ?>
                            </div>
                            <div class="desc-div">
                                <br>
                                <?php if (Catroom::findOne(['id'=>$catroom->id])->status == 1): ?>
                                    <?=Html::a(
                                        '<i class="fa fa-ban" aria-hidden="true"></i> Отключить',
                                        Yii::$app->request->baseUrl.'/update/changestatus/'.$catroom->id,[
                                            'title' => 'Отключить', 'class'=>'text-warning'
                                        ]
                                    );?>
                                <?php else: ?>
                                    <?=Html::a(
                                        '<i class="fa fa-check" aria-hidden="true"></i> Включить',
                                        Yii::$app->request->baseUrl.'/update/changestatus/'.$catroom->id,[
                                            'title' => 'Включить'
                                        ]
                                    );?>
                                <?php endif; ?>
                                <h3 class="title">
                                    <?=$catroom->title?>
                                </h3>
                                <p><?=substr($catroom->description,0,30)?>...</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
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
<script type="text/javascript">
function alertfunction(x) {
    $("#hiddenimg").val(x);
    $('#delete-modal').modal('show');
}
function deletebutton() {
    $('#delete-modal').modal('hide');
}
</script>
