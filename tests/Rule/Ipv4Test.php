<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Ipv4;
use PHPUnit\Framework\TestCase;

final class Ipv4Test extends TestCase
{
    private Ipv4 $rule;

    protected function setUp(): void
    {
        $this->rule = new Ipv4();
    }

    public function test_validates_ipv4(): void
    {
        $this->assertTrue($this->rule->validate('192.168.1.1'));
    }

    public function test_validates_loopback(): void
    {
        $this->assertTrue($this->rule->validate('127.0.0.1'));
    }

    public function test_rejects_ipv6(): void
    {
        $this->assertFalse($this->rule->validate('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }

    public function test_rejects_invalid_ip(): void
    {
        $this->assertFalse($this->rule->validate('999.999.999.999'));
    }

    public function test_rejects_plain_string(): void
    {
        $this->assertFalse($this->rule->validate('notanip'));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null));
    }

    public function test_name(): void
    {
        $this->assertSame('ipv4', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('ip_address', $this->rule->message('ip_address'));
    }
}
