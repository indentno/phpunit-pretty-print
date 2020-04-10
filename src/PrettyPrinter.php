<?php

namespace Sempro\PHPUnitPrettyPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Runner\BaseTestRunner;
use PHPUnit\TextUI\ResultPrinter;
use PHPUnit\Util\Filter;

class PrettyPrinter extends ResultPrinter implements TestListener
{
    protected $className;
    protected $previousClassName;

    public function startTestSuite(TestSuite $suite): void
    {
        parent::startTestSuite($suite);
    }

    public function startTest(Test $test): void
    {
        $this->className = get_class($test);
    }

    public function endTest(Test $test, float $time): void
    {
        parent::endTest($test, $time);

        $testMethodName = \PHPUnit\Util\Test::describe($test);

        // Convert capitalized words to lowercase
        $testMethodName[1] = preg_replace_callback('/([A-Z]{2,})/', function ($matches) {
            return strtolower($matches[0]);
        }, $testMethodName[1]);

        // Convert non-breaking method name to camelCase
        $testMethodName[1] = str_replace(' ', '', ucwords($testMethodName[1], ' '));

        // Convert snakeCase method name to camelCase
        $testMethodName[1] = str_replace('_', '', ucwords($testMethodName[1], '_'));

        preg_match_all('/((?:^|[A-Z])[a-z0-9]+)/', $testMethodName[1], $matches);

        // Prepend all numbers with a space
        $replaced = preg_replace('/(\d+)/', ' $1', $matches[0]);

        $testNameArray = array_map('strtolower', $replaced);

        $name = implode(' ', $testNameArray);

        // check if prefix is test remove it
        $name = preg_replace('/^test /', '', $name, 1);

        // Get the data set name
        $name = $this->handleDataSetName($name, $testMethodName[1]);

        $color = 'fg-green';
        if ($test->getStatus() !== 0) {
            $color = 'fg-red';
        }

        $this->write(' ');

        switch ($test->getStatus()) {
            case BaseTestRunner::STATUS_PASSED:
                $this->writeWithColor('fg-green', $name, false);

                break;
            case BaseTestRunner::STATUS_SKIPPED:
                $this->writeWithColor('fg-yellow', $name, false);

                break;
            case BaseTestRunner::STATUS_INCOMPLETE:
                $this->writeWithColor('fg-blue', $name, false);

                break;
            case BaseTestRunner::STATUS_FAILURE:
                $this->writeWithColor('fg-red', $name, false);

                break;
            case BaseTestRunner::STATUS_ERROR:
                $this->writeWithColor('fg-red', $name, false);

                break;
            case BaseTestRunner::STATUS_RISKY:
                $this->writeWithColor('fg-magenta', $name, false);

                break;
            case BaseTestRunner::STATUS_WARNING:
                $this->writeWithColor('fg-yellow', $name, false);

                break;
            case BaseTestRunner::STATUS_UNKNOWN:
            default:
                $this->writeWithColor('fg-cyan', $name, false);

                break;
        }

        $this->write(' ');

        $timeColor = $time > 0.5 ? 'fg-yellow' : 'fg-white';
        $this->writeWithColor($timeColor, '[' . number_format($time, 3) . 's]', true);
    }

    protected function writeProgress(string $progress): void
    {
        if ($this->previousClassName !== $this->className) {
            $this->write("\n");
            $this->writeWithColor('bold', $this->className, false);
            $this->writeNewLine();
        }

        $this->previousClassName = $this->className;

        $this->printProgress();

        switch (strtoupper(preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $progress))) {
            case '.':
                $this->writeWithColor('fg-green', '  ✓', false);

                break;
            case 'S':
                $this->writeWithColor('fg-yellow', '  →', false);

                break;
            case 'I':
                $this->writeWithColor('fg-blue', '  ∅', false);

                break;
            case 'F':
                $this->writeWithColor('fg-red', '  x', false);

                break;
            case 'E':
                $this->writeWithColor('fg-red', '  ⚈', false);

                break;
            case 'R':
                $this->writeWithColor('fg-magenta', '  ⌽', false);

                break;
            case 'W':
                $this->writeWithColor('fg-yellow', '  ¤', false);

                break;
            default:
                $this->writeWithColor('fg-cyan', '  ≈', false);

                break;
        }
    }

    protected function printDefectTrace(TestFailure $defect): void
    {
        $this->write($this->formatExceptionMsg($defect->getExceptionAsString()));
        $trace = Filter::getFilteredStacktrace(
            $defect->thrownException()
        );
        if (!empty($trace)) {
            $this->write("\n" . $trace);
        }
        $exception = $defect->thrownException()->getPrevious();
        while ($exception) {
            $this->write(
                "\nCaused by\n" .
                TestFailure::exceptionToString($exception) . "\n" .
                Filter::getFilteredStacktrace($exception)
            );
            $exception = $exception->getPrevious();
        }
    }

    protected function formatExceptionMsg($exceptionMessage): string
    {
        $exceptionMessage = str_replace("+++ Actual\n", '', $exceptionMessage);
        $exceptionMessage = str_replace("--- Expected\n", '', $exceptionMessage);
        $exceptionMessage = str_replace('@@ @@', '', $exceptionMessage);

        if ($this->colors) {
            $exceptionMessage = preg_replace('/^(Exception.*)$/m', "\033[01;31m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace('/(Failed.*)$/m', "\033[01;31m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace("/(\-+.*)$/m", "\033[01;32m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace("/(\++.*)$/m", "\033[01;31m$1\033[0m", $exceptionMessage);
        }

        return $exceptionMessage;
    }

    private function handleDataSetName($name, $testMethodName): string
    {
        preg_match('/\bwith data set "([^"]+)"/', $testMethodName, $dataSetMatch);

        if (empty($dataSetMatch)) {
            return $name;
        }

        return $name . ' [' . $dataSetMatch[1] . ']';
    }

    private function printProgress()
    {
        if (filter_var(getenv('PHPUNIT_PRETTY_PRINT_PROGRESS'), FILTER_VALIDATE_BOOLEAN)) {
            $this->numTestsRun++;

            $total = $this->numTests;
            $current = str_pad($this->numTestsRun, strlen($total), '0', STR_PAD_LEFT);

            $this->write("[{$current}/{$total}]");
        }
    }
}
