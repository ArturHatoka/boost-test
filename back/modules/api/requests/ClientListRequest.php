<?php

namespace app\modules\api\requests;

use app\models\Client;
use yii\base\Model;

class ClientListRequest extends Model
{
    public int $limit = 100;
    public int $offset = 0;
    public ?string $status = null;
    public ?string $state = null;

    public function rules(): array
    {
        return [
            [['limit', 'offset'], 'integer', 'min' => 0],
            [['limit'], 'integer', 'max' => 500],
            [['state', 'status'], 'trim'],
            [['state'], 'string', 'max' => 128],
            [['status'], 'in', 'range' => [Client::STATUS_ACTIVE, Client::STATUS_INACTIVE]],
        ];
    }
}
