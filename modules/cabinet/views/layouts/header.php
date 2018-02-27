<?php
use yii\helpers\Html;
use app\models\User;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">рф</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <?php if (!Yii::$app->user->isGuest): ?>
                <ul class="nav navbar-nav">
                    <li class="user user-menu">
                        <a href="<?=Yii::$app->request->baseUrl?>/cabinet" class="text-uppercase">
                            <span class="hidden-xs"><?= User::findOne(['id'=>Yii::$app->user->getId()])->username ?></span>
                        </a>
                    </li>
                    <li class="user user-menu">
                        <?= Html::a(
                            'Выход',
                            ['/cabinet/default/logout'],
                            ['data-method' => 'post', 'class' => 'btn btn-rosy btn-flat']
                        ) ?>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>
</header>
