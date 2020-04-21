<?php

use backend\helpers\MenuHelper;
use common\models\User;

$user = User::getUser();
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $user->getAvatar() ?>"
                     class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->fio ?></p>
            </div>
        </div>
        <?php
        $items = MenuHelper::getMenu();
        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items'   => $items
            ]
        ) ?>
    </section>
</aside>
