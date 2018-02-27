<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\db\Query;
use app\models\Object;
use app\models\Image;
use app\models\Post;
use app\models\Servis;
use app\models\Curency;
use app\models\Config;
use app\models\Rp;

$post = Post::findOne(['id'=>1]);

$locality = Config::findOne(['id'=>1]);
$alias = $locality->alias_three;
$aliasprefix = explode('-', $alias)[0];
$aliasthree = mb_convert_case(explode("-", $alias)[1], MB_CASE_TITLE, "UTF-8");
$aliasall = explode($aliasprefix."-".explode("-", $alias)[1],$alias)[1];
$aliasend = implode(' ', explode('-',$aliasall));

if (strpos($locality->title, '-') !== false) {
    $divaider = " -";
} else {
    $divaider = " ";
}

$title = 'Отдых '.$aliasprefix.' '.$aliasthree. $divaider .$aliasend;
$this->title = $title;

$objectservs = (new Query())->select(['service'])->from('object')->where(['active'=>1]);
$servs = Servis::find()->andFilterWhere(['in','id', $objectservs])->orderBy('title ASC')->all();
$servitems = [];
foreach ($servs as $s) {
    array_push($servitems, $s->parent_id); $servitems = array_unique($servitems);
}
if (count($servitems)==0) {
    $servitems = ['0'];
}
$parents = Servis::find()->andFilterWhere(['in','id', $servitems])->orderBy('sort DESC')->all();
?>
<div class="site-index">
    <div class="object-content text-center">
        <h1 class="text-primary"><?=$title?></h1>
        <div class="object-list">
            <div class="row">
                <div class="col-lg-12">
                    <div id="services-all">
                        <?php foreach ($parents as $parent): ?>
                            <ul><li><b>
                                <?=Html::a(Servis::findOne(['id'=>$parent->id])->title, ["/".Servis::findOne(['id'=>$parent->id])->alias.'-'.$locality->alias_two], ['class'=>'text-green'] )?>
                            </b></li>
                                <?php foreach ($servs as $child): ?>
                                    <?php if (Servis::findOne(['id'=>$child->id])->parent_id==$parent->id): ?>
                                        <li><?=Html::a(Servis::findOne(['id'=>$child->id])->title, ["/".Servis::findOne(['id'=>$child->id])->alias.'-'.$locality->alias_two])?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <h2 class="title-carousel">Лучшие предложения по онлайн-бронированию без процентов и переплат!</h2>
            <div class="owl-carousel object-carousel owl-theme text-center">
                <?php foreach ($objects as $object): ?>
                    <?php $objectprice =0;?>
                    <div class="item">
                        <?php
                        if ($objectimage=Image::find()->where(['object_id'=>$object->id])->orderBy('id')->one()) {
                            $image = $objectimage->image;
                        } else {
                            $image = "upload/images/default/default.jpg";
                        } ?>
                        <?php if ($objectprice != 0): ?>
                            <span class="price">Цена от <?=$objectprice." ".Curency::findOne(['id'=>$object->curency_id])->mini_title?></span>
                        <?php endif; ?>
                        <?= Html::a(Html::img(Yii::$app->request->baseUrl."/".$image),['/'.$object->alias])?>
                        <div class="item-desc">
                            <h3 class="title"><?= Html::a($object->full_title,['/'.$object->alias], ['class'=>"title"])?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="main-content text-center">
        <?=$post->description?>
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
                    if ($r = Rp::find()->where(['page'=>'/'])->andWhere('date >= NOW()')->andWhere(['number'=>$i+1])->one()) {
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
</div>
