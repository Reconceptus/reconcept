<?php

use modules\config\models\Config;
use yii\db\Migration;

/**
 * Class m200904_112757_create_search_position_module
 */
class m200904_112757_create_search_position_module extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('position_request', [
            'id'          => $this->bigPrimaryKey()->unsigned(),
            'query'       => $this->string(),
            'domain'      => $this->string(),
            'depth'       => $this->integer(),
            'last_result' => $this->integer(),
            'status'      => $this->smallInteger(1)->unsigned()->defaultValue(0),
            'created_at'  => $this->dateTime(),
            'updated_at'  => $this->dateTime()
        ]);

        $this->createTable('position_log', [
            'id'         => $this->bigPrimaryKey()->unsigned(),
            'request_id' => $this->bigInteger(),
            'query'      => $this->string(),
            'domain'     => $this->string(),
            'position'   => $this->integer(),
            'depth'      => $this->integer(),
            'status'     => $this->smallInteger(1)->unsigned()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $auth = Yii::$app->authManager;

        $position = $auth->createPermission('position');
        $position->description = 'Поисковые позиции';
        $auth->add($position);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $position);

        $this->insert('module', ['name' => 'position', 'title' => 'Позиции в поиске', 'icon' => 'commenting']);

        $id = $this->db->createCommand("SELECT id FROM module WHERE name='position' AND parent_id IS NULL")->queryScalar();
        $actionsModules = [
//            ['support', 'Поддержка', $id, 'user-circle'],
        ];
        $this->batchInsert('module', ['name', 'title', 'parent_id', 'icon'], $actionsModules);

        $configModuleId = $this->db->createCommand("SELECT id FROM module WHERE name='config' AND parent_id IS NULL")->queryScalar();
        $this->insert('module', ['name' => 'position', 'title' => 'Настройки получения позиции в поисковиках', 'parent_id' => $configModuleId, 'icon' => 'book']);


        $this->insert('config', ['slug' => 'position', 'name' => 'Настройки получения позиции в поисковиках']);
        $positionConfig = $this->db->createCommand("SELECT id FROM config WHERE slug='position'")->queryScalar();
        $params = [
            [$positionConfig, 'position_yandex_user', 'Пользователь яндекс', Config::TYPE_INPUT, '', 1],
            [$positionConfig, 'position_yandex_key', 'Ключ яндекс', Config::TYPE_INPUT, '', 2],
        ];
        $this->batchInsert('config', ['parent_id', 'slug', 'name', 'type', 'value', 'sort'], $params);

        $pos = $auth->createPermission('config_position');
        $pos->description = 'Настройки получения позиции в поисовиках';
        $auth->add($pos);

        $auth->addChild($admin, $pos);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('podition_request');
        $this->dropTable('podition_log');
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('position'));
        $id = $this->db->createCommand("SELECT id FROM module WHERE name='position' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $id]);
        $this->delete('module', ['and', ['name' => 'position'], ['id' => $id]]);

        $configModuleId = $this->db->createCommand("SELECT id FROM module WHERE name='config' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $configModuleId, 'name' => 'position']);

        $positionConfig = $this->db->createCommand("SELECT id FROM config WHERE slug='position'")->queryScalar();
        $this->delete('config', ['parent_id' => $positionConfig]);
        $this->delete('config', ['id' => $positionConfig, 'slug' => 'position']);

        $auth->remove($auth->getPermission('config_position'));
    }
}
