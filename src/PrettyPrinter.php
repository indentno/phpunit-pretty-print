<?php

namespace Sempro\PHPUnitPrettyPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\ResultPrinter;
use PHPUnit\Util\Filter;

class PrettyPrinter extends ResultPrinter implements TestListener
{
    protected $className;
    protected $previousClassName;

    public function startTestSuite(TestSuite $suite)
    {
        parent::startTestSuite($suite);
    }

    public function startTest(Test $test)
    {
        $this->className = get_class($test);
    }

    public function endTest(Test $test, $time)
    {
        parent::endTest($test, $time);

        $testMethodName = explode('::', \PHPUnit\Util\Test::describe($test));

        // convert snakeCase method name to camelCase
        $testMethodName[1] = str_replace('_', '', ucwords($testMethodName[1], '_'));

        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $testMethodName[1], $matches);
        $testNameArray = array_map('strtolower', $matches[0]);

        // check if prefix is test remove it
        if ($testNameArray[0] === 'test') {
            array_shift($testNameArray);
        }

        $name = implode(' ', $testNameArray);

        $color = 'fg-green';
        if ($test->getStatus() !== 0) {
            $color = 'fg-red';
        }

        $this->write(' ');
        $this->writeWithColor($color, $name, false);
        $this->write(' ');

        $timeColor = $time > 0.5 ? 'fg-yellow' : 'fg-white';
        $this->writeWithColor($timeColor, '[' . number_format($time, 3) . 's]', true);
    }

    protected function writeProgress($progress)
    {
        if ($this->previousClassName !== $this->className) {
            $this->write("\n");
            $this->writeWithColor('bold', $this->className, false);
            $this->writeNewLine();
        }

        $this->previousClassName = $this->className;

        if ($progress == '.') {
            $this->writeWithColor('fg-green', '  ✓', false);
        } else {
            $this->writeWithColor('fg-red', '  x', false);
        }
    }

    protected function printDefectTrace(TestFailure $defect)
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

    protected function formatExceptionMsg($exceptionMessage)
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
}
