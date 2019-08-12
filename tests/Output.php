<?php

namespace Sempro\PHPUnitPrettyPrinter\Tests;

use Exception;

class OutputTest extends \PHPUnit\Framework\TestCase
{
    public function testSuccess(): void
    {
        $this->assertTrue(true);
    }

    public function testFail(): void
    {
        $this->assertTrue(false);
    }

    public function testError(): void
    {
        throw new Exception('error');
    }

    public function testSkip(): void
    {
        $this->markTestSkipped('skipped');
    }
    
    public function testIncomplete(): void
    {
        $this->markTestIncomplete('incomplete');
    }

    public function testShouldConvertTitleCaseToLowercasedWords()
    {
        $this->assertTrue(true);
    }

    public function test_should_convert_snake_case_to_lowercased_words()
    {
        $this->assertTrue(true);
    }

    public function test should convert non breaking spaces to lowercased words()
    {
        $this->assertTrue(true);
    }
}
