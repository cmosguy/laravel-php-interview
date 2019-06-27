<?php


namespace V1\Tests\Unit\Http\Controllers;


use App\DAL\Contracts\TipRepository;
use App\Models\Tip;
use App\Modules\Pagination\Paginator;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery\MockInterface;
use Tests\TestCase;
use V1\Http\Controllers\TipsController;
use V1\Services\Tip\Requests\IndexRequest;
use V1\Services\Tip\Requests\NewRequest;
use V1\Services\Tip\Requests\UpdateRequest;

class TipsControllerTest extends TestCase
{
    /**
     * @var MockInterface
     */
    private $repository;

    /**
     * @var MockInterface
     */
    private $db;

    /**
     * @var TipsController
     */
    private $controller;

    protected function setUp()
    {
        parent::setUp();
        $this->repository = $this->newMock(TipRepository::class);
        $this->db = $this->newMock(DatabaseManager::class);
        $this->controller = app(TipsController::class);
    }

    /** @test */
    public function all_tip_results_should_be_returned_if_pagination_is_not_required(): void
    {
        $request = new IndexRequest();
        $request->setContainer($this->app);
        $this->repository->shouldReceive('all')->once()->andReturn(make(Tip::class, 5));

        $response = $this->controller->index($request);

        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function tip_results_should_be_paginated_if_the_query_values_are_set(): void
    {
        $request = new IndexRequest(['paginate' => 1, 'perPage' => 10, 'page' => 2]);
        $request->setContainer($this->app);
        $this->repository->shouldReceive('paginate')->with(2, 10)->once()->andReturn(
            new Paginator(new LengthAwarePaginator([], 0, 10, 2), $request)
        );

        $response = $this->controller->index($request);

        $this->assertEquals(200, $response->status());
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     * @test
     */
    public function tip_pagination_requires_additional_parameters_in_order_to_return_paginated_results(): void
    {
        $request = new IndexRequest(['paginate' => 1, 'perPage' => null, 'page' => -1]);
        $request->setContainer($this->app);

        $this->controller->index($request);
    }

    /** @test */
    public function not_found_response_should_be_returned_if_tip_does_not_exists_in_database(): void
    {
        $this->repository->shouldReceive('show')->with('missing-uuid')->once()
                         ->andThrow((new ModelNotFoundException())->setModel(Tip::class));

        $response = $this->controller->show('missing-uuid');

        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function existing_tip_in_database_should_be_returned_with_success(): void
    {
        $this->repository->shouldReceive('show')->with('existing-uuid')->once()->andReturn(new Tip());

        $response = $this->controller->show('existing-uuid');

        $this->assertEquals(200, $response->status());
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     * @test
     */
    public function data_is_required_in_order_to_store_new_tip(): void
    {
        $request = new NewRequest();
        $request->setContainer($this->app);

        $this->db->shouldReceive('beginTransaction')->once();

        $this->controller->store($request);
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     * @test
     */
    public function valid_data_is_required_in_order_to_store_new_tip(): void
    {
        $request = new NewRequest(['title' => str_random(256), 'description' => str_random(1001)]);
        $request->setContainer($this->app);

        $this->db->shouldReceive('beginTransaction')->once();

        $this->controller->store($request);
    }

    /** @test */
    public function tip_should_be_stored_if_valid_data_is_provided(): void
    {
        $request = new NewRequest(['title' => 'Hello World', 'description' => 'Hello from description as well']);
        $request->setContainer($this->app);

        $this->db->shouldReceive('beginTransaction')->once();
        $this->repository->shouldReceive('create')->with([
            'title' => 'Hello World',
            'description' => 'Hello from description as well'
        ])->once()->andReturn(new Tip());
        $this->db->shouldReceive('commit')->once();

        $response = $this->controller->store($request);

        $this->assertEquals(201, $response->status());
    }

    /** @test */
    public function tip_must_exists_in_order_to_update(): void
    {
        $request = new UpdateRequest(['title' => 'Hello World', 'description' => 'Hello from description as well']);
        $request->setContainer($this->app);

        $this->repository->shouldReceive('show')->with('missing-uuid')->once()
                         ->andThrow((new ModelNotFoundException())->setModel(Tip::class));

        $response = $this->controller->update('missing-uuid', $request);

        $this->assertEquals(404, $response->status());
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     * @test
     */
    public function valid_data_should_be_provided_in_order_to_update_existing_tip(): void
    {
        $request = new UpdateRequest(['title' => str_random(256), 'description' => str_random(1001)]);
        $request->setContainer($this->app);

        $this->repository->shouldReceive('show')->with('existing-uuid')->once()->andReturn(new Tip());
        $this->db->shouldReceive('beginTransaction')->once();

        $this->controller->update('existing-uuid', $request);
    }

    /** @test */
    public function existing_tip_with_valid_request_data_should_be_updated_successfully(): void
    {
        $attributes = ['title' => 'Hello world', 'description' => 'Hello world from description'];
        $request = new UpdateRequest($attributes);
        $request->setContainer($this->app);
        $existingTip = new Tip();

        $this->repository->shouldReceive('show')->with('existing-uuid')->once()->andReturn($existingTip);
        $this->db->shouldReceive('beginTransaction')->once();
        $this->repository->shouldReceive('model->getFillable')->andReturn(['title', 'description']);
        $this->repository->shouldReceive('update')->with($existingTip, $attributes)->once()->andReturn(new Tip());
        $this->db->shouldReceive('commit')->once();

        $response = $this->controller->update('existing-uuid', $request);

        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function it_requires_existing_tip_in_order_to_delete(): void
    {
        $this->repository->shouldReceive('show')->with('missing-uuid')->once()
                         ->andThrow((new ModelNotFoundException())->setModel(Tip::class));

        $response = $this->controller->destroy('missing-uuid');

        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function any_error_should_be_reported_if_the_deletion_fails_for_existing_tip(): void
    {
        $tip = new Tip();
        $tip->guid = 'existing-uuid';
        $this->repository->shouldReceive('show')->with('existing-uuid')->once()->andReturn($tip);
        $this->db->shouldReceive('beginTransaction')->once();
        $this->repository->shouldReceive('destroy')->once()->with($tip)->andThrow(new Exception());

        $response = $this->controller->destroy('existing-uuid');

        $this->assertEquals(500, $response->status());
    }

    /** @test */
    public function non_true_response_from_database_should_not_delete_existing_tip(): void
    {
        $tip = new Tip();
        $tip->guid = 'existing-uuid';
        $this->repository->shouldReceive('show')->with('existing-uuid')->once()->andReturn($tip);
        $this->db->shouldReceive('beginTransaction')->once();
        $this->repository->shouldReceive('destroy')->once()->with($tip)->andReturn(false);

        $response = $this->controller->destroy('existing-uuid');

        $this->assertEquals(500, $response->status());
    }

    /** @test */
    public function existing_tip_should_be_deleted_with_success(): void
    {
        $tip = new Tip();
        $tip->guid = 'existing-uuid';
        $this->repository->shouldReceive('show')->with('existing-uuid')->once()->andReturn($tip);
        $this->db->shouldReceive('beginTransaction')->once();
        $this->repository->shouldReceive('destroy')->once()->with($tip)->andReturn(true);
        $this->db->shouldReceive('commit');

        $response = $this->controller->destroy('existing-uuid');

        $this->assertEquals(200, $response->status());
    }
}
