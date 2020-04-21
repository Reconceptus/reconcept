<?php

use yii\db\Migration;

class m190218_101323_add_services_module extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // таблицы
        $this->createTable('service_category', [
            'id'   => $this->primaryKey()->unsigned(),
            'name' => $this->string(),
            'slug' => $this->string()
        ]);

        $this->createTable('service', [
            'id'              => $this->primaryKey()->unsigned(),
            'category_id'     => $this->integer()->unsigned(),
            'slug'            => $this->string(),
            'created_at'      => $this->dateTime(),
            'updated_at'      => $this->dateTime(),
            'name'            => $this->string()->notNull(),
            'image'           => $this->string(),
            'text'            => $this->text()->notNull(),
            'status'          => $this->smallInteger(1),
            'to_footer'       => $this->boolean(),
            'to_menu'         => $this->boolean(),
            'seo_title'       => $this->string(),
            'seo_description' => $this->string(),
        ]);


        // модуль
        $this->insert('module', ['name' => 'services', 'title' => 'Услуги', 'icon' => 'code']);

        $id = $this->db->createCommand("SELECT id FROM module WHERE name='services' AND parent_id IS NULL")->queryScalar();
        $servicesModules = [
            ['service', 'Услуги', $id, 'desktop'],
            ['category', 'Категория услуг', $id, 'list'],
        ];
        $this->batchInsert('module', ['name', 'title', 'parent_id', 'icon'], $servicesModules);


        // разрешения
        $auth = Yii::$app->authManager;

        $services = $auth->createPermission('services');
        $services->description = 'Услуги';
        $auth->add($services);

        $service = $auth->createPermission('services_service');
        $service->description = 'Услуги';
        $auth->add($service);

        $category = $auth->createPermission('services_category');
        $category->description = 'Категории услуг';
        $auth->add($category);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $services);
        $auth->addChild($admin, $service);
        $auth->addChild($admin, $category);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $id = $this->db->createCommand("SELECT id FROM module WHERE name='services' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $id]);
        $this->delete('module', ['and', ['name' => 'services'], ['id' => $id]]);
        $this->dropTable('service');
        $this->dropTable('service_category');
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('services_category'));
        $auth->remove($auth->getPermission('services_service'));
        $auth->remove($auth->getPermission('services'));
    }
}
