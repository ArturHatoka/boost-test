<?php

namespace app\modules\api\repositories;

interface ClientRepositoryInterface
{
    /**
     * @return array<int,array{id:int,name:string,state:string,status:string}>
     */
    public function findAll(int $limit, int $offset, ?string $status = null, ?string $state = null): array;

    /**
     * @return array{id:int,name:string,state:string,status:string}|null
     */
    public function findById(int $id): ?array;

    /**
     * @return array{id:int,name:string,state:string,status:string}
     */
    public function create(string $name, string $state, string $status): array;

    /**
     * @return array{id:int,name:string,state:string,status:string}|null
     */
    public function update(int $id, string $name, string $state, ?string $status = null): ?array;

    public function delete(int $id): bool;
}
