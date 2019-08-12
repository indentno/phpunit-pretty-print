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

    public function testForthTestShouldBeSkipped()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('→ skip', $lines[7]);
    }

    public function testFifthTestShouldBeIncomplete()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('∅ incomplete', $lines[8]);
    }

    public function testTestNamesCanBeTitleCased()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ should convert title case to lowercased words', $lines[9]);
    }

    public function testTestNameCanBeSnakeCased()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ should convert snake case to lowercased words', $lines[10]);
    }

    public function testTestNameCanBeNonBreakingSpaced()
    {
        $lines = $this->getOutput();

        $this->assertStringContainsString('✓ should convert non breaking spaces to lowercased words', $lines[11]);
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
