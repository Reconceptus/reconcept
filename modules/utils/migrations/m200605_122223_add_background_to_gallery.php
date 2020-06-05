<?php

use yii\db\Migration;

/**
 * Class m200605_122223_add_background_to_gallery
 */
class m200605_122223_add_background_to_gallery extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('utils_gallery', 'background',$this->string(10)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('utils_gallery', 'background');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200605_122223_add_background_to_gallery cannot be reverted.\n";

        return false;
    }
    */
}
