<?php

use yii\db\Migration;

/**
 * Handles the creation of tables for `utils` module.
 */
class m190118_105414_create_utils extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // создаем таблицы
        $this->createTable('utils_gallery_layout', [
            'id'     => $this->primaryKey()->unsigned(),
            'name'   => $this->string(150),
            'code'   => $this->string(150),
            'layout' => $this->text(),
            'item'   => $this->text()
        ]);

        $this->createTable('utils_gallery', [
            'id'        => $this->primaryKey()->unsigned(),
            'code'      => $this->string(150),
            'name'      => $this->string(150),
            'layout_id' => $this->integer()->unsigned()
        ]);

        $this->createTable('utils_block', [
            'id'      => $this->primaryKey(),
            'type'    => $this->string(10),
            'slug'    => $this->string(150),
            'name'    => $this->string(150),
            'content' => $this->text()
        ]);

        $this->createTable('utils_share', [
            'id'  => $this->primaryKey()->unsigned(),
            'url' => $this->string(),
            'vk'  => $this->integer()->defaultValue(0),
            'ok'  => $this->integer()->defaultValue(0),
            'fb'  => $this->integer()->defaultValue(0),
            'tw'  => $this->integer()->defaultValue(0),
            'ig'  => $this->integer()->defaultValue(0),
        ]);
        $this->createIndex('u_share_url', 'utils_share', 'url', true);

        $this->createTable('subscriber', [
            'id'      => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned(),
            'email'   => $this->string(),
            'status'  => $this->smallInteger(1)
        ]);

        $this->createTable('page', [
            'id'              => $this->primaryKey()->unsigned(),
            'slug'            => $this->string(),
            'created_at'      => $this->dateTime(),
            'updated_at'      => $this->dateTime(),
            'name'            => $this->string()->notNull(),
            'text'            => $this->text()->notNull(),
            'seo_title'       => $this->string(),
            'seo_description' => $this->string(),
        ]);

        $this->insert('module', ['name' => 'utils', 'title' => 'Утилиты', 'icon' => 'cube']);


        // добавляем в модули
        $id = $this->db->createCommand("SELECT id FROM module WHERE name='utils' AND parent_id IS NULL")->queryScalar();
        $utilsModules = [
            ['layout', 'Шаблоны галереи', $id, 'file-image-o'],
            ['gallery', 'Галереи', $id, 'picture-o'],
            ['block', 'Блоки', $id, 'cubes'],
            ['subscribe', 'Подписка', $id, 'envelope'],
            ['page', 'Статические страницы', $id, 'file-text'],
        ];
        $this->batchInsert('module', ['name', 'title', 'parent_id', 'icon'], $utilsModules);


        // разрешения
        $auth = Yii::$app->authManager;

        $utils = $auth->createPermission('utils');
        $utils->description = 'Утилиты';
        $auth->add($utils);

        $galleryLayout = $auth->createPermission('utils_layout');
        $galleryLayout->description = 'Шаблоны галереи';
        $auth->add($galleryLayout);

        $gallery = $auth->createPermission('utils_gallery');
        $gallery->description = 'Галереи';
        $auth->add($gallery);

        $block = $auth->createPermission('utils_block');
        $block->description = 'Блоки';
        $auth->add($block);

        $subscribe = $auth->createPermission('utils_subscribe');
        $subscribe->description = 'Подписка';
        $auth->add($subscribe);

        $page = $auth->createPermission('utils_page');
        $page->description = 'Статичные страницы';
        $auth->add($page);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $utils);
        $auth->addChild($admin, $galleryLayout);
        $auth->addChild($admin, $gallery);
        $auth->addChild($admin, $block);
        $auth->addChild($admin, $page);
        $auth->addChild($admin, $subscribe);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('utils_gallery');
        $this->dropTable('utils_gallery_layout');
        $this->dropTable('utils_block');
        $this->dropTable('utils_share');
        $this->dropTable('subscriber');
        $this->dropTable('page');

        $id = $this->db->createCommand("SELECT id FROM module WHERE name='utils' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $id]);
        $this->delete('module', ['and', ['name' => 'utils'], ['id' => $id]]);

        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('utils_subscribe'));
        $auth->remove($auth->getPermission('utils_page'));
        $auth->remove($auth->getPermission('utils_block'));
        $auth->remove($auth->getPermission('utils_gallery'));
        $auth->remove($auth->getPermission('utils_layout'));
        $auth->remove($auth->getPermission('utils'));
    }
}
