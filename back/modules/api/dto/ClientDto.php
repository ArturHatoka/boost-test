<?php

namespace app\modules\api\dto;

final class ClientDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $state,
        public readonly string $status
    ) {
    }

    /**
     * @param array{id:int,name:string,state:string,status:string} $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['name'],
            (string) $row['state'],
            (string) $row['status']
        );
    }
}
