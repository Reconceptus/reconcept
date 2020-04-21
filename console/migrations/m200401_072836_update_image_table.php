<?php

use yii\db\Migration;

/**
 * Class m200401_072836_update_image_table
 */
class m200401_072836_update_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('image', 'class_full', $this->string(200));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('image', 'class_full');
    }

}
