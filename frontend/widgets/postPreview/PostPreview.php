<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\postPreview;

use yii\base\Widget;

class PostPreview extends Widget
{
    public $viewName = 'index';
    public $favorites = [];
    public $model = null;

    public function run()
    {
        if (!$this->model) {
            return '';
        }
        $content = $this->render($this->viewName, ['model' => $this->model, 'favorites' => $this->favorites]);
        return $content;
    }
}