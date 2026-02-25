<?php

namespace app\modules\api\controllers;

use app\models\Client;
use yii\rest\ActiveController;
use yii\web\Response;

class ClientController extends ActiveController
{
    public $modelClass = Client::class;

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        return $behaviors;
    }
}
