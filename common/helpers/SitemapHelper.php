<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 13.03.2019
 * Time: 15:23
 */

namespace common\helpers;


class SitemapHelper
{
    /**
     * Создает новый sitemap
     */
    public static function sitemap()
    {
        $items = [];
        $models = \Yii::$app->params['siteMapModels'];
        if ($models) {
            foreach ($models as $class)
                $items = array_merge($items, $class::find()->active()->all());
        }
        $str = self::generate($items);
        file_put_contents(\Yii::getAlias('@frontend') . '/web/sitemap.xml', $str);
    }

    /**
     * @param $items
     * @return string
     */
    public static function generate($items)
    {
        $host = \Yii::$app->params['front'];
        $str = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($items as $item) {
            $str .= "<url>
            <loc>" . $host . $item->getUrl() . "</loc>
            <lastmod>" . date(DATE_W3C, strtotime($item->updated_at)) . "</lastmod>
            <changefreq>weekly</changefreq>
            </url>";
        }
        $str .= '</urlset>';
        return $str;
    }
}