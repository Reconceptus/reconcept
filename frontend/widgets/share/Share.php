<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 20.08.2018
 * Time: 9:42
 */

namespace frontend\widgets\share;

use modules\utils\models\UtilsShare;
use yii\base\Widget;


class Share extends Widget
{
    public $viewName = 'index';

    public function run()
    {
        $url = \Yii::$app->request->getUrl();
        $model = UtilsShare::findOne(['url' => $url]);
        if (!$model) {
            $model = new UtilsShare(['url' => $url]);
        }
        $content = $this->render($this->viewName, ['model' => $model]);
        return $content;
    }
}