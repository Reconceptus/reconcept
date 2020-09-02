<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\footer;

use modules\blog\models\Post;
use modules\config\models\Config;
use modules\services\models\Service;
use yii\base\Widget;


class Footer extends Widget
{
    /* @var $model Post */
    public $viewName = 'index';

    public function run()
    {
        $content = \Yii::$app->cache->getOrSet('contacts', function () {
            return $this->render($this->viewName, ['contacts' => self::getContacts(), 'services' => $this->getServices()]);
        }, 60 * 60 * 24);
        return $content;
    }

    public static function getContacts()
    {
        $contacts = Config::find()->alias('c')->select(['c.name', 'c.value'])
            ->innerJoin(Config::tableName() . ' c1', 'c.parent_id=c1.id')
            ->where(['c1.slug' => 'contacts'])->andWhere(['is', 'c1.parent_id', null])
            ->andWhere(['and', ['like', 'c.slug', 'social'], ['is not', 'c.value', null], ['!=', 'c.value', '']])->asArray()
            ->all();
        return $contacts;
    }

    public function getServices()
    {
        return Service::find()->select(['slug', 'name'])->where(['to_footer' => 1])->asArray()->all();
    }
}