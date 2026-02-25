<?php

namespace app\modules\api\controllers;

use yii\rest\Controller;

class ClientController extends Controller
{
    public function actionIndex(): array
    {
        return [
            'message' => 'Client API is configured',
        ];
    }
}
