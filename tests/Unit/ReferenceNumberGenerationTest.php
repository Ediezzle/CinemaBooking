<?php

namespace Tests\Unit;

use App\Traits\StringHelper;
use PHPUnit\Framework\TestCase;

class ReferenceNumberGenerationTest extends TestCase
{
    use StringHelper;

    /**
     * A basic unit test example.
     */
    public function test_reference_number_generated_matches_expected_number_of_digits(): void
    {
        for($i=0; $i<10; $i++) {
            $refNumber = $this->generateRandomAlphaNumericString(8);
            $this->assertEquals(8, strlen($refNumber));

            $refNumber = $this->generateRandomAlphaNumericString(20);
            $this->assertEquals(20, strlen($refNumber));
        }
    }
}
