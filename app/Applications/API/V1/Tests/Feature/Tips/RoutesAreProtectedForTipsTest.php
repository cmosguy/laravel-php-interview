<?php


namespace V1\Tests\Feature\Tips;


use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use V1\Tests\Feature\TestCase;

class RoutesAreProtectedForTipsTest extends TestCase
{
    /** @test */
    public function api_token_must_be_set_in_order_to_hit_any_of_tips_route(): void
    {
        $old = env('API_KEY');
        putenv("API_KEY=null");

        $response = $this->getJson(route('api.v1.tips.index'));
        $response->assertStatus(IlluminateJsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        putenv("API_KEY={$old}");
    }

    /** @test */
    public function valid_api_key_should_be_provided_in_order_to_call_protected_route(): void
    {
        $response = $this->getJson(route('api.v1.tips.index'), $this->bearerHeader('invalid-key'));
        $response->assertStatus(IlluminateJsonResponse::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function protected_route_should_be_hit_with_valid_api_key(): void
    {
        $response = $this->getJson(route('api.v1.tips.index'), $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_OK);
    }
}
