<?php

use yii\db\Migration;

/**
 * Class m200325_092414_add_image_type_field
 */
class m200325_092414_add_image_type_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('image', 'type', $this->smallInteger()->unsigned()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('image', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200325_092414_add_image_type_field cannot be reverted.\n";

        return false;
    }
    */
}
