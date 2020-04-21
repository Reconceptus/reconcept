<?php
/**
 * Created by PhpStorm.
 * User: adm
 * Date: 11.12.2018
 * Time: 22:11
 */

namespace modules\blog\models;

use paulzi\nestedsets\NestedSetsQueryTrait;

class CommentQuery extends \yii\db\ActiveQuery
{
    use NestedSetsQueryTrait;
}