<?php

use modules\config\models\Config;
use yii\db\Migration;

/**
 * Class m181220_080213_add_blog_config
 */
class m181220_080213_add_blog_config extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $configModuleId = $this->db->createCommand("SELECT id FROM module WHERE name='config' AND parent_id IS NULL")->queryScalar();
        $this->insert('module', ['name' => 'blog', 'title' => 'Настройки блога', 'parent_id' => $configModuleId, 'icon' => 'book']);


        $this->insert('config', ['slug' => 'blog', 'name' => 'Настройки блога']);
        $blogConfig = $this->db->createCommand("SELECT id FROM config WHERE slug='blog'")->queryScalar();
        $params = [
            [$blogConfig, 'pre_moderate_comments', 'Премодерация комментариев', Config::TYPE_CHECKBOX, 1, 1],
            [$blogConfig, 'insert_galleries', 'Вставлять галлереи', Config::TYPE_CHECKBOX, 0, 2],
        ];
        $this->batchInsert('config', ['parent_id', 'slug', 'name', 'type', 'value', 'sort'], $params);


        $auth = Yii::$app->authManager;
        $blog = $auth->createPermission('config_blog');
        $blog->description = 'Настройки блога';
        $auth->add($blog);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $blog);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $configModuleId = $this->db->createCommand("SELECT id FROM module WHERE name='config' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $configModuleId, 'name' => 'blog']);

        $blogConfig = $this->db->createCommand("SELECT id FROM config WHERE slug='blog'")->queryScalar();
        $this->delete('config', ['parent_id' => $blogConfig]);
        $this->delete('config', ['id' => $blogConfig, 'slug' => 'blog']);

        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('config_blog'));
    }
}
