<?php


namespace V1\Tests\Feature\Tips;


use App\Models\Tip;
use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use V1\Tests\Feature\TestCase;

class ListAllTipsTest extends TestCase
{
    /** @test */
    public function all_results_should_be_returned_from_database(): void
    {
        $response = $this->getJson(route('api.v1.tips.index'), $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_OK)->assertJsonStructure(['message', 'result']);

        $data = $response->json('result');
        $this->assertEquals(count($data), Tip::all()->count());
    }

    /** @test */
    public function paginated_results_require_additional_attributes(): void
    {
        $uri = route('api.v1.tips.index') . '?' . http_build_query(['paginate' => 1]);
        $response = $this->getJson($uri, $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'validator'
        ]);

        $validator = $response->json('validator');

        $this->assertEquals(str_replace([':attribute', ':values'], ['per page', 'paginate'],
            trans('validation.required_with')), $validator['perPage'][0]);
        $this->assertEquals(str_replace([':attribute', ':values'], ['page', 'paginate'],
            trans('validation.required_with')), $validator['page'][0]);
    }

    /** @test */
    public function paginated_results_require_valid_additional_attributes(): void
    {
        $uri = route('api.v1.tips.index') . '?' . http_build_query([
                'paginate' => 1,
                'perPage' => -1,
                'page' => 'string'
            ]);
        $response = $this->getJson($uri, $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'validator'
        ]);

        $validator = $response->json('validator');

        $this->assertEquals(str_replace([':attribute', ':min'], ['per page', '1'],
            trans('validation.min.numeric')), $validator['perPage'][0]);
        $this->assertEquals(str_replace([':attribute'], ['page'],
            trans('validation.numeric')), $validator['page'][0]);
    }

    /** @test */
    public function paginated_results_should_be_returned_if_request_is_correct(): void
    {
        $uri = route('api.v1.tips.index') . '?' . http_build_query([
                'paginate' => 1,
                'perPage' => 3,
                'page' => 1
            ]);
        $response = $this->getJson($uri, $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_OK)->assertJsonStructure([
            'message',
            'result' => [
                'current_page',
                'data',
                'from',
                'last_page',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]
        ]);

        $result = $response->json('result');
        $this->assertEquals(1, $result['current_page']);
        $this->assertCount(3, $result['data']);
        $this->assertEquals(1, $result['from']);
        $this->assertEquals(round(Tip::all()->count() / 3), $result['last_page']);
        $this->assertEquals('http://localhost/api/v1/tips?paginate=1&perPage=3&page=2', $result['next_page_url']);
        $this->assertEquals('http://localhost/api/v1/tips', $result['path']);
        $this->assertEquals(3, $result['per_page']);
        $this->assertEquals(null, $result['prev_page_url']);
        $this->assertEquals(3, $result['to']);
        $this->assertEquals(Tip::all()->count(), $result['total']);
    }
}
