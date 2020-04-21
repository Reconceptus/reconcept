<?php

use yii\db\Migration;

/**
 * Class m181206_113622_create_blog_tables
 */
class m181206_113622_create_blog_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // таблицы
        $this->createTable('blog_category', [
            'id'              => $this->primaryKey()->unsigned(),
            'name'            => $this->string(100),
            'slug'            => $this->string(100),
            'description'     => $this->string(1000),
            'image'           => $this->string(),
            'status'          => $this->smallInteger(1)->notNull(),
            'sort'            => $this->integer()->defaultValue(200),
            'seo_title'       => $this->string(100),
            'seo_description' => $this->string(),
            'lft'             => $this->integer()->unsigned(),
            'rgt'             => $this->integer()->unsigned(),
            'depth'           => $this->integer()->unsigned(),
            'created_at'      => $this->dateTime(),
            'updated_at'      => $this->dateTime(),
        ]);

        $this->createTable('blog_post', [
            'id'             => $this->primaryKey()->unsigned(),
            'category_id'    => $this->integer()->unsigned(),
            'slug'           => $this->string()->notNull(),
            'name'           => $this->string()->notNull(),
            'intro'          => $this->string(1000)->notNull(),
            'text'           => $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext')->notNull(),
            'title'          => $this->string(),
            'keywords'       => $this->string(),
            'description'    => $this->string(),
            'views'          => $this->integer()->unsigned()->comment('Количество просмотров'),
            'author_id'      => $this->integer()->unsigned()->notNull(),
            'image_preview'  => $this->string(),
            'image'          => $this->string(),
            'created_at'     => $this->dateTime(),
            'updated_at'     => $this->dateTime(),
            'status'         => $this->smallInteger(1),
            'allow_comments' => $this->smallInteger(1),
            'sort'           => $this->integer(),
            'to_main'        => $this->boolean(),
            'to_letter'      => $this->boolean(),
        ]);

        $this->createTable('blog_tag', [
            'id'         => $this->primaryKey()->unsigned(),
            'name'       => $this->string(150),
            'slug'       => $this->string(160),
            'sort'       => $this->integer(),
            'language'   => $this->string(6),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createTable('blog_post_tag', [
            'id'      => $this->bigPrimaryKey()->unsigned(),
            'post_id' => $this->integer()->unsigned(),
            'tag_id'  => $this->integer()->unsigned(),
        ]);

        $this->createTable('blog_comment', [
            'id'         => $this->primaryKey()->unsigned(),
            'lft'        => $this->integer()->unsigned(),
            'rgt'        => $this->integer()->unsigned(),
            'depth'      => $this->integer(),
            'author_id'  => $this->integer()->unsigned(),
            'text'       => $this->string(2000),
            'name'       => $this->string(70),
            'email'      => $this->string(70),
            'accept'     => $this->smallInteger(1),
            'post_id'    => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'ip'         => $this->string(45),
            'status'     => $this->smallInteger()->defaultValue(1)
        ]);

        $this->createTable('blog_favorite', [
            'id'         => $this->primaryKey()->unsigned(),
            'user_id'    => $this->integer()->unsigned(),
            'post_id'    => $this->integer()->unsigned(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('FK_post_author', 'blog_post', 'author_id', 'user', 'id');
        $this->addForeignKey('FK_post_tag_post', 'blog_post_tag', 'post_id', 'blog_post', 'id', 'cascade', 'cascade');
        $this->addForeignKey('FK_post_tag_tag', 'blog_post_tag', 'tag_id', 'blog_tag', 'id', 'cascade', 'cascade');
        $this->addForeignKey('FK_comment_author', 'blog_comment', 'author_id', 'user', 'id');
        $this->addForeignKey('FK_comment_post', 'blog_comment', 'post_id', 'blog_post', 'id');
        $this->addForeignKey(
            'fk_blog_favorite_user',
            'blog_favorite',
            'user_id',
            'user',
            'id',
            'cascade',
            'cascade'
        );
        $this->addForeignKey(
            'fk_blog_favorite_post',
            'blog_favorite',
            'post_id',
            'blog_post',
            'id',
            'cascade',
            'cascade'
        );

        $this->createIndex('u_category_slug', 'blog_category', 'slug', true);
        $this->createIndex('I_post_author', 'blog_post', 'author_id');
        $this->createIndex('I_tag_lang', 'blog_tag', 'language');
        $this->createIndex('U_tag_name', 'blog_tag', 'name', true);


        // модули
        $this->insert('module', ['name' => 'blog', 'title' => 'Блог', 'icon' => 'book']);
        $id = $this->db->createCommand("SELECT id FROM module WHERE name='blog' AND parent_id IS NULL")->queryScalar();
        $blogModules = [
            ['category', 'Категории', $id, 'list'],
            ['post', 'Посты', $id, 'file-text'],
            ['comment', 'Комментарии', $id, 'comments'],
        ];
        $this->batchInsert('module', ['name', 'title', 'parent_id', 'icon'], $blogModules);


        // разрешения
        $auth = Yii::$app->authManager;

        $blog = $auth->createPermission('blog');
        $blog->description = 'Блог';
        $auth->add($blog);

        $categories = $auth->createPermission('blog_category');
        $categories->description = 'Категории';
        $auth->add($categories);

        $posts = $auth->createPermission('blog_post');
        $posts->description = 'Посты';
        $auth->add($posts);

        $comments = $auth->createPermission('blog_comment');
        $comments->description = 'Комментарии';
        $auth->add($comments);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $blog);
        $auth->addChild($admin, $categories);
        $auth->addChild($admin, $posts);
        $auth->addChild($admin, $comments);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_favorite_user', 'blog_favorite');
        $this->dropForeignKey('fk_blog_favorite_post', 'blog_favorite');
        $this->dropForeignKey('FK_post_tag_tag', 'blog_post_tag');
        $this->dropForeignKey('FK_post_tag_post', 'blog_post_tag');
        $this->dropForeignKey('FK_post_author', 'blog_post');
        $this->dropForeignKey('FK_comment_author', 'blog_comment');
        $this->dropForeignKey('FK_comment_post', 'blog_comment');
        $this->dropTable('blog_favorite');
        $this->dropTable('blog_comment');
        $this->dropTable('blog_post_tag');
        $this->dropTable('blog_tag');
        $this->dropTable('blog_post');
        $this->dropTable('blog_category');

        $id = $this->db->createCommand("SELECT id FROM module WHERE name='blog' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $id]);
        $this->delete('module', ['and', ['name' => 'blog'], ['id' => $id]]);

        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('blog_comment'));
        $auth->remove($auth->getPermission('blog_post'));
        $auth->remove($auth->getPermission('blog_category'));
        $auth->remove($auth->getPermission('blog'));
    }
}
