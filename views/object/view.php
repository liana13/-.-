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
use yii\bootstrap\Modal;
use app\models\Country;
use app\models\Locality;
use app\models\Region;
use app\models\Field;
use app\models\Servis;
use app\models\CatroomSearch;
use app\models\Config;

$locality_title = Config::findOne(['id'=>1])->title;
$fields = Field::find()->where(['!=', 'class', ""])->orderBy('sort asc')->all();
$reviews = Review::find()->where(['object_id'=>$model->id, 'status'=>1])->all();
if (Person::findOne(['id'=>$model->user_id])) {
    $phone=Person::findOne(['id'=>$model->user_id])->phone;
}
$images=Image::find()->where(['object_id'=>$model->id])->orderBy('id')->all();
if (Person::findOne(['user_id'=>$model->user_id])) {
    $mails = $email =Person::findOne(['user_id'=>$model->user_id])->email;
}
if (User::findOne(['id'=>$model->user_id])) {
    $emailuser =User::findOne(['id'=>$model->user_id])->email;
}
?>
<?php if (Yii::$app->session->hasFlash('contact')){
    Modal::begin([
        'id'=>'notify-modal',
        'size'=>'modal-sm',
        'header' => '<h4 class="modal-title">Уведомление</h4>',
    ]);
    echo '<div class="text-center" id="notify-content">
        <p class="light">'.Yii::$app->session->getFlash('contact').'</p><button type="button" class="btn-close btn btn-common" data-dismiss="modal" aria-hidden="true">OK</button></div>';
    Modal::end();
    $jsnotify = "$('#notify-modal').modal('show');";
    $this->registerJs($jsnotify);
}?>
<?php $this->title = $model->full_title." ".$locality_title; ?>
<?php if ($model->active == 1 || !Yii::$app->user->isGuest && $model->user_id == Yii::$app->user->getId() || !Yii::$app->user->isGuest && User::findOne(['id'=>Yii::$app->user->getId()])->type==1): ?>
    <?php if ($model->tarif_id == 0): ?>
        <?= $this->render('_viewfree', ['model' => $model]); ?>
    <?php else: ?>
        <?= $this->render('_viewtarif', ['model' => $model, 'dataProvider'=>$dataProvider]); ?>
    <?php endif; ?>
<?php else: ?>
    <p class="text-danger">Объект не активен.</p>
<?php endif; ?>
