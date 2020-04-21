<?php

use yii\db\Migration;

/**
 * Class m190301_070348_add_main_page
 */
class m190301_070348_add_main_page extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('main_page_top', [
            'id'            => $this->primaryKey(),
            'image'         => $this->string(),
            'image_preview' => $this->string(),
            'quote'         => $this->string(500),
            'sign'          => $this->string(500)
        ]);

        $this->createTable('main_page_pages', [
            'id'   => $this->primaryKey(),
            'text' => $this->text(),
            'sort' => $this->integer()
        ]);
        $this->insert('main_page_pages', ['text' => 'Текст', 'sort' => 1]);


        // модули
        $this->insert('module', ['name' => 'mainpage', 'title' => 'Главная страница', 'icon' => 'bank']);
        $id = $this->db->createCommand("SELECT id FROM module WHERE name='mainpage' AND parent_id IS NULL")->queryScalar();
        $servicesModules = [
            ['top', 'Блок сверху', $id, 'picture-o'],
            ['logos', 'Блок логотипов', $id, 'file-image-o'],
        ];
        $this->batchInsert('module', ['name', 'title', 'parent_id', 'icon'], $servicesModules);

        // разрешения
        $auth = Yii::$app->authManager;
        $main = $auth->createPermission('mainpage');
        $main->description = 'Главная страница';
        $auth->add($main);

        $top = $auth->createPermission('mainpage_top');
        $top->description = 'Блок сверху';
        $auth->add($top);

        $logo = $auth->createPermission('mainpage_logos');
        $logo->description = 'Блок логотипов';
        $auth->add($logo);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $main);
        $auth->addChild($admin, $top);
        $auth->addChild($admin, $logo);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('main_page_top');
        $this->dropTable('main_page_pages');

        $id = $this->db->createCommand("SELECT id FROM module WHERE name='mainpage' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $id]);
        $this->delete('module', ['and', ['name' => 'mainpage'], ['id' => $id]]);

        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('mainpage_logos'));
        $auth->remove($auth->getPermission('mainpage_top'));
        $auth->remove($auth->getPermission('mainpage'));
    }
}
