<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\Object;
use app\models\Image;
use app\models\Properties;

/* @var $this yii\web\View */
/* @var $model app\models\Ads */
$user =  User::findOne(['id'=>$model->user_id]);
$object = Object::findOne(['id'=>$model->object_id]);
$img = Image::findOne(['object_id'=>$model->object_id]);
?>
<div class="row">
    <div class="col-lg-10">
        <div class="item-container item-featured reviews-enabled">
            <div class="content bron">
                <div class="item-image">
                    <?php if ($img): ?>
                        <img src="<?=Yii::$app->request->baseUrl.'/'. $img->image?>" alt="<?=$model->id?>" width="" height="">
                    <?php else: ?>
                        <img class="" src="<?=Yii::$app->request->baseUrl?>/images/default.jpg">
                    <?php endif; ?>
                </div>
                <div class="item-data">
                    <div class="item-header">
                        <div class="item-title">
                            <?php $objtitle = $object->full_title;?>
                            <h4><?=Html::a($objtitle, ['/'.$object->alias], ['target'=>'_blank'])?></h4>
                        </div>
                    </div>
                    <div class="item-body">
                        <p>Адрес: <b><?=(Properties::findOne(['field_id'=>5, 'object_id'=>$object->id]))?Properties::findOne(['field_id'=>5, 'object_id'=>$object->id])->field_value:$object->address?></b></p>
                    </div>
                    <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i>', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger remove-button pull-right',
                        'data' => [
                            'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот объект из закладок?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
