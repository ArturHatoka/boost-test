<?php

namespace app\modules\api\services;

use app\modules\api\dto\ClientDto;
use app\modules\api\exceptions\ClientNotFoundException;
use app\modules\api\repositories\ClientRepositoryInterface;

class ClientService
{
    public function __construct(
        private readonly ClientRepositoryInterface $clients
    ) {
    }

    /**
     * @return ClientDto[]
     */
    public function list(int $limit, int $offset, ?string $status = null, ?string $state = null): array
    {
        $rows = $this->clients->findAll($limit, $offset, $status, $state);

        return array_map([ClientDto::class, 'fromRow'], $rows);
    }

    public function view(int $id): ClientDto
    {
        $row = $this->clients->findById($id);
        if ($row === null) {
            throw new ClientNotFoundException('Client not found.');
        }

        return ClientDto::fromRow($row);
    }

    public function create(string $name, string $state, string $status): ClientDto
    {
        $row = $this->clients->create($name, $state, $status);

        return ClientDto::fromRow($row);
    }

    public function update(int $id, string $name, string $state, ?string $status = null): ClientDto
    {
        $row = $this->clients->update($id, $name, $state, $status);
        if ($row === null) {
            throw new ClientNotFoundException('Client not found.');
        }

        return ClientDto::fromRow($row);
    }

    public function delete(int $id): void
    {
        if ($this->clients->delete($id)) {
            return;
        }

        throw new ClientNotFoundException('Client not found.');
    }
}
