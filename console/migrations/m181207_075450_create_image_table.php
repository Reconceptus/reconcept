<?php

use yii\db\Migration;

/**
 * Handles the creation of table `image`.
 */
class m181207_075450_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('image', [
            'id'      => $this->primaryKey()->unsigned(),
            'class'   => $this->string(100),
            'item_id' => $this->integer()->unsigned(),
            'image'   => $this->string(150),
            'thumb'   => $this->string(150),
            'alt'     => $this->string(),
            'sort'    => $this->integer(),
            'is_main' => $this->smallInteger(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image');
    }
}
