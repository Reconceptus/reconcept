<?php

use yii\db\Migration;

/**
 * Class m190514_061415_create_feedback
 */
class m190514_061415_create_feedback extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('feedback_support', [
            'id'         => $this->bigPrimaryKey(),
            'url'        => $this->string(),
            'name'       => $this->string(),
            'email'      => $this->string(),
            'phone'      => $this->string(),
            'contact'    => $this->string(),
            'file'       => $this->string(),
            'message'    => $this->string(1000),
            'answer'     => $this->text(),
            'status'     => $this->smallInteger(0)->unsigned()->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);
        $auth = Yii::$app->authManager;

        $feedback = $auth->createPermission('feedback');
        $feedback->description = 'Работа с обращениями';
        $auth->add($feedback);

        $support = $auth->createPermission('feedback_support');
        $support->description = 'Заявки';
        $auth->add($support);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $feedback);
        $auth->addChild($admin, $support);

        $this->insert('module', ['name' => 'feedback', 'title' => 'Заявки', 'icon' => 'commenting']);

        $id = $this->db->createCommand("SELECT id FROM module WHERE name='feedback' AND parent_id IS NULL")->queryScalar();
        $actionsModules = [
            ['support', 'Поддержка', $id, 'user-circle'],
        ];
        $this->batchInsert('module', ['name', 'title', 'parent_id', 'icon'], $actionsModules);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('feedback_support');
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('feedback_support'));
        $auth->remove($auth->getPermission('feedback'));
        $id = $this->db->createCommand("SELECT id FROM module WHERE name='feedback' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $id]);
        $this->delete('module', ['and', ['name' => 'feedback'], ['id' => $id]]);
    }
}
