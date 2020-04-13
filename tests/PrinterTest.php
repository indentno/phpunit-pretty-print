<?php

namespace Sempro\PHPUnitPrettyPrinter\Tests;

class PrinterTest extends \PHPUnit\Framework\TestCase
{
    public function testFirstTestShouldPass()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ success', $lines[4]);
    }

    public function testSecondTestShouldFail()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('x fail', $lines[5]);
    }

    public function testThirdTestShouldThrowAnError()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('⚈ error', $lines[6]);
    }

    public function testForthTestShouldBeRisked()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('⌽ risky', $lines[7]);
    }

    public function testForthTestShouldBeSkipped()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('→ skip', $lines[8]);
    }

    public function testFifthTestShouldBeIncomplete()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('∅ incomplete', $lines[9]);
    }

    public function testTestNamesCanBeTitleCased()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ should convert title case to lowercased words', $lines[10]);
    }

    public function testTestNameCanBeSnakeCased()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ should convert snake case to lowercased words', $lines[11]);
    }

    public function testTestNameCanBeNonBreakingSpaced()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ should convert non breaking spaces to lowercased words', $lines[12]);
    }

    public function testTestNameCanContainNumbers()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ can contain 1 or 99 numbers', $lines[13]);
    }

    public function testTestNameCanStartOrEndWithANumber()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ 123 can start or end with numbers 456', $lines[14]);
    }

    public function testTestNameCanContainCapitalizedWords()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ should preserve capitalized and partially capitalized words', $lines[15]);
    }

    public function testItCanShowProgressWhileRunningTests()
    {
        putenv('PHPUNIT_PRETTY_PRINT_PROGRESS=true');

        $lines = array_slice($this->getOutput(), 4, 12);
        $count = count($lines);

        foreach ($lines as $index => $line) {
            $this->assertStringContainsString(vsprintf('%s/%s', [$index + 1, $count]), $line);
        }
    }

    private function getOutput(): array
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
