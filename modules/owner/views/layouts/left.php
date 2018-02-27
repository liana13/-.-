<aside class="main-sidebar">
    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Добавить новый объект', 'icon' => 'fa fa-plus', 'url' => ['/owner/object/create']],
                    ['label' => 'Мои объекты', 'icon' => 'fa fa-object-group', 'url' => ['/owner/object/index']],
                    ['label' => 'Сообщения', 'icon' => 'fa fa-comments-o', 'url' => ['/owner/message/index']],
                    ['label' => 'Бронирования', 'icon' => 'fa fa-address-book', 'url' => ['/owner/booking/index']],
                    ['label' => 'Настройки', 'icon' => 'fa fa-cog', 'url' => ['/owner/default/update/'.Yii::$app->user->getId()]],
                    ['label' => 'Личные данные', 'icon' => 'fa fa-users', 'url' => ['/owner/person/index']],
                    ['label' => 'Вопросы/Ответы', 'icon' => 'fa fa-money', 'url'=>['/owner/faq/index']],
                ],
            ]
        ) ?>
    </section>

</aside>
