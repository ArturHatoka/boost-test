<?php

namespace tests\unit\modules\api\services;

use app\models\Client;
use app\modules\api\exceptions\ClientNotFoundException;
use app\modules\api\repositories\ClientRepositoryInterface;
use app\modules\api\services\ClientService;

class ClientServiceTest extends \Codeception\Test\Unit
{
    public function testListReturnsDtosWithFilters(): void
    {
        $service = new ClientService($this->makeRepository([
            ['id' => 1, 'name' => 'Acme', 'state' => 'CA', 'status' => Client::STATUS_ACTIVE],
            ['id' => 2, 'name' => 'Globex', 'state' => 'NY', 'status' => Client::STATUS_INACTIVE],
            ['id' => 3, 'name' => 'Initech', 'state' => 'CA', 'status' => Client::STATUS_ACTIVE],
        ]));

        $items = $service->list(10, 0, Client::STATUS_ACTIVE, 'CA');

        verify(count($items))->equals(2);
        verify($items[0]->id)->equals(1);
        verify($items[1]->id)->equals(3);
    }

    public function testViewThrowsWhenClientDoesNotExist(): void
    {
        $service = new ClientService($this->makeRepository());

        $this->expectException(ClientNotFoundException::class);
        $service->view(999);
    }

    public function testCreateReturnsCreatedClientDto(): void
    {
        $service = new ClientService($this->makeRepository());

        $created = $service->create('Acme', 'CA', Client::STATUS_ACTIVE);

        verify($created->id)->equals(1);
        verify($created->name)->equals('Acme');
        verify($created->state)->equals('CA');
        verify($created->status)->equals(Client::STATUS_ACTIVE);
    }

    public function testUpdateThrowsWhenClientDoesNotExist(): void
    {
        $service = new ClientService($this->makeRepository());

        $this->expectException(ClientNotFoundException::class);
        $service->update(777, 'Acme', 'CA', Client::STATUS_ACTIVE);
    }

    public function testDeleteThrowsWhenClientDoesNotExist(): void
    {
        $service = new ClientService($this->makeRepository());

        $this->expectException(ClientNotFoundException::class);
        $service->delete(777);
    }

    /**
     * @param array<int,array{id:int,name:string,state:string,status:string}> $seedRows
     */
    private function makeRepository(array $seedRows = []): ClientRepositoryInterface
    {
        return new class($seedRows) implements ClientRepositoryInterface {
            /** @var array<int,array{id:int,name:string,state:string,status:string}> */
            private array $rows = [];
            private int $nextId = 1;

            /**
             * @param array<int,array{id:int,name:string,state:string,status:string}> $seedRows
             */
            public function __construct(array $seedRows)
            {
                $this->rows = array_values($seedRows);
                if ($this->rows !== []) {
                    $ids = array_column($this->rows, 'id');
                    $this->nextId = (int) max($ids) + 1;
                }
            }

            public function findAll(int $limit, int $offset, ?string $status = null, ?string $state = null): array
            {
                $rows = array_filter($this->rows, static function (array $row) use ($status, $state): bool {
                    if ($status !== null && $status !== '' && $row['status'] !== $status) {
                        return false;
                    }

                    if ($state !== null && $state !== '' && $row['state'] !== $state) {
                        return false;
                    }

                    return true;
                });

                return array_values(array_slice($rows, $offset, $limit));
            }

            public function findById(int $id): ?array
            {
                foreach ($this->rows as $row) {
                    if ($row['id'] === $id) {
                        return $row;
                    }
                }

                return null;
            }

            public function create(string $name, string $state, string $status): array
            {
                $row = [
                    'id' => $this->nextId++,
                    'name' => $name,
                    'state' => $state,
                    'status' => $status,
                ];

                $this->rows[] = $row;

                return $row;
            }

            public function update(int $id, string $name, string $state, ?string $status = null): ?array
            {
                foreach ($this->rows as $index => $row) {
                    if ($row['id'] !== $id) {
                        continue;
                    }

                    $updated = $row;
                    $updated['name'] = $name;
                    $updated['state'] = $state;
                    if ($status !== null && $status !== '') {
                        $updated['status'] = $status;
                    }

                    $this->rows[$index] = $updated;

                    return $updated;
                }

                return null;
            }

            public function delete(int $id): bool
            {
                foreach ($this->rows as $index => $row) {
                    if ($row['id'] !== $id) {
                        continue;
                    }

                    unset($this->rows[$index]);
                    $this->rows = array_values($this->rows);

                    return true;
                }

                return false;
            }
        };
    }
}
