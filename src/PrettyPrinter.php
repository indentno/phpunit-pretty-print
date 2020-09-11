<?php

namespace Sempro\PHPUnitPrettyPrinter;

use PHPUnit\Framework\TestListener;
use PHPUnit\TextUI\ResultPrinter;

class PrettyPrinter extends ResultPrinter implements TestListener
{
    use PrettyPrinterTrait;
}
