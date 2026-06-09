<?php

namespace Meritum\Validation\Test;

use Meritum\Validation\Result;
use PHPUnit\Framework\TestCase;

final class ResultTest extends TestCase
{
    public function test_passes_when_no_errors(): void
    {
        $result = new Result([]);

        $this->assertTrue($result->passed());
    }

    public function test_fails_when_errors_present(): void
    {
        $result = new Result(['name' => ['The name is required']]);

        $this->assertFalse($result->passed());
    }

    public function test_returns_errors(): void
    {
        $errors = [
            'email' => ['The email is required', 'The email must be a valid email'],
            'name' => ['The name is required'],
        ];

        $result = new Result($errors);

        $this->assertSame($errors, $result->getErrors());
    }

    public function test_returns_empty_errors_when_passing(): void
    {
        $result = new Result([]);

        $this->assertSame([], $result->getErrors());
    }
}
