<?php

use yii\helpers\Html;
use yii\db\Query;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use app\models\Image;
use yii\widgets\ActiveForm;
use app\models\Person;
use app\models\Review;
use app\models\User;
use app\models\Properties;
use mirocow\yandexmaps\Canvas as YandexCanvas;
use mirocow\yandexmaps\Map as YandexMap;
use mirocow\yandexmaps\objects\Placemark as Placemark;
use yii\bootstrap\Modal;
use yii\captcha\Captcha;
use app\models\Field;
use app\models\Servis;
use app\models\Catroom;
use app\models\Food;
use app\models\Childage;
use app\models\Room;
use app\models\Price;
use app\models\Curency;
use app\models\filter;
use app\models\Calendar;
use app\models\Bookmark;
use app\models\AdminForm;
use app\models\Tarif;

$fields = Field::find()->where(['!=', 'class', ""])->orderBy('sort asc')->all();
$title = $model->full_title;
$reviews = Review::find()->where(['object_id'=>$model->id, 'status'=>1])->all();
if (Person::findOne(['id'=>$model->user_id])) {
    $phone=Person::findOne(['id'=>$model->user_id])->phone;
}
if ($model->tarif_id==4) {
    $imgcount = explode(",",Tarif::findOne(['tarifid'=>4])->photo)[0];
} else {
    $imgcount = Tarif::findOne(['tarifid'=>$model->tarif_id])->photo;
}
$images=Image::find()->where(['object_id'=>$model->id])->orderBy('id')->limit($imgcount)->all();
$model_cal = new filter();
$catrooms = Catroom::find()->where(['object_id'=>$model->id])->all();
$adminform = new AdminForm();
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

