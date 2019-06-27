<?php


namespace V1\Http\Controllers;


use App\Http\Controllers\JsonController;
use App\Models\Tip;
use App\Modules\Response\Json\JsonResponseFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use V1\Exceptions\ModelWasNotDeleted;
use V1\Services\Tip\Requests\IndexRequest;
use V1\Services\Tip\Requests\NewRequest;
use V1\Services\Tip\Requests\UpdateRequest;
use V1\Services\Tip\TipService;

class TipsController extends JsonController
{
    /**
     * @var TipService
     */
    private $service;

    /**
     * @var DatabaseManager
     */
    private $database;

    public function __construct(TipService $service, JsonResponseFactory $response, DatabaseManager $database)
    {
        parent::__construct($response);
        $this->service = $service;
        $this->database = $database;
    }

    /**
     * Return all tips from database
     * ?paginate=1&perPage=N&page=N to paginate the results
     * @param IndexRequest $request
     * @return IlluminateJsonResponse
     */
    public function index(IndexRequest $request): IlluminateJsonResponse
    {
        $items = $this->service->index($request);

        return $this->response()->ok(['result' => $items->toArray()]);
    }

    /**
     * Single representation of tip
     * @param string $uuid
     * @return IlluminateJsonResponse
     */
    public function show(string $uuid): IlluminateJsonResponse
    {
        try {
            $tip = $this->service->show($uuid);

            return $this->response()->ok(['result' => $tip->toArray()]);
        } catch (ModelNotFoundException $exception) {
            return $this->response()->notFound($exception->getMessage());
        }
    }

    /**
     * Create new tip instance save to database and return as json response
     * @param NewRequest $request
     * @return IlluminateJsonResponse
     * @throws \Exception
     */
    public function store(NewRequest $request): IlluminateJsonResponse
    {
        $this->database->beginTransaction();
        $tip = $this->service->store($request);
        $this->database->commit();

        return $this->response()->created(['result' => $tip->toArray()]);
    }

    /**
     * Update existing tip and return the updated tip as json response
     * @param string        $uuid
     * @param UpdateRequest $request
     * @return IlluminateJsonResponse
     * @throws \Exception
     */
    public function update(string $uuid, UpdateRequest $request): IlluminateJsonResponse
    {
        try {
            /** @var Tip $tip */
            $tip = $this->service->show($uuid);
            $this->database->beginTransaction();
            $updated = $this->service->update($tip, $request);
            $this->database->commit();

            return $this->response()->ok(['result' => $updated->toArray()]);
        } catch (ModelNotFoundException $exception) {
            return $this->response()->notFound($exception->getMessage());
        }
    }

    /**
     * Delete the existing model from database
     * @param string $uuid
     * @return IlluminateJsonResponse
     * @throws \Exception
     */
    public function destroy(string $uuid): IlluminateJsonResponse
    {
        try {
            /** @var Tip $tip */
            $tip = $this->service->show($uuid);
            $this->database->beginTransaction();
            $this->service->destroy($tip);
            $this->database->commit();

            return $this->response()->ok();
        } catch (ModelNotFoundException $exception) {
            return $this->response()->notFound($exception->getMessage());
        } catch (ModelWasNotDeleted $exception) {
            return $this->response()->internalError($exception->getMessage());
        }
    }
}
