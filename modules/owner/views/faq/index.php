<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Faq;

$faqs = Faq::find()->all();

$this->title = Yii::t('app', 'Вопросы/ответы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="all-faqs">
        <?php foreach ($faqs as $faq): ?>
            <div class="faqs">
                <div class="question"><p><?=$faq->question?></p></div>
                <div class="answer"><p><?=$faq->answer?></p></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
