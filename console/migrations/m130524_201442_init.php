<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                   => $this->primaryKey()->unsigned(),
            'username'             => $this->string()->notNull()->unique(),
            'role'                 => $this->string(30)->defaultValue('user'),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email'                => $this->string()->notNull()->unique(),
            'last_name'            => $this->string(),
            'first_name'           => $this->string(),
            'patronymic'           => $this->string(),
            'fio'                  => $this->string(),
            'image'                => $this->string(),
            'phone'                => $this->string(),
            'country'              => $this->string(),
            'city'                 => $this->string(),
            'address'              => $this->string(),
            'type'                 => $this->smallInteger(1),
            'organization'         => $this->string(),
            'position'             => $this->string(),
            'status'               => $this->smallInteger()->notNull()->defaultValue(10),
            'sex'                  => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('profile', [
            'id'      => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()
        ]);
        $this->addForeignKey(
            'fk_profile_user_id',
            'profile',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_profile_user_id', 'profile');
        $this->dropTable('profile');
        $this->dropTable('{{%user}}');
    }
}
