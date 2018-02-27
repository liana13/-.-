<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
?>
<div class="user-view shadow-out">
    <div class="row row-cab">
        <div class="col-sm-12">
            <h3>Профиль</h3><?= Html::a("Редактировать",['default/update/'.Yii::$app->user->getId()], ['class'=>"btn btn-common col-sm-offset-8"])?>
            <div class="row">
                <div class="col-md-9">
                    <div class="profile-img pull-left">
                        <?php if ($model->avatar): ?>
                            <img class="img-circle" src="<?=Yii::$app->request->baseUrl.'/'.$model->avatar?>">
                        <?php else: ?>
                            <img class="img-circle" src="<?=Yii::$app->request->baseUrl?>/images/avatar-user.png">
                        <?php endif; ?>
                    </div>
                     <?php if ($model->name): ?>
                    <p>Имя: <?=$model->name?></p>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
            <h3>Адреса электронной почты</h3>
            <div class="items">
                <div class="row">
                    <div class="col-md-12"><?=$model->email?></div>
                </div>
            </div>
            <?php if ($model->phone): ?>
                <h3>Телефоны</h3>
                <div class="items">
                    <div class="row">
                        <div class="col-md-12"><?=$model->phone?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
