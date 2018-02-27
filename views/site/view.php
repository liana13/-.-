<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
;
use app\models\Object;
use app\models\Image;
use app\models\Config;
use app\models\Region;
use app\models\Locality;
use app\models\Servis;
use app\models\Rp;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
$url = Yii::$app->request->get('url');
$config = Config::findOne(['id'=>1]);
?>
<?php if ($model->keyword): ?>
    <?php $desc = $model->title; $keywords = $model->keyword; ?>
<?php else: ?>
    <?php $desc = Yii::$app->name; $keywords = ''; ?>
<?php endif; ?>
<?= $this->registerMetaTag(['name' => 'description','content' => $desc]); ?>
<?= $this->registerMetaTag(['name' => 'keywords','content' => $keywords]); ?>

<?php $this->title = $model->title; ?>
<div class="main-content text-center">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="text-left"><?=$model->description?></div>
</div>
<?php if (count($advert) != 0 || count($rp) != 0): ?>
    <div class="row ">
        <?php if (count($rp) == 0): ?>
            <?php foreach ($advert as $object): ?>
                <div class="col-sm-2 text-center objects-small">
                    <?php
                    if ($objectimage=Image::find()->where(['object_id'=>$object->id])->orderBy('id')->one()) {
                        $image = $objectimage->image;
                    } else {
                        $image = "upload/images/default/default.jpg";
                    } ?>
                    <?= Html::a(Html::img(Yii::$app->request->baseUrl."/".$image),['/'.$object->alias])?>
                    <h4><?= Html::a($object->full_title,['/'.$object->alias], ['class'=>"title"])?></h4>
                    <p><?= Html::a($config->address,['/'.$object->alias], ['class'=>"address"])?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php for ($i=0; $i <= 6 + count($rp); $i++) {
                if ($r = Rp::find()->where(['page'=>'/'.$url])->andWhere('date >= NOW()')->andWhere(['number'=>$i+1])->one()) {
                    $object = Object::findOne(['id'=>$r->iditem]);
                } else {
                    $object = $advert[$i];
                }
                if ($object) {?>
                    <div class="col-sm-2 text-center objects-small">
                        <?php if ($objectimage=Image::find()->where(['object_id'=>$object->id])->orderBy('id')->one()) {
                            $image = $objectimage->image;
                        } else {
                            $image = "upload/images/default/default.jpg";
                        } ?>
                        <?= Html::a(Html::img(Yii::$app->request->baseUrl."/".$image),['/'.$object->alias])?>
                        <h4><?= Html::a($object->full_title,['/'.$object->alias], ['class'=>"title"])?></h4>
                        <p><?= Html::a($config->address,['/'.$object->alias], ['class'=>"address"])?></p>
                    </div>
                <?php }
            } ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
