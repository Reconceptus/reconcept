<?php

use yii\db\Migration;

/**
 * Class m190129_071015_create_portfolio
 */
class m190129_071015_create_portfolio extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // таблицы
        $this->createTable('portfolio_portfolio', [
            'id'                 => $this->primaryKey()->unsigned(),
            'name'               => $this->string(),
            'slug'               => $this->string(),
            'full_name'          => $this->string(),
            'description'        => $this->string(800),
            'alt'                => $this->string(),
            'url'                => $this->string(),
            'status'             => $this->smallInteger(1),
            'author_id'          => $this->integer()->unsigned(),
            'image'              => $this->string(),
            'horizontal_preview' => $this->string(),
            'vertical_preview'   => $this->string(),
            'content'            => $this->text(),
            'to_main'            => $this->smallInteger(1),
            'to_footer'          => $this->smallInteger(1),
            'seo_title'          => $this->string(),
            'seo_description'    => $this->string(),
            'created_at'         => $this->dateTime(),
            'updated_at'         => $this->dateTime()
        ]);

        $this->createTable('portfolio_tag', [
            'id'       => $this->primaryKey()->unsigned(),
            'name'     => $this->string(150),
            'sort'     => $this->integer(),
            'language' => $this->string(6)
        ]);

        $this->createTable('portfolio_portfolio_tag', [
            'id'           => $this->bigPrimaryKey()->unsigned(),
            'portfolio_id' => $this->integer()->unsigned(),
            'tag_id'       => $this->integer()->unsigned(),
        ]);

        $this->createTable('portfolio_review', [
            'id'           => $this->primaryKey()->unsigned(),
            'portfolio_id' => $this->integer()->unsigned(),
            'fio'          => $this->string(),
            'position'     => $this->string(),
            'image'        => $this->string(),
            'text'         => $this->text(),
            'to_main'      => $this->smallInteger(1)
        ]);

        $this->createTable('portfolio_hidden_tag', [
            'id'   => $this->primaryKey()->unsigned(),
            'name' => $this->string(150)
        ]);

        $this->createTable('portfolio_portfolio_hidden_tag', [
            'id'           => $this->bigPrimaryKey()->unsigned(),
            'portfolio_id' => $this->integer()->unsigned(),
            'tag_id'       => $this->integer()->unsigned(),
        ]);

        $this->addForeignKey(
            'fk_hidden_portfolio',
            'portfolio_portfolio_hidden_tag',
            'portfolio_id',
            'portfolio_portfolio',
            'id',
            'cascade', 'cascade');

        $this->addForeignKey(
            'fk_hidden_tag',
            'portfolio_portfolio_hidden_tag',
            'tag_id',
            'portfolio_hidden_tag',
            'id',
            'cascade', 'cascade');

        $this->addForeignKey(
            'fk_portfolio_author',
            'portfolio_portfolio',
            'author_id',
            'user',
            'id',
            'set null', 'cascade');

        $this->addForeignKey(
            'fk_review_portfolio',
            'portfolio_review',
            'portfolio_id',
            'portfolio_portfolio',
            'id',
            'set null', 'cascade');

        $this->addForeignKey(
            'fk_portfolio_portfolio',
            'portfolio_portfolio_tag',
            'portfolio_id',
            'portfolio_portfolio',
            'id',
            'cascade', 'cascade');

        $this->addForeignKey(
            'fk_portfolio_tag',
            'portfolio_portfolio_tag',
            'tag_id',
            'portfolio_tag',
            'id',
            'cascade', 'cascade');


        // модули
        $this->insert('module', ['name' => 'portfolio', 'title' => 'Портфолио', 'icon' => 'folder-open']);


        // разрешения
        $auth = Yii::$app->authManager;

        $port = $auth->createPermission('portfolio');
        $port->description = 'Портфолио';
        $auth->add($port);

        $portfolio = $auth->createPermission('portfolio_portfolio');
        $portfolio->description = 'Портфолио';
        $auth->add($portfolio);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $port);
        $auth->addChild($admin, $portfolio);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_portfolio_author', 'portfolio_portfolio');
        $this->dropForeignKey('fk_review_portfolio', 'portfolio_review');
        $this->dropForeignKey('fk_portfolio_portfolio', 'portfolio_portfolio_tag');
        $this->dropForeignKey('fk_portfolio_tag', 'portfolio_portfolio_tag');
        $this->dropForeignKey('fk_hidden_portfolio', 'portfolio_portfolio_hidden_tag');
        $this->dropForeignKey('fk_hidden_tag', 'portfolio_portfolio_hidden_tag');

        $this->dropTable('portfolio_portfolio_hidden_tag');
        $this->dropTable('portfolio_hidden_tag');
        $this->dropTable('portfolio_review');
        $this->dropTable('portfolio_portfolio_tag');
        $this->dropTable('portfolio_tag');
        $this->dropTable('portfolio_portfolio');

        $id = $this->db->createCommand("SELECT id FROM module WHERE name='portfolio' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $id]);
        $this->delete('module', ['and', ['name' => 'portfolio'], ['id' => $id]]);

        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('portfolio_portfolio'));
        $auth->remove($auth->getPermission('portfolio'));
    }
}
