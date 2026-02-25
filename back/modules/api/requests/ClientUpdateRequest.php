<?php

namespace app\modules\api\requests;

use app\models\Client;
use yii\base\Model;

class ClientUpdateRequest extends Model
{
    public string $name = '';
    public string $state = '';
    public ?string $status = null;

    public function rules(): array
    {
        return [
            [['name', 'state'], 'required'],
            [['name', 'state', 'status'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['state'], 'string', 'max' => 128],
            [['status'], 'in', 'range' => [Client::STATUS_ACTIVE, Client::STATUS_INACTIVE]],
        ];
    }
}
