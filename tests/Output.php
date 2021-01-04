<?php

namespace Sempro\PHPUnitPrettyPrinter\Tests;

use Exception;

class Output extends \PHPUnit\Framework\TestCase
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

    public function testRisky(): void
    {
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

    public function testCanContain1Or99Numbers()
    {
        $this->assertTrue(true);
    }

    public function test123CanStartOrEndWithNumbers456()
    {
        $this->assertTrue(true);
    }

    public function test_should_preserve_CAPITALIZED_and_paRTiaLLY_CAPitaLIZed_words()
    {
        $this->assertTrue(true);
    }

    public function dataProvider()
    {
        yield 'dataset1' => ['test'];
        yield 'DataSet2' => ['test'];
        yield 'data set 3' => ['test'];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testWithNamedDatasets(string $value)
    {
        $this->assertEquals('test', $value);
    }

}
