<?php


namespace App\DAL;


use App\Models\Base\UuidModel;
use App\Modules\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseEloquentUuid implements UuidRepository
{
    /**
     * @var UuidModel|Builder
     */
    private $query;

    /**
     * @var UuidModel
     */
    private $model;

    public function __construct(UuidModel $model)
    {
        $this->model = $model;
        $this->query = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->newQuery()->with($relations)->get($columns);
    }

    public function paginate(int $page, int $perPage = 20, array $columns = ['*']): Paginator
    {
        return new Paginator($this->newQuery()->paginate($perPage, $columns, 'page', $page), request());
    }

    public function show(string $uuid): UuidModel
    {
        return $this->newQuery()->findOrFail($uuid);
    }

    public function create(array $attributes): UuidModel
    {
        return $this->newQuery()->create($attributes);
    }

    public function update(UuidModel $model, array $attributes): UuidModel
    {
        $model->update($attributes);

        return $model->fresh();
    }

    public function destroy(UuidModel $model): bool
    {
        return $model->delete();
    }

    public function model(): UuidModel
    {
        return $this->model;
    }

    /**
     * Return new query instance
     * @return Builder
     */
    protected function newQuery(): Builder
    {
        return $this->query->newQuery();
    }
}
