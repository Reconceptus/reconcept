<?php

use modules\portfolio\models\Portfolio;
use yii\db\Migration;

/**
 * Class m200306_083634_add_portfolio_sort
 */
class m200306_083634_add_portfolio_sort extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('portfolio_portfolio', 'sort', $this->integer());
        $models = Portfolio::find()->all();
        foreach ($models as $k => $model) {
            $model->sort = $k + 1;
            $model->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('portfolio_portfolio', 'sort');
    }
}
