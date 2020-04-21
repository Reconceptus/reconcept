<?php
/**
 * Created by PhpStorm.
 * User: adm
 * Date: 26.12.2018
 * Time: 16:22
 */

namespace modules\blog\models;

use paulzi\nestedsets\NestedSetsQueryTrait;

class BlogCategoryQuery extends \yii\db\ActiveQuery
{
    use NestedSetsQueryTrait;
}