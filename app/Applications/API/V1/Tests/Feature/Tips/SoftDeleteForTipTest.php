<?php


namespace V1\Tests\Feature\Tips;


use App\Models\Tip;
use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use V1\Tests\Feature\TestCase;

class SoftDeleteForTipTest extends TestCase
{
    /** @test */
    public function tip_must_exists_in_database_in_order_to_be_deleted(): void
    {
        $response = $this->deleteJson(route('api.v1.tips.destroy', ['tip' => 'missing-uuid']), [],
            $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_NOT_FOUND)->assertJsonStructure(['message',]);
    }

    /** @test */
    public function existing_tip_should_be_soft_deleted_from_database(): void
    {
        /** @var Tip $tip */
        $tip = create(Tip::class);
        $response = $this->deleteJson(route('api.v1.tips.destroy', ['tip' => $tip->guid_text]), [],
            $this->bearerHeader());
        $response->assertStatus(IlluminateJsonResponse::HTTP_OK)->assertJsonStructure(['message']);

        $this->assertEquals(Tip::withTrashed()->find($tip->guid)->guid_text, $tip->guid_text);
    }
}
