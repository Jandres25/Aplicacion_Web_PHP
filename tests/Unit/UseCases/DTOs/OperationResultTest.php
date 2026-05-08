<?php

namespace Tests\Unit\UseCases\DTOs;

use App\UseCases\DTOs\OperationResult;
use PHPUnit\Framework\TestCase;

class OperationResultTest extends TestCase
{
    public function test_ok_creates_successful_result(): void
    {
        $result = OperationResult::ok('Operación exitosa', ['id' => 1]);

        $this->assertTrue($result->success);
        $this->assertSame('Operación exitosa', $result->message);
        $this->assertSame(['id' => 1], $result->data);
    }

    public function test_ok_with_no_arguments(): void
    {
        $result = OperationResult::ok();

        $this->assertTrue($result->success);
        $this->assertNull($result->message);
        $this->assertNull($result->data);
    }

    public function test_fail_creates_failed_result_with_message(): void
    {
        $result = OperationResult::fail('Algo salió mal');

        $this->assertFalse($result->success);
        $this->assertSame('Algo salió mal', $result->message);
        $this->assertNull($result->data);
    }

    public function test_properties_are_readonly(): void
    {
        $result = OperationResult::ok('msg', 'data');

        $this->expectException(\Error::class);
        $result->success = false;
    }
}
