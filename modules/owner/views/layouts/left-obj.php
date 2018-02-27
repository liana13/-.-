<?php
use yii\helpers\Html;
use app\models\Object;

?>
<?php if (!empty(explode("add/",$_SERVER['REQUEST_URI'])[1])): ?>
    <?php $tarifid = explode("add/",$_SERVER['REQUEST_URI'])[1]; ?>
<?php elseif(Yii::$app->session->get('tarifid')): ?>
    <?php $tarifid = Yii::$app->session->get('tarifid');?>
<?php else: ?>
    <?php $tarifid = 6; ?>
<?php endif; ?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?php if ($tarifid == 2 || $tarifid == 4 || $tarifid == 3): ?>
            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Основное', 'icon' => 'object-group', 'url' => ['/owner/object/add/'.$tarifid]],
                        ['label' => 'Финансы', 'icon' => 'money', 'url'=>'#', 'template' => '<a href="{url}" class="disabled" tabindex="-1" title="Заполните данные объекта, чтобы активировать вкладку.">{icon} {label}</a>'],
                    ],
                ]
            ) ?>
        <?php elseif ($tarifid == 1): ?>
            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Основное', 'icon' => 'object-group', 'url' => ['/owner/object/add/'.$tarifid]],
                        ['label' => 'Финансы', 'icon' => 'money', 'url'=>'#', 'template' => '<a href="{url}" class="disabled" tabindex="-1" title="Заполните данные объекта, чтобы активировать вкладку.">{icon} {label}</a>'],
                    ],
                ]
            ) ?>
        <?php elseif ($tarifid == 5): ?>
            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Основное', 'icon' => 'object-group', 'url' => ['/owner/object/add/'.$tarifid]],
                        ['label' => 'Категории номеров', 'icon' => 'object-group', 'url' => ['/owner/default/category'], 'template' => '<a href="{url}" class="disabled" tabindex="-1" title="Заполните данные объекта, чтобы активировать вкладку.">{icon} {label}</a>'],
                        ['label' => 'Календарь и цены', 'icon' => 'address-book', 'url' => ['/owner/default/calendar'], 'template' => '<a href="{url}" class="disabled" tabindex="-1" title="Заполните данные объекта, чтобы активировать вкладку.">{icon} {label}</a>'],
                        ['label' => 'Финансы', 'icon' => 'money', 'url'=>'#', 'template' => '<a href="{url}" class="disabled" tabindex="-1" title="Заполните данные объекта, чтобы активировать вкладку.">{icon} {label}</a>'],
                    ],
                ]
            ) ?>
        <?php endif; ?>
    </section>
</aside>
