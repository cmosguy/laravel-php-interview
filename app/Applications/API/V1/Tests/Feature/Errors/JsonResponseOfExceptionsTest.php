<?php


namespace V1\Tests\Feature\Errors;


use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use V1\Tests\Feature\TestCase;

class JsonResponseOfExceptionsTest extends TestCase
{
    /** @test */
    public function it_should_return_404_response_on_missing_route(): void
    {
        $missingUrl = '/api/wrong/url';
        $this->getJson($missingUrl)->assertStatus(IlluminateJsonResponse::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_405_when_method_is_not_allowed_on_route(): void
    {
        $route = route('api.v1.tips.index');
        $this->putJson($route)->assertStatus(IlluminateJsonResponse::HTTP_METHOD_NOT_ALLOWED);
    }
}
