<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Статистика', 'icon' => 'fa fa-pie-chart', 'url' => ['/admin/default/index']],
                    ['label' => 'Настройки', 'icon' => 'fa fa-cog', 'url' => ['/admin/user/update/'.Yii::$app->user->getId()]],
                    ['label' => 'Настройки сайта', 'icon' => 'fa fa-cog', 'url' => ['/admin/config/update/1']],
                    ['label' => 'Пользователи', 'icon' => 'fa fa-users', 'url' => ['/admin/user/index']],
                    [
                        'label' => 'Объекты',
                        'icon' => 'fa fa-file-text',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Администраторы объектов', 'icon' => 'fa fa-users', 'url' => ['/admin/person/index']],
                            ['label' => 'Объекты', 'icon' => 'fa fa-object-group', 'url' => ['/admin/object/index']],
                            ['label' => 'Сервисы', 'icon' => 'fa fa-shield', 'url' => ['/admin/servis/index']],
                            ['label' => 'Свойства объектов', 'icon' => 'fa fa-asterisk', 'url' => ['/admin/field/index']],
                            ['label' => 'Бронирования', 'icon' => 'fa fa-address-book', 'url' => ['/admin/booking/index']],
                            ['label' => 'Отзывы', 'icon' => 'fa fa-comments-o', 'url' => ['/admin/review/index']],
                            ['label' => 'Коэффициенты', 'icon' => 'fa fa-calculator', 'url' => ['/admin/coefficient/index']],
                            ['label' => 'Рекламные показы', 'icon' => 'fa fa-newspaper-o', 'url' => ['/admin/rp/index']],
                            ['label' => 'Тарифы', 'icon' => 'fa fa-text-width', 'url' => ['/admin/tarif/index']],
                            ['label' => 'Финансы', 'icon' => 'fa fa-calculator', 'url' => ['/admin/finance/index']],
                        ],
                    ],

                    [
                        'label' => 'Контент',
                        'icon' => 'fa fa-file-text',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Материалы', 'icon' => 'fa fa-tags', 'url' => ['/admin/post/index']],
                        ],
                    ],
                    ['label' => 'Вопросы/ответы', 'icon' => 'fa fa-question-circle-o', 'url' => ['/admin/faq/index']],
                ],
            ]
        ) ?>

    </section>

</aside>
