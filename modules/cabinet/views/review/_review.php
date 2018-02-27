<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Object;
use app\models\User;
use dosamigos\tinymce\TinyMce;
$object = Object::findOne(['id'=>$model->object_id]);

/* @var $this yii\web\View */
/* @var $model app\models\Review */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="review-form">
    <div class="review-item item-container" style="display: block;">
        <div class="nik">
            <?php $objtitle = $object->full_title;?>
            <h4><?=Html::a($objtitle, ['/'.$object->alias], ['target'=>'_blank'])?></h4>
        </div>
        <div class="desc-review"><p><?=$model->description?></p></div>
        <div class="rate-review">
            <?php for ($i=0; $i < $model->rate; $i++) { ?>
                <i class="fa fa-star" aria-hidden="true"></i>
            <?php } ?>
        </div>
        <div class="created-review"><p><?=$model->created_at?></p></div>
        <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i>', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger  pull-right',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот элемент?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
</div>
