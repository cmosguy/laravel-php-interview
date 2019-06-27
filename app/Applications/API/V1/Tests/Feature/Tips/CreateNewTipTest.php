<?php


namespace V1\Tests\Feature\Tips;


use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use V1\Tests\Feature\TestCase;

class CreateNewTipTest extends TestCase
{
    /** @test */
    public function it_requires_data_in_order_to_store_new_tip(): void
    {
        $response = $this->postJson(route('api.v1.tips.store'), [], $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'validator'
        ]);

        $validator = $response->json('validator');
        $this->assertEquals(str_replace([':attribute'], ['title'],
            trans('validation.required')), $validator['title'][0]);
        $this->assertEquals(str_replace([':attribute'], ['description'],
            trans('validation.required')), $validator['description'][0]);
    }

    /** @test */
    public function it_requires_valid_data_in_order_to_store_new_tip(): void
    {
        $attributes = ['title' => str_random(256), 'description' => str_random(1001)];
        $response = $this->postJson(route('api.v1.tips.store'), $attributes, $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'validator'
        ]);

        $validator = $response->json('validator');
        $this->assertEquals(str_replace([':attribute', ':max'], ['title', '255'],
            trans('validation.max.string')), $validator['title'][0]);
        $this->assertEquals(str_replace([':attribute', ':max'], ['description', '1000'],
            trans('validation.max.string')), $validator['description'][0]);
    }
}
