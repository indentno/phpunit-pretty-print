<?php

namespace Sempro\PHPUnitPrettyPrinter\Tests;

class PrinterTest extends \PHPUnit\Framework\TestCase
{
    private static $output;

    public static function setUpBeforeClass(): void
    {
        self::$output = self::getOutput();
    }

    public function testFirstTestShouldPass()
    {
        $lines = self::$output;

        $this->assertStringContainsString('✓ success', $lines[4]);
    }

    public function testSecondTestShouldFail()
    {
        $lines = self::$output;

        $this->assertStringContainsString('x fail', $lines[5]);
    }

    public function testThirdTestShouldThrowAnError()
    {
        $lines = self::$output;

        $this->assertStringContainsString('⚈ error', $lines[6]);
    }

    public function testForthTestShouldBeSkipped()
    {
        $lines = self::$output;

        $this->assertStringContainsString('→ skip', $lines[7]);
    }

    public function testFifthTestShouldBeIncomplete()
    {
        $lines = self::$output;

        $this->assertStringContainsString('∅ incomplete', $lines[8]);
    }

    public function testTestNamesCanBeTitleCased()
    {
        $lines = self::$output;

        $this->assertStringContainsString('✓ should convert title case to lowercased words', $lines[9]);
    }

    public function testTestNameCanBeSnakeCased()
    {
        $lines = self::$output;

        $this->assertStringContainsString('✓ should convert snake case to lowercased words', $lines[10]);
    }

    public function testTestNameCanBeNonBreakingSpaced()
    {
        $lines = self::$output;

        $this->assertStringContainsString('✓ should convert non breaking spaces to lowercased words', $lines[11]);
    }

    public function testTestNameCanContainNumbers()
    {
        $lines = self::$output;

        $this->assertStringContainsString('✓ can contain 1 or 99 numbers', $lines[12]);
    }

    public function testTestNameCanStartOrEndWithANumber()
    {
        $lines = self::$output;

        $this->assertStringContainsString('✓ 123 can start or end with numbers 456', $lines[13]);
    }

    public function testTestNameCanContainCapitalizedWords()
    {
        $lines = self::$output;

        $this->assertStringContainsString('✓ should preserve capitalized and partially capitalized words', $lines[14]);
    }

    public function testItCanShowProgressWhileRunningTests()
    {
        putenv('PHPUNIT_PRETTY_PRINT_PROGRESS=true');

        $lines = array_slice(self::getOutput(), 4, 11);
        $count = count($lines);

        foreach ($lines as $index => $line) {
            $this->assertStringContainsString(vsprintf('%s/%s', [$index + 1, $count]), $line);
        }
    }

    private static function getOutput(): array
    {
        $command = [
            "vendor/bin/phpunit",
            "tests/Output.php",
            "--printer 'Sempro\PHPUnitPrettyPrinter\PrettyPrinter'",
        ];

        exec(implode(' ', $command), $out);

        return $out;
    }
}
