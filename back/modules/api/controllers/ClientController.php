<?php

namespace app\modules\api\controllers;

use app\modules\api\exceptions\ClientNotFoundException;
use app\modules\api\requests\ClientCreateRequest;
use app\modules\api\requests\ClientListRequest;
use app\modules\api\requests\ClientUpdateRequest;
use app\modules\api\services\ClientService;
use app\modules\api\transformers\ClientTransformer;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ClientController extends ApiController
{
    private ClientService $service;
    private ClientTransformer $transformer;

    public function init(): void
    {
        parent::init();
        $this->service = Yii::$container->get(ClientService::class);
        $this->transformer = Yii::$container->get(ClientTransformer::class);
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex(): array
    {
        $request = new ClientListRequest();
        $request->load(Yii::$app->request->queryParams, '');
        if (!$request->validate()) {
            return $this->validationError($request);
        }

        $clients = $this->service->list(
            $request->limit,
            $request->offset,
            $request->status,
            $request->state
        );

        return $this->transformer->transformCollection($clients);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): array
    {
        try {
            $client = $this->service->view($id);
        } catch (ClientNotFoundException) {
            throw new NotFoundHttpException('Client not found.');
        }

        return $this->transformer->transform($client);
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionCreate(): array
    {
        $request = new ClientCreateRequest();
        $request->load(Yii::$app->request->bodyParams, '');
        if (!$request->validate()) {
            return $this->validationError($request);
        }

        try {
            $client = $this->service->create($request->name, $request->state, (string) $request->status);
        } catch (\RuntimeException $exception) {
            throw new ServerErrorHttpException($exception->getMessage(), 0, $exception);
        }

        Yii::$app->response->setStatusCode(201);

        return $this->transformer->transform($client);
    }

    /**
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate(int $id): array
    {
        $request = new ClientUpdateRequest();
        $request->load(Yii::$app->request->bodyParams, '');
        if (!$request->validate()) {
            return $this->validationError($request);
        }

        try {
            $client = $this->service->update($id, $request->name, $request->state, $request->status);
        } catch (ClientNotFoundException) {
            throw new NotFoundHttpException('Client not found.');
        } catch (\RuntimeException $exception) {
            throw new ServerErrorHttpException($exception->getMessage(), 0, $exception);
        }

        return $this->transformer->transform($client);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): ?array
    {
        try {
            $this->service->delete($id);
        } catch (ClientNotFoundException) {
            throw new NotFoundHttpException('Client not found.');
        }

        Yii::$app->response->setStatusCode(204);

        return null;
    }
}
