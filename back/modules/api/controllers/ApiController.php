<?php

namespace app\modules\api\controllers;

use Yii;
use yii\base\Model;
use yii\rest\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        return $behaviors;
    }

    protected function validationError(Model $model): array
    {
        Yii::$app->response->setStatusCode(422);

        return [
            'errors' => $model->getErrors(),
        ];
    }
}
