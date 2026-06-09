<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Url;
use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{
    private Url $rule;

    protected function setUp(): void
    {
        $this->rule = new Url();
    }

    public function test_validates_http_url(): void
    {
        $this->assertTrue($this->rule->validate('http://example.com'));
    }

    public function test_validates_https_url(): void
    {
        $this->assertTrue($this->rule->validate('https://example.com'));
    }

    public function test_validates_url_with_path(): void
    {
        $this->assertTrue($this->rule->validate('https://example.com/path/to/page'));
    }

    public function test_validates_url_with_query_string(): void
    {
        $this->assertTrue($this->rule->validate('https://example.com?foo=bar'));
    }

    public function test_rejects_missing_scheme(): void
    {
        $this->assertFalse($this->rule->validate('example.com'));
    }

    public function test_rejects_plain_string(): void
    {
        $this->assertFalse($this->rule->validate('notaurl'));
    }

    public function test_rejects_integer(): void
    {
        $this->assertFalse($this->rule->validate(42));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null));
    }

    public function test_name(): void
    {
        $this->assertSame('url', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('website', $this->rule->message('website'));
    }
}
