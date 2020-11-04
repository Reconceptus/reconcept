<?php

use yii\db\Migration;

/**
 * Class m201103_143712_add_views_to_services
 */
class m201103_143712_add_views_to_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service', 'views', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('service', 'views');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201103_143712_add_views_to_services cannot be reverted.\n";

        return false;
    }
    */
}