<div class="object-view">
    <div class="row">
        <div class="col-lg-7">
            <div id="slider" class="flexslider <?=(count($images)==1 || count($images)==0) ? 'single' : ''?>">
                <ul class="slides">
                    <?php if (Image::find()->where(['object_id'=>$model->id])): ?>
                        <?php if (count($images) != 0): ?>
                            <?php foreach ($images as $img): ?>
                                <li>
                                    <?= Html::a(Html::img(Html::encode(Yii::$app->request->baseUrl.'/'. $img->image), ['title'=>(!empty($img->value))?$img->value:'', 'alt' => Html::encode($model->title), 'class'=>"clasadimage"]),
                                                    Html::encode(Yii::$app->request->baseUrl.'/'. $img->image), ['data-fancybox'=>"gallery",  'rel'=>'lightbox'])?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><img src="<?=Yii::$app->request->baseUrl?>/upload/images/default/default.jpg" alt="<?=Html::encode($model->title)?>"/></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if (count($images) != 1 && count($images)!=0): ?>
                <div id="carousel" class="flexslider">
                    <ul class="slides">
                        <?php if (count($images) != 0): ?>
                            <?php foreach ($images as $img): ?>
                                <li><?= Html::img(Html::encode(Yii::$app->request->baseUrl.'/'. $img->image), ['title'=>(!empty($img->value))?$img->value:'', 'alt' => Html::encode($model->title), 'class'=>"clasadimage"]); ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><img src="<?=Yii::$app->request->baseUrl?>/upload/images/default/default.jpg" alt="<?=Html::encode($model->title)?>"/></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-5 object-info">
            <div class="text-center">
                <?php if (!Yii::$app->user->isGuest && User::findOne(['id'=>Yii::$app->user->getId()])->type==2): ?>
                    <?php if (Bookmark::findOne(['user_id'=>Yii::$app->user->getId(), 'object_id'=>$model->id])): ?>
                        <?=Html::a('<i class="fa fa-heart" aria-hidden="true"></i>', ['/object/bookmark?id='.$model->id], ['title'=>'Удалить из закладок', 'class'=>'pull-right'])?>
                    <?php else: ?>
                        <?=Html::a('<i class="fa fa-heart-o" aria-hidden="true"></i>', ['/object/bookmark?id='.$model->id], ['title'=>'Добавить в закладки', 'class'=>'pull-right'])?>
                    <?php endif; ?>
                <?php endif; ?>
                <h1 class="title">
                    <?= $title?>
                </h1>
            </div>
            <?php if (Properties::findOne(['field_id'=>5, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>5])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>5, 'object_id'=>$model->id])->field_value?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($model->tarif_id!=4 && Properties::findOne(['field_id'=>7, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>7])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>7, 'object_id'=>$model->id])->field_value?>
                      <p><small class="text-danger">При звонке, пожалуйста, ссылайтесь на портал <?=Yii::$app->name?></small></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>32, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>32])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>32, 'object_id'=>$model->id])->field_value?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>1, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-ship" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>1])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>1, 'object_id'=>$model->id])->field_value." метров"?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>2, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-building-o" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>2])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                        <?php if (Properties::findOne(['field_id'=>2, 'object_id'=>$model->id])->field_value != 0): ?>
                            <?=Properties::findOne(['field_id'=>2, 'object_id'=>$model->id])->field_value." метров"?>
                        <?php else: ?>
                            <?="В центре"?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>34, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-level-up" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>34])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>34, 'object_id'=>$model->id])->field_value." метров"?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>6, 'object_id'=>$model->id]) && Properties::findOne(['field_id'=>6, 'object_id'=>$model->id])->field_value != "N ;E"
                        && Properties::findOne(['field_id'=>6, 'object_id'=>$model->id])->field_value != "N ; E"): ?>
                <div class="row">
                    <div class="col-md-5">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>6])->title?>: </span>
                    </div>
                    <div class="col-md-7">
                        <?= Properties::findOne(['field_id'=>6, 'object_id'=>$model->id])->field_value ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($model->tarif_id != 4): ?>
                <?php if (Properties::findOne(['field_id'=>9, 'object_id'=>$model->id])): ?>
                    <div class="row">
                        <div class="col-md-5">
                            <i class="fa fa-globe" aria-hidden="true"></i>
                            <span>Адрес в интернет:</span>
                        </div>
                        <div class="col-md-7">
                            <?php $url = Properties::findOne(['field_id'=>9, 'object_id'=>$model->id])->field_value; ?>
                            <?php if (mb_stripos($url, 'http://') !== false || mb_stripos($url, 'https://') !== false): ?>
                                <?= Html::a(Properties::findOne(['field_id'=>9, 'object_id'=>$model->id])->field_value, $url, ['target'=>'_blank'])?>
                            <?php else: ?>
                                <?= Html::a(Properties::findOne(['field_id'=>9, 'object_id'=>$model->id])->field_value, 'http://'.$url, ['target'=>'_blank'])?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="row">
                <?php if (Properties::findOne(['field_id'=>4, 'object_id'=>$model->id])): ?>
                    <div class="col-sm-6">
                        <a href="#seeall" class="see-link"><i class="fa fa-map" aria-hidden="true"></i> Посмотреть на карте</a>
                    </div>
                <?php endif; ?>
                <?php if ($model->allow_review == 1 && $model->tarif_id == 4): ?>
                    <div class="col-sm-6">
                        <a href="#seereviews" class="see-rev"><i class="fa fa-comments-o" aria-hidden="true"></i> Читать отзывы</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="texttop">
        <div class="newpage">
            <?php if ($model->general): ?>
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        <span>Основное: </span>
                    </div>
                    <div class="col-sm-9 general">
                        <p><?=$model->general?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($model->description): ?>
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-file-text" aria-hidden="true"></i>
                        <span>Описание: </span>
                    </div>
                    <div class="col-sm-9">
                        <p><?=$model->description?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>11, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-object-group" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>11])->title?>: </span>
                    </div>
                    <div class="col-sm-9">
                        <p><?=Properties::findOne(['field_id'=>11, 'object_id'=>$model->id])->field_value?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (count($catrooms)!=0 && $model->tarif_id == 4): ?>
                <div class="room-price" id="filter-box">
                    <?php $form = ActiveForm::begin(['options'=>['class'=>'cat-search-form'],'action' => ['/'.$model->alias],'method'=>'get']);?>
                        <?= $form->field($model_cal, 'adult')->hiddenInput(['value'=>(!empty(Yii::$app->request->get('filter')['adult']))?Yii::$app->request->get('filter')['adult']:'1'])->label(false) ?>
                        <?= $form->field($model_cal, 'child')->hiddenInput(['value'=>(!empty(Yii::$app->request->get('filter')['child']))?Yii::$app->request->get('filter')['child']:''])->label(false) ?>
                        <?= $form->field($model_cal, 'age_1')->hiddenInput(['value'=>(!empty(Yii::$app->request->get('filter')['age_1']))?Yii::$app->request->get('filter')['age_1']:''])->label(false) ?>
                        <?= $form->field($model_cal, 'age_2')->hiddenInput(['value'=>(!empty(Yii::$app->request->get('filter')['age_2']))?Yii::$app->request->get('filter')['age_2']:''])->label(false) ?>
                        <?= $form->field($model_cal, 'age_3')->hiddenInput(['value'=>(!empty(Yii::$app->request->get('filter')['age_3']))?Yii::$app->request->get('filter')['age_3']:''])->label(false) ?>
                        <?= $form->field($model_cal, 'age_4')->hiddenInput(['value'=>(!empty(Yii::$app->request->get('filter')['age_4']))?Yii::$app->request->get('filter')['age_4']:''])->label(false) ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <h3>НОМЕРА И ЦЕНЫ</h3>
                            </div>
                            <div class="col-sm-2 pad text-center">
                                <?= $form->field($model_cal, 'from')->textInput(['class'=>'form-control calendar-search calendar-click', 'readonly' => true, 'value'=>(Yii::$app->request->get('filter')['from'])?Yii::$app->request->get('filter')['from']:''])->label('Дата заезда') ?>
                                <i class='fa fa-calendar text-primary calendaricon' aria-hidden='true' id='calendar_open'></i>
                                <input type="text" class="daterange calendar" value=""/>
                            </div>
                            <div class="col-sm-2 pad text-center">
                                <?= $form->field($model_cal, 'to')->textInput(['class'=>'form-control calendar-search calendar-click', 'readonly' => true, 'value'=>(Yii::$app->request->get('filter')['from'])?Yii::$app->request->get('filter')['to']:''])->label('Дата выезда') ?>
                                <i class='fa fa-calendar text-primary calendaricon' aria-hidden='true' id='calendar_open'></i>
                            </div>
                            <?php $ages = "";
                            if (Yii::$app->request->get('filter')['child'] == 4) {
                                if (Yii::$app->request->get('filter')['age_1']) {
                                    $ages .= Yii::$app->request->get('filter')['age_1'].", ";
                                } else {
                                    $ages .= "0, ";
                                }
                                if (Yii::$app->request->get('filter')['age_2']) {
                                    $ages .= Yii::$app->request->get('filter')['age_2'].", ";
                                } else {
                                    $ages .= "0, ";
                                }
                                if (Yii::$app->request->get('filter')['age_3']) {
                                    $ages .= Yii::$app->request->get('filter')['age_3'].", ";
                                } else {
                                    $ages .= "0, ";
                                }
                                if (Yii::$app->request->get('filter')['age_4']) {
                                    $ages .= Yii::$app->request->get('filter')['age_4']." лет";
                                } else {
                                    $ages .= "0 лет";
                                }
                            } elseif (Yii::$app->request->get('filter')['child'] == 3) {
                                if (Yii::$app->request->get('filter')['age_1']) {
                                    $ages .= Yii::$app->request->get('filter')['age_1'].", ";
                                } else {
                                    $ages .= "0, ";
                                }
                                if (Yii::$app->request->get('filter')['age_2']) {
                                    $ages .= Yii::$app->request->get('filter')['age_2'].", ";
                                } else {
                                    $ages .= "0, ";
                                }
                                if (Yii::$app->request->get('filter')['age_3']) {
                                    $ages .= Yii::$app->request->get('filter')['age_3']." лет";
                                } else {
                                    $ages .= "0 лет";
                                }
                            } elseif (Yii::$app->request->get('filter')['child'] == 2) {
                                if (Yii::$app->request->get('filter')['age_1']) {
                                    $ages .= Yii::$app->request->get('filter')['age_1'].", ";
                                } else {
                                    $ages .= "0, ";
                                }
                                if (Yii::$app->request->get('filter')['age_2']) {
                                    $ages .= Yii::$app->request->get('filter')['age_2']." лет";
                                } else {
                                    $ages .= "0 лет";
                                }
                            } elseif (Yii::$app->request->get('filter')['child'] == 1) {
                                if (Yii::$app->request->get('filter')['age_1']) {
                                    $ages .= Yii::$app->request->get('filter')['age_1']." лет";
                                } else {
                                    $ages .= "0 лет";
                                }
                            } ?>
                            <?php $value = "";
                            if (Yii::$app->request->get('filter')['adult'] && Yii::$app->request->get('filter')['child']) {
                                $value = Yii::$app->request->get('filter')['adult']." взр и ".Yii::$app->request->get('filter')['child']." дет. (".$ages." )";
                            } elseif (Yii::$app->request->get('filter')['adult']) {
                                $value = Yii::$app->request->get('filter')['adult']." взр и 0 дет.";
                            }?>
                            <div class="col-sm-2 pad text-center">
                                <div class="form-group field-filter-count">
                                    <label class="control-label" for="filter-count">Укажите кол-во гостей</label>
                                    <input type="text" id="filter-count" readonly value="<?=$value?>" class="form-control calendar-search">
                                </div>
                                <div class="form-guest pull-left" id="count-box">
                                    <div class="header_ages">
                                        <div class="row">
                                            <div class="col-sm-7">
                                               <p>Взрослые</p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="counts"> <i class="fa fa-plus plus_adult"  aria-hidden="true"></i> <span class="adult_count"> <?=(Yii::$app->request->get('filter')['adult'])? Yii::$app->request->get('filter')['adult']:'1'?> </span> <i class="fa fa-minus minus_adult" aria-hidden="true"></i></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-7">
                                               <p>Дети</p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="counts"> <i class="fa fa-plus plus-max"  aria-hidden="true"></i> <span class="child_count"> <?=(Yii::$app->request->get('filter')['child'])? Yii::$app->request->get('filter')['child']:'0'?></span> <i class="fa fa-minus minus-max" aria-hidden="true"></i></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ages">
                                        <div class="row <?=(Yii::$app->request->get('filter')['child'] >= 1 )?'':'age'?> age-1">
                                            <div class="col-sm-7">
                                               <p>Возраст <small>(от 0 до 17)</small> </p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="counts"> <i class="fa fa-plus plus_age1"  aria-hidden="true" onclick="countAgePlus('adult_count_1', 'filter-age_1')"></i> <span class="adult_count_age adult_count_1"> <?=(Yii::$app->request->get('filter')['age_1'])? Yii::$app->request->get('filter')['age_1']:'0'?>
                                                </span> <i class="fa fa-minus minus" aria-hidden="true" onclick="countAgeMinus('adult_count_1', 'filter-age_1')"></i></p>
                                            </div>
                                        </div>
                                        <div class="row <?=(Yii::$app->request->get('filter')['child'] >= 2)?'':'age'?> age-2">
                                            <div class="col-sm-7">
                                               <p>Возраст <small>(от 0 до 17)</small></p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="counts"> <i class="fa fa-plus plus_age2"  aria-hidden="true" onclick="countAgePlus('adult_count_2', 'filter-age_2')"></i> <span class="adult_count_age adult_count_2"> <?=(Yii::$app->request->get('filter')['age_2'])? Yii::$app->request->get('filter')['age_2']:'0'?> </span> <i class="fa fa-minus minus" aria-hidden="true" onclick="countAgeMinus('adult_count_2', 'filter-age_2')"></i></p>
                                            </div>
                                        </div>
                                        <div class="row <?=(Yii::$app->request->get('filter')['child'] >=3)?'':'age'?> age-3">
                                            <div class="col-sm-7">
                                               <p>Возраст <small>(от 0 до 17)</small></p>
                                            </div>
                                            <div class="col-sm-5 ">
                                                <p class="counts"> <i class="fa fa-plus plus_age3"  aria-hidden="true" onclick="countAgePlus('adult_count_3', 'filter-age_3')"></i> <span class="adult_count_age adult_count_3"> <?=(Yii::$app->request->get('filter')['age_3'])? Yii::$app->request->get('filter')['age_3']:'0'?> </span> <i class="fa fa-minus minus" aria-hidden="true" onclick="countAgeMinus('adult_count_3', 'filter-age_3')"></i></p>
                                            </div>
                                        </div>
                                        <div class="row <?=(Yii::$app->request->get('filter')['child'] >= 4)?'':'age'?> age-4">
                                            <div class="col-sm-7">
                                               <p>Возраст <small>(от 0 до 17)</small></p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="counts"> <i class="fa fa-plus plus_age4"  aria-hidden="true" onclick="countAgePlus('adult_count_4', 'filter-age_4')"></i> <span class="adult_count_age adult_count_4"> <?=(Yii::$app->request->get('filter')['age_4'])? Yii::$app->request->get('filter')['age_4']:'0'?> </span> <i class="fa fa-minus minus" aria-hidden="true" onclick="countAgeMinus('adult_count_4', 'filter-age_4')"></i></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <?= Html::submitButton(Yii::t('app', 'Найти'), ['class' => 'btn btn-common']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-danger">Онлайн-бронирование осуществляется по ценам объекта без процентов и комиссий.</p>
                            <?php if (Yii::$app->request->get('filter')): ?>
                                <?php $jsscroll='$("html, body").animate({ scrollTop: $("#filter-box").offset().top }, "1000");'; $this->registerJs($jsscroll); ?>
                                <?= Html::a('Изменить параметры',['/'.$model->alias]); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-12">
                            <?php if (Yii::$app->request->get('filter') && !empty(Yii::$app->request->get('filter')['from']) && !empty(Yii::$app->request->get('filter')['to'])): ?>
                                <?= ListView::widget([
                                    'dataProvider'=>$dataProvider,
                                    'emptyText' => 'К сожалению номеров с выбранными параметрами на данный момент нет, попробуйте изменить даты или выбрать меньшее количество гостей.',
                                    'itemOptions' => ['class' => ''],
                                    'itemView' => '_catroomfilter',
                                    'summary'=>'',
                                    'pager' => '',
                                ]) ?>
                            <?php else: ?>
                                <?= ListView::widget([
                                    'dataProvider'=>$dataProvider,
                                    'emptyText' => 'К сожалению номеров с выбранными параметрами на данный момент нет, попробуйте выбрать меньшее количество гостей.',
                                    'itemOptions' => ['class' => 'list-view list-obj-cat'],
                                    'itemView' => '_catroomitem',
                                    'summary'=>'',
                                    'pager' => '',
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="back"></div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>49, 'object_id'=>$model->id]) || Properties::findOne(['field_id'=>50, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <?php if (Properties::findOne(['field_id'=>49, 'object_id'=>$model->id])): ?>
                        <div class="col-sm-offset-3 col-sm-3">
                            <i class="fa fa-sign-in" aria-hidden="true"></i>
                            <span><?=Field::findOne(['id'=>49])->title?>: </span>
                            <?= Properties::findOne(['field_id'=>49, 'object_id'=>$model->id])->field_value ?>
                        </div>
                    <?php endif; ?>
                    <?php if (Properties::findOne(['field_id'=>50, 'object_id'=>$model->id])): ?>
                        <div class="col-sm-3">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            <span><?=Field::findOne(['id'=>50])->title?>: </span>
                            <?= Properties::findOne(['field_id'=>50, 'object_id'=>$model->id])->field_value ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($model->tarif_id != 4): ?>
                <?php if (Properties::findOne(['field_id'=>31, 'object_id'=>$model->id])): ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span>Цены: </span>
                        </div>
                        <div class="col-sm-9">
                            <?= Properties::findOne(['field_id'=>31, 'object_id'=>$model->id])->field_value ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>38]) && $model->tarif_id !=4 || Properties::findOne(['object_id'=>$model->id, 'field_id'=>36]) && $model->tarif_id !=4
                        || $model->tarif_id == 4 || Properties::findOne(['object_id'=>$model->id, 'field_id'=>39])): ?>
                <div class="row">
                    <?php if(Properties::findOne(['object_id'=>$model->id, 'field_id'=>38]) && $model->tarif_id !=4 || Properties::findOne(['object_id'=>$model->id, 'field_id'=>36]) && $model->tarif_id !=4): ?>
                        <div class="col-lg-offset-3 col-lg-3">
                            <?php Modal::begin([
                                'header' => '<h3>Заявка на бронирование</h3><p>Заявка отправляется напрямую в данный объект, но не гарантирует бронирования. Если в течении суток с Вами не свяжется представитель данного объекта, то пожалуйста свяжитесь с объектом сами.</p>',
                                'toggleButton' => ['label' => 'Отправить заявку на бронь', 'class' => 'btn btn-modal-open btn-common', 'data-toggle'=>"modal", 'title'=>'Бронирование без процентов. Заявка отправляется в данный объект.'],
                            ]);?>
                                <?= $this->render('_contact', ['model'=>$model]) ?>
                            <?php Modal::end(); ?>
                        </div>
                        <?php if(Properties::findOne(['object_id'=>$model->id, 'field_id'=>39])): ?>
                            <?php Modal::begin([
                                'header' => '<h3>Написать администратору объекта</h3>',
                                'size'=>'custom-modal',
                                'toggleButton' => ['label' => 'Написать администратору объекта', 'class' => 'btn btn-modal-open btn-common'],
                            ]);?>
                            <?= $this->render('_messagetarif', ['object'=>$model->id]) ?>
                            <?php Modal::end(); ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="col-lg-offset-3 col-lg-3">
                            <?php if (Yii::$app->user->isGuest && $model->tarif_id == 4): ?>
                                <?php Modal::begin([
                                    'header' => '<h3 class="text-center">Уведомление</h3>',
                                    'size'=>'modal-sm',
                                    'id'=>'dialog-mess',
                                    'toggleButton' => ['label' => 'Написать администратору объекта', 'class' => 'btn btn-modal-open btn-common'],
                                ]);?>
                                   <?="<p>". Html::a('Авторизуйтесь',[''], ['class'=>'login-btn', "data-dismiss"=>"modal"]) . ", чтобы написать письмо.</p>"?>
                                   <?= Html::button('Ok',['class'=>'btn-close btn btn-login btn-common', "data-dismiss"=>"modal", "aria-hidden"=>true])?>
                                <?php Modal::end(); ?>
                            <?php elseif($model->tarif_id == 4): ?>
                                <?php Modal::begin([
                                    'header' => '<h3>Написать администратору объекта</h3>',
                                    'size'=>'custom-modal',
                                    'toggleButton' => ['label' => 'Написать администратору объекта', 'class' => 'btn btn-modal-open btn-common'],
                                ]);?>
                                <?= $this->render('_message', ['object'=>$model->id]) ?>
                                <?php Modal::end(); ?>
                            <?php elseif(Properties::findOne(['object_id'=>$model->id, 'field_id'=>39])): ?>
                                <?php Modal::begin([
                                    'header' => '<h3>Написать администратору объекта</h3>',
                                    'size'=>'custom-modal',
                                    'toggleButton' => ['label' => 'Написать администратору объекта', 'class' => 'btn btn-modal-open btn-common'],
                                ]);?>
                                <?= $this->render('_messagetarif', ['object'=>$model->id]) ?>
                                <?php Modal::end(); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="fields-box">
                <?php foreach ($fields as $field): ?>
                    <?php if (Properties::findOne(['field_id'=>$field->id, 'object_id'=>$model->id]) && $field->id!=1 && $field->id!=2 && $field->id!=11 && $field->id!=32 && $field->id!=34): ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <i class="fa <?=$field->class?>" aria-hidden="true"></i>
                                <span><?=$field->title?>: </span>
                            </div>
                            <div class="col-sm-9">
                                <p><?=Properties::findOne(['field_id'=>$field->id, 'object_id'=>$model->id])->field_value?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php if (Properties::findOne(['field_id'=>4, 'object_id'=>$model->id])): ?>
        <hr>
        <div class="text-top" id="seeall">
            <h3>Посмотреть на карте</h3>
            <div class="map">
                <?php
                $geocoord = Properties::findOne(['field_id'=>4, 'object_id'=>$model->id])->field_value;
                $coord1 = explode(',', explode("[",$geocoord)[1])[0];
                $coord2 = explode(',', explode("]",$geocoord)[0])[1];
                $map = new YandexMap('yandex_map', [
                    'center' => [$coord1,$coord2],
                    'zoom' => 11,
                    // Enable zoom with mouse scroll
                    'behaviors' => array('default', 'scrollZoom'),
                    'type' => "yandex#map",
                ],
                [
                    'objects' => [new Placemark([$coord1,$coord2], [], [
                        'draggable' => true,
                        'preset' => 'islands#dotIcon',
                        'iconColor' => '#ff0000',
                        // 'events' => [
                        //     'dragend' => 'js:function (e) {
                        //         console.log(e.get(\'target\').geometry.getCoordinates());
                        //         }'
                        // ],
                    ])],
                ],
                [
                    // Permit zoom only fro 9 to 11
                    'minZoom' => 7,
                    'maxZoom' => 17,
                    'controls' => [
                          "new ymaps.control.SmallZoomControl()",
                          "new ymaps.control.SearchControl([false])",
                          "new ymaps.control.TypeSelector(['yandex#map', 'yandex#satellite'])",
                    ],
                ]);
                echo YandexCanvas::widget([
                   'htmlOptions' => [
                       'style' => 'height: 400px; ',
                   ],
                   'map' => $map,
                ]);
                ?>
            </div>
        </div>
    <?php endif; ?>
    <p><small>Информация размещена объектом. Администрация портала не несёт ответственность за достоверность размещённых данных.</small></p>
    <p class="text-primary toggle-adminform">Сообщить об ошибке администрации портала</p>
    <div class="contact-admin" style="display:none";>
        <div class="row">
            <div class="col-sm-5">
                <?php $form = ActiveForm::begin(['action'=>['/site/contactadmin']]); ?>
                    <?= $form->field($adminform, 'objectid')->hiddenInput(['value' => $model->id])->label(false); ?>
                    <?= $form->field($adminform, 'object')->hiddenInput(['value' => $title])->label(false); ?>
                    <?= $form->field($adminform, 'email') ?>
                    <?= $form->field($adminform, 'body')->textarea(['rows' => 6]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-modal-open btn-common', 'name' => 'contact-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?php if ($model->allow_review == 1 && $model->tarif_id == 4): ?>
        <hr>
        <div class="reviews text-center" id="seereviews">
            <h3><?=(count($reviews) == 0)? "Отзывов нет": "Отзывы(".count($reviews).")"?></h3>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <?php if ($review->locality): ?>
                        <div class="nik pull-left"><?=User::findOne(['id'=>$review->user_id])->username?> <span>(<?=$review->locality?>)</span></div>
                    <?php else: ?>
                        <div class="nik pull-left"><?=User::findOne(['id'=>$review->user_id])->username?></div>
                    <?php endif; ?>
                    <div class="desc-review"><p><?=$review->description?></p></div>
                    <div class="rate-review">
                        <?php for ($i=0; $i < $review->rate; $i++) { ?>
                            <img src="<?=Yii::$app->request->baseUrl?>/images/star.png" alt="star.png">
                        <?php } ?>
                    </div>
                    <div class="created-review pull-right"><?=$review->created_at?></div>
                </div>
            <?php endforeach; ?>
            <?php if (count($reviews) > 5): ?>
                <div class="text-right">
                    <a href="#" id="loadMore" class="btn-show btn-animate">Читать ещё</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php if (Yii::$app->session->hasFlash('bron')){
    Modal::begin([
        'id'=>'bron-modal',
        'size'=>'modal-sm',
        'header' => '<h2 class="modal-title text-center">Уведомление</h2>',
    ]);?>
    <div id="bron-content">
        <p class="text-center"><?=Yii::$app->session->getFlash('bron')?></p>
        <?= Html::button('Ok',['class'=>'btn-close btn btn-login btn-common', "data-dismiss"=>"modal", "aria-hidden"=>true])?>
    </div>
    <?php
    Modal::end();
    $jsbron = "$('#bron-modal').modal('show');$('#bron-modal').find('#bron-content').show();";
    $this->registerJs($jsbron);
} ?>
<?php
$js = "$(document).ready(function(){
    $('.calendar-click').click(function(){
        $('.daterange').click();
    });
    $('.calendaricon').click(function(){
        $('.daterange').click();
    });
    $('.daterange').daterangepicker({
        'locale': {
            'firstDay':1,
            'daysOfWeek': ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'пт', 'сб'],
            'monthNames': ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октября', 'Ноябрь', 'Декабрь'],
        },
        autoApply: true,
        minDate:  new Date(),
    }, function(start, end, label) {
      console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
    $('.daterange').on('apply.daterangepicker', function(ev, picker) {
        $('#filter-from').val(picker.startDate.format('YYYY-MM-DD'));
        $('#filter-to').val(picker.endDate.format('YYYY-MM-DD'));
    });
 })";
 $this->registerJs($js);
?>
