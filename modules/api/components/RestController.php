<?php

namespace app\modules\api\components;

use yii\helpers\ArrayHelper;
use yii\rest\Controller;

/**
 * Class RestController
 * @package app\modules\api\components
 */
class RestController extends Controller
{

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
            ],
        ];
    }

    /**
     * @param array $data
     * @param null $message
     * @return array
     */
    public function renderResult($data = [], $message = null)
    {
        $result = [];
        $result["message"] = $message;
        $array = $data;
        if (is_object($data)) {
            $array = ArrayHelper::toArray($data);
        }
        return ArrayHelper::merge($result, $array);
    }
}
