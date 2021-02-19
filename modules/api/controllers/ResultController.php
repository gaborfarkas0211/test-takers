<?php

namespace app\modules\api\controllers;

use app\models\Result;
use app\modules\api\components\RestController;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ResultController
 * @package app\modules\api\controllers
 */
class ResultController extends RestController
{
    /**
     * @return array
     */
    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs["index"] = ["GET"];
        $verbs["create"] = ['POST'];
        $verbs["update"] = ['POST'];
        $verbs["delete"] = ['DELETE'];
        return $verbs;
    }

    /**
     * @return array
     */
    public function actionCreate()
    {
        $model = new Result();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->renderResult($model, "Result successfully uploaded.");
            }
        }
        Yii::$app->response->setStatusCode(400);
        return $this->renderResult($model->getErrors());
    }

    /**
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = Result::findOne($id);
        if ($model && $model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                return $this->renderResult($model, "Result successfully updated.");
            }
            Yii::$app->response->setStatusCode(400);
            return $this->renderResult($model->getErrors());
        }
        throw new NotFoundHttpException('This result is not found.');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionIndex()
    {
        return Result::find()->all();
    }

    /**
     * @param int $id
     * @return array
     * @throws BadRequestHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $model = Result::findOne($id);
        if ($model && $model->delete()) {
            return $this->renderResult([], "Result deleted successfully.");
        }
        throw new BadRequestHttpException('Result could not delete.');
    }
}
