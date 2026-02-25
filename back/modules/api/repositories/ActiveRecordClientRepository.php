<?php

namespace app\modules\api\repositories;

use app\models\Client;
use RuntimeException;

class ActiveRecordClientRepository implements ClientRepositoryInterface
{
    public function findAll(int $limit, int $offset, ?string $status = null, ?string $state = null): array
    {
        $query = Client::find()->orderBy(['id' => SORT_ASC]);

        if ($status !== null && $status !== '') {
            $query->andWhere(['status' => $status]);
        }

        if ($state !== null && $state !== '') {
            $query->andWhere(['state' => $state]);
        }

        $models = $query
            ->limit($limit)
            ->offset($offset)
            ->all();

        return array_map([$this, 'toRow'], $models);
    }

    public function findById(int $id): ?array
    {
        $model = Client::findOne($id);

        return $model === null ? null : $this->toRow($model);
    }

    public function create(string $name, string $state, string $status): array
    {
        $model = new Client([
            'name' => $name,
            'state' => $state,
            'status' => $status,
        ]);

        $this->saveOrFail($model);

        return $this->toRow($model);
    }

    public function update(int $id, string $name, string $state, ?string $status = null): ?array
    {
        $model = Client::findOne($id);
        if ($model === null) {
            return null;
        }

        $model->name = $name;
        $model->state = $state;
        if ($status !== null && $status !== '') {
            $model->status = $status;
        }

        $this->saveOrFail($model);

        return $this->toRow($model);
    }

    public function delete(int $id): bool
    {
        $model = Client::findOne($id);
        if ($model === null) {
            return false;
        }

        return (bool) $model->delete();
    }

    /**
     * @return array{id:int,name:string,state:string,status:string}
     */
    private function toRow(Client $model): array
    {
        return [
            'id' => (int) $model->id,
            'name' => (string) $model->name,
            'state' => (string) $model->state,
            'status' => (string) $model->status,
        ];
    }

    private function saveOrFail(Client $model): void
    {
        if ($model->save()) {
            return;
        }

        $errors = $model->getFirstErrors();
        $message = empty($errors) ? 'Client persistence failed.' : implode('; ', $errors);
        throw new RuntimeException($message);
    }
}
