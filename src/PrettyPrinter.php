<?php

namespace Sempro\PHPUnitPrettyPrinter;

use PHPUnit\Framework\TestListener;
use PHPUnit\TextUI\ResultPrinter;

/**
 * For PHPUnit9 you must use instead {@see PrettyPrinterForPhpUnit9}
 *
 * @deprecated 1.4.0
 *
 * @link https://github.com/indentno/phpunit-pretty-print/issues/32 Fix for PHPUnit9 compatibility
 */
class PrettyPrinter extends ResultPrinter implements TestListener
{
    use PrettyPrinterTrait;
}
