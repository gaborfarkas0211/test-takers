<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays homepage. 
     *
     * @return string س
     */
    public function actionIndex()
    {
       return $this->render('index');
    }
 
}