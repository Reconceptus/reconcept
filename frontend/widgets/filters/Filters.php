<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace modules\shop\widgets\filters;

use modules\shop\models\Catalog;
use modules\shop\models\CatalogItem;
use modules\shop\models\Item;
use modules\shop\models\ItemOption;
use modules\shop\models\Sku;
use yii\base\Widget;
use yii\db\Query;

class Filters extends Widget
{
    public $viewName = 'index';
    public $category;

    public function run()
    {
        $query1 = new Query();
        $query1->distinct(true)
            ->select('cat.id')
            ->from(Catalog::tableName() . ' cat')
            ->innerJoin(CatalogItem::tableName() . ' ci', 'cat.id = ci.catalog_id')
            ->innerJoin(ItemOption::tableName() . ' io', 'cat.id = io.catalog_id and ci.id = io.catalog_item_id and io.type=' . ItemOption::TYPE_ITEM)
            ->innerJoin(Item::tableName() . ' i', 'i.id = io.item_id and i.category_id =' . $this->category->id)
            ->where(['i.status' => Item::STATUS_ACTIVE])
            ->andWhere(['or', 'cat.category_id IS NULL', 'cat.category_id = ' . $this->category->id]);

        $query2 = new Query();
        $query2->distinct(true)
            ->select('cat.id')
            ->from(Catalog::tableName() . ' cat')
            ->innerJoin(CatalogItem::tableName() . ' ci', 'cat.id = ci.catalog_id')
            ->innerJoin(ItemOption::tableName() . ' io', 'cat.id = io.catalog_id and ci.id = io.catalog_item_id and io.type=' . ItemOption::TYPE_SKU)
            ->innerJoin(Sku::tableName() . ' sku', 'sku.id = io.item_id')
            ->innerJoin(Item::tableName() . ' i', 'sku.item_id = i.id and i.category_id =' . $this->category->id)
            ->where(['i.status' => Item::STATUS_ACTIVE])
            ->andWhere(['sku.status' => Sku::STATUS_ACTIVE])
            ->andWhere(['or', 'cat.category_id IS NULL', 'cat.category_id = ' . $this->category->id]);

        $query = $query1->union($query2);
        $ids = [-1];

        $dbIds = $query->createCommand()->queryAll();
        foreach ($dbIds as $id) {
            $ids[] = $id['id'];
        }

        $filters = Catalog::find()
            ->distinct(true)
            ->where(['filter' => 1])
            ->andWhere(['in', 'id', $dbIds])
            ->orderBy(['sort' => SORT_ASC])
            ->all();

        $content = $this->render($this->viewName, [
            'filters'  => $filters,
            'category' => $this->category
        ]);
        return $content;
    }
}