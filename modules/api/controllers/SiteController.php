<?php

namespace app\modules\api\controllers;

use app\modules\api\components\RestController;
use yii\web\NotFoundHttpException;

/**
 * Class SiteController
 * @package app\modules\api\controllers
 */
class SiteController extends RestController
{
    /**
     * @return array
     */
    public function actionError()
    {
        return $this->renderResult(new NotFoundHttpException());
    }
}
