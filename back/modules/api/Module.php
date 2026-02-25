<?php

namespace app\modules\api;

use app\modules\api\repositories\ActiveRecordClientRepository;
use app\modules\api\repositories\ClientRepositoryInterface;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\api\controllers';

    public function init(): void
    {
        parent::init();

        \Yii::$container->set(ClientRepositoryInterface::class, ActiveRecordClientRepository::class);
    }
}
