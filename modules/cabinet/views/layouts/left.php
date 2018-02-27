<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Мой кабинет', 'icon' => 'fa fa-users', 'url' => ['/cabinet']],
                    ['label' => 'Бронирования', 'icon' => 'fa fa-address-book', 'url' => ['/cabinet/booking/future']],
                    ['label' => 'Сообщения', 'icon' => 'fa fa-comments-o', 'url' => ['/cabinet/message/index']],
                    ['label' => 'Отзывы', 'icon' => 'fa fa-star-o', 'url' => ['/cabinet/review/index']],
                    ['label' => 'Закладки', 'icon' => 'fa fa-heart-o', 'url' => ['/cabinet/bookmark/index']],
                    ['label' => 'Настройки', 'icon' => 'fa fa-cog', 'url' => ['/cabinet/default/update/'.Yii::$app->user->getId()]],
                ],
            ]
        ) ?>

    </section>

</aside>
