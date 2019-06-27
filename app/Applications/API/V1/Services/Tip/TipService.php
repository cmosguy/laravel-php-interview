<?php


namespace V1\Services\Tip;


use App\DAL\Contracts\TipRepository;
use App\Models\Tip;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use V1\Exceptions\ModelWasNotDeleted;
use V1\Services\Tip\Requests\IndexRequest;
use V1\Services\Tip\Requests\NewRequest;
use V1\Services\Tip\Requests\UpdateRequest;

class TipService
{
    /**
     * @var TipRepository
     */
    private $repository;

    public function __construct(TipRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Determine if paginated results should be returned or all
     * @param IndexRequest $request
     * @return Arrayable
     */
    public function index(IndexRequest $request): Arrayable
    {
        $options = $request->validated();

        if ($options['paginate'] ?? false === true) {
            return $this->repository->paginate($options['page'], $options['perPage']);
        }

        return $this->repository->all();
    }

    /**
     * Single representation of tips. Throws an exception if guid not found in database
     * @param string $uuid
     * @return Arrayable
     * @throws ModelNotFoundException
     */
    public function show(string $uuid): Arrayable
    {
        return $this->repository->show($uuid);
    }

    /**
     * Save new request to database as tip
     * @param NewRequest $request
     * @return Arrayable
     */
    public function store(NewRequest $request): Arrayable
    {
        return $this->repository->create($request->validated());
    }

    /**
     * Update the given tip with the given request attributes
     * @param Tip           $tip
     * @param UpdateRequest $request
     * @return Arrayable
     */
    public function update(Tip $tip, UpdateRequest $request): Arrayable
    {
        $attributes = array_only($request->validated(), $this->repository->model()->getFillable());

        return $this->repository->update($tip, $attributes);
    }

    /**
     * @param Tip $tip
     * @throws ModelWasNotDeleted
     */
    public function destroy(Tip $tip): void
    {
        try {
            if ($this->repository->destroy($tip) !== true) {
                throw new ModelWasNotDeleted($tip->guid, class_basename($tip));
            }
        } catch (Exception $exception) {
            throw new ModelWasNotDeleted($tip->guid, class_basename($tip), $exception->getMessage());
        }
    }
}
