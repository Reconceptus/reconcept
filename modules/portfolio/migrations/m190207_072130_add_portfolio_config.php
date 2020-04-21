<?php

use modules\config\models\Config;
use yii\db\Migration;

/**
 * Class m190207_072130_add_portfolio_config
 */
class m190207_072130_add_portfolio_config extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $configModuleId = $this->db->createCommand("SELECT id FROM module WHERE name='config' AND parent_id IS NULL")->queryScalar();
        $this->insert('module', ['name' => 'portfolio', 'title' => 'Настройки портфолио', 'parent_id' => $configModuleId, 'icon' => 'folder-open']);

        $this->insert('config', ['slug' => 'portfolio', 'name' => 'Настройки портфолио']);
        $portfolioConfig = $this->db->createCommand("SELECT id FROM config WHERE slug='portfolio'")->queryScalar();
        $params = [
            [$portfolioConfig, 'portfolio_index_title', 'Заголовок страницы портфолио', Config::TYPE_INPUT, '', 1],
            [$portfolioConfig, 'portfolio_index_seo_title', 'Seo title страницы портфолио', Config::TYPE_INPUT, '', 1],
            [$portfolioConfig, 'portfolio_index_seo_description', 'Seo description страницы портфолио', Config::TYPE_INPUT, '', 1],
        ];
        $this->batchInsert('config', ['parent_id', 'slug', 'name', 'type', 'value', 'sort'], $params);

        $auth = Yii::$app->authManager;
        $portfolio = $auth->createPermission('config_portfolio');
        $portfolio->description = 'Настройки портфолио';
        $auth->add($portfolio);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $portfolio);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $configModuleId = $this->db->createCommand("SELECT id FROM module WHERE name='config' AND parent_id IS NULL")->queryScalar();
        $this->delete('module', ['parent_id' => $configModuleId, 'name' => 'portfolio']);

        $portfolioConfig = $this->db->createCommand("SELECT id FROM config WHERE slug='portfolio'")->queryScalar();
        $this->delete('config', ['parent_id' => $portfolioConfig]);
        $this->delete('config', ['id' => $portfolioConfig, 'slug' => 'portfolio']);

        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('config_portfolio'));
    }
}
