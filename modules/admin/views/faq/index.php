<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Faq;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

$faqs = Faq::find()->all();
$this->title = Yii::t('app', 'Вопросы/ответы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-index">

    <h1><?= Html::encode($this->title) ?>   <?= Html::a(Yii::t('app', 'Добавить'), ['create'], ['class' => 'btn btn-success']) ?></h1>
    <div class="all-faqs">
        <?php foreach ($faqs as $faq): ?>
            <?php Modal::begin([
                'id'=>'delete-modal'.$faq->id,
                'size'=>'modal-sm',
                'header' => '<h3 class="modal-title text-center">Уведомление</h3>',
            ]);?>
            <div id="delete-content<?=$faq->id?>">
                <?php $form = ActiveForm::begin([
                    'id'=>'deleteform'.$faq->id,
                    'action'=>['faq/delete/'.$faq->id],
                ]); ?>
                    <h3 class="text-center">Вы уверены,что хотите удалить?</h3>
                    <div class="form-group text-center">
                        <?= Html::submitButton('ДА', ['class' => 'btn btn-common', 'name' => 'delete-button']) ?>
                        <?= Html::button('НЕТ', ['class' => 'btn btn-common', 'onclick' => 'deletebutton('.$faq->id.')']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
            <?php Modal::end();?>
            <div class="faqs">
                <div class="question"><i class="fa fa-trash text-danger" title="Удалить вопрос и ответ" onclick="alertfunction(<?=$faq->id?>)" ></i>  <p><?=$faq->question?></p></div>
                <div class="answer"><p><?=$faq->answer?></p></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script type="text/javascript">
function deletebutton(x) {
    $('#delete-modal'+x).modal('hide');
}
function alertfunction(x) {
    $('#delete-modal'+x).modal('show');
}
</script>
