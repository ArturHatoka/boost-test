<?php

namespace tests\unit\models;

use app\models\Client;

class ClientTest extends \Codeception\Test\Unit
{
    private function makeModel(array $attributes = []): Client
    {
        return new class($attributes) extends Client {
            public function attributes(): array
            {
                return ['id', 'name', 'state', 'status'];
            }
        };
    }

    public function testValidationRequiresNameAndState(): void
    {
        $model = $this->makeModel([
            'status' => Client::STATUS_ACTIVE,
        ]);

        verify($model->validate())->false();
        verify($model->errors)->arrayHasKey('name');
        verify($model->errors)->arrayHasKey('state');
    }

    public function testStatusDefaultsToActive(): void
    {
        $model = $this->makeModel([
            'name' => 'Acme',
            'state' => 'California',
        ]);

        verify($model->validate())->true();
        verify($model->status)->equals(Client::STATUS_ACTIVE);
    }

    public function testValidationRejectsInvalidStatus(): void
    {
        $model = $this->makeModel([
            'name' => 'Acme',
            'state' => 'California',
            'status' => 'Archived',
        ]);

        verify($model->validate())->false();
        verify($model->errors)->arrayHasKey('status');
    }

    public function testValidationAcceptsInactiveStatus(): void
    {
        $model = $this->makeModel([
            'name' => 'Acme',
            'state' => 'California',
            'status' => Client::STATUS_INACTIVE,
        ]);

        verify($model->validate())->true();
    }

    public function testFieldsList(): void
    {
        $model = $this->makeModel();

        verify($model->fields())->equals(['id', 'name', 'state', 'status']);
    }
}
