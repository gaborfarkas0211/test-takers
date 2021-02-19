<?php

namespace app\modules\api\controllers;

use Yii;
use app\modules\api\components\RestController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\Result;
use League\Csv\Reader;

/**
 * Class FileUploadController
 * @package app\modules\api\controllers
 */
class FileUploadController extends RestController
{
    /**
     * @return array
     */
    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs["index"] = ['POST'];
        return $verbs;
    }

    /**
     * @return array|\Exception|\Throwable
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \League\Csv\Exception
     */
    public function actionIndex()
    {
        if ($file = UploadedFile::getInstanceByName('file')) {
            if ($file->type !== 'application/vnd.ms-excel') {
                throw new BadRequestHttpException('Invalid file type.');
            }

            if ($file->saveAs('../uploads/' . $file->name)) {
                $csv = Reader::createFromPath('../uploads/' . $file->name)->setHeaderOffset(0);
                $records = [];
                foreach ($csv as $record) {
                    $model = new Result();
                    $model->attributes = $record;
                    if (!$model->validate()) {
                        continue;
                    }
                    $records[] = $model;
                }

                try {
                    Yii::$app->db->createCommand()->batchInsert(Result::tableName(), ['id', 'testTaker', 'correctAnswers', 'incorrectAnswers'], $records)->execute();
                } catch (\Throwable $th) {
                    return $th;
                }
                return $this->renderResult([], 'Csv uploaded successful.');
            }
        }
        throw new NotFoundHttpException('The csv could not be uploaded.');
    }
}
