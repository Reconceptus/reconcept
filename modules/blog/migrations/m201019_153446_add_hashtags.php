<?php

use yii\db\Migration;

/**
 * Class m201019_153446_add_hashtags
 */
class m201019_153446_add_hashtags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('blog_hashtag', [
            'id'               => $this->bigPrimaryKey()->unsigned(),
            'name'             => $this->string()->unique(),
            'number_of_clicks' => $this->integer()->defaultValue(0),
            'created_at'       => $this->dateTime()
        ]);

        $this->createTable('blog_hash_post', [
            'id'         => $this->bigPrimaryKey()->unsigned(),
            'post_id'    => $this->integer()->unsigned()->notNull(),
            'hashtag_id' => $this->bigInteger()->unsigned()->notNull()
        ]);
        $this->addForeignKey('fk_hash_post_post', 'blog_hash_post', 'post_id', 'blog_post', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_hash_post_hash', 'blog_hash_post', 'hashtag_id', 'blog_hashtag', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_hash_post_hash', 'blog_hash_post');
        $this->dropForeignKey('fk_hash_post_post', 'blog_hash_post');
        $this->dropTable('blog_hash_post');
        $this->dropTable('blog_hashtag');
    }
}
