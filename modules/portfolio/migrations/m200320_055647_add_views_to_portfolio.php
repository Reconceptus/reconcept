<?php

use yii\db\Migration;

/**
 * Class m200320_055647_add_views_to_portfolio
 */
class m200320_055647_add_views_to_portfolio extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('portfolio_portfolio', 'views', $this->integer()->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('portfolio_portfolio', 'views');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_055647_add_views_to_portfolio cannot be reverted.\n";

        return false;
    }
    */
}
