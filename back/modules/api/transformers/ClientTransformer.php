<?php

namespace app\modules\api\transformers;

use app\modules\api\dto\ClientDto;

class ClientTransformer
{
    /**
     * @return array{id:int,name:string,state:string,status:string}
     */
    public function transform(ClientDto $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'state' => $dto->state,
            'status' => $dto->status,
        ];
    }

    /**
     * @param ClientDto[] $collection
     * @return array<int,array{id:int,name:string,state:string,status:string}>
     */
    public function transformCollection(array $collection): array
    {
        return array_map([$this, 'transform'], $collection);
    }
}
