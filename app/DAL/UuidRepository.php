<?php


namespace App\DAL;


use App\Models\Base\UuidModel;
use App\Modules\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface UuidRepository
{
    /**
     * Return all data from database
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Return paginated results
     * @param int   $page
     * @param int   $perPage
     * @param array $columns
     * @return Paginator
     */
    public function paginate(int $page, int $perPage = 20, array $columns = ['*']): Paginator;

    /**
     * Single model representation
     * @param string $uuid
     * @return UuidModel
     * @throws ModelNotFoundException
     */
    public function show(string $uuid): UuidModel;

    /**
     * Store new entity to database
     * @param array $attributes
     * @return UuidModel
     */
    public function create(array $attributes): UuidModel;

    /**
     * Update existing model
     * @param UuidModel $model
     * @param array     $attributes
     * @return UuidModel
     */
    public function update(UuidModel $model, array $attributes): UuidModel;

    /**
     * @param UuidModel $model
     * @return bool
     * @throws \Exception
     */
    public function destroy(UuidModel $model): bool;

    /**
     * Return the injected model
     * @return UuidModel
     */
    public function model(): UuidModel;
}
