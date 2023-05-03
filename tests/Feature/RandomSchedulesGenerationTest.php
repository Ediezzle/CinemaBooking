<?php

namespace Tests\Feature;

use Tests\TestCase;

class RandomSchedulesGenerationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_random_schedules_can_be_generated(): void
    {
        $this->artisan('generate:schedules')->assertSuccessful();
    }
}
