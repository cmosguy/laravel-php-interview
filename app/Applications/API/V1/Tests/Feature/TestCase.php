<?php


namespace V1\Tests\Feature;


use Tests\TestCase as LaravelTestCase;

class TestCase extends LaravelTestCase
{
    protected static $migrated = false;

    public function createApplication()
    {
        $app = parent::createApplication();
        if (static::$migrated === false) {
            $this->afterApplicationCreated(function() {
                $this->artisan('migrate:fresh', ['--seed' => true]);
            });

            static::$migrated = true;
        }

        return $app;
    }

    /**
     * @param string|null $key
     * @return array
     */
    protected function bearerHeader(string $key = null): array
    {
        if (is_null($key)) {
            $key = env('API_KEY');
        }

        return [
            'authorization' => "Bearer {$key}"
        ];
    }

    /**
     * Assert that field is required_with from laravel validator
     * @param array       $messages
     * @param string      $key
     * @param string|null $fieldName
     * @param int         $index
     */
    protected function assertFieldRequiredWith(
        array $messages,
        string $key,
        string $fieldName = null,
        int $index = 0
    ): void {
        if (is_null($fieldName)) {
            $fieldName = $key;
        }

        $this->assertEquals(str_replace(':attribute', $fieldName, trans('validation.required_with')),
            $messages[$key][$index]);
    }
}
