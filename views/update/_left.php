<?php $tarifalias = $object->tarif_id; $newtarif = $object->new_tarif; ?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?php if ($tarifalias == 1 || $tarifalias == 2 || $tarifalias == 3 || $newtarif == 1 || $newtarif == 2 || $newtarif == 3 ): ?>
            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Основное', 'icon' => 'fa fa-object-group', 'url' => ['/update/'.$object->alias]],
                        ['label' => 'Финансы', 'icon' => 'fa fa-money', 'url'=>['/update/finance/'.$object->id]],
                    ],
                ]
            ) ?>
        <?php elseif ($tarifalias == 0 && $newtarif == 0): ?>
            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Основное', 'icon' => 'fa fa-object-group', 'url' => ['/update/'.$object->alias]],
                        ['label' => 'Финансы', 'icon' => 'fa fa-money', 'url'=>['/update/finance/'.$object->id]],
                    ],
                ]
            ) ?>
        <?php else: ?>
            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Основное', 'icon' => 'fa fa-object-group', 'url' => ['/update/'.$object->alias]],
                        ['label' => 'Категории номеров', 'fa fa-icon' => 'object-group', 'url' => ['/update/catroom/'.$object->id]],
                        ['label' => 'Календарь и цены', 'icon' => 'fa fa-address-book', 'url' => ['/update/availability/'.$object->id]],
                        ['label' => 'Финансы', 'icon' => 'fa fa-money', 'url'=>['/update/finance/'.$object->id]],
                    ],
                ]
            ) ?>
        <?php endif; ?>
    </section>
</aside>
