# phpunit-pretty-print
> Prettify PHPUnit output

[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)
[![eslint](https://img.shields.io/badge/code_style-PSR_2-blue.svg?style=flat-square)](http://www.php-fig.org/psr/psr-2/)

<img src="https://raw.githubusercontent.com/Sempro/phpunit-pretty-print/master/preview.png" alt="phpunit-pretty-print">

### Installation
```bash
composer require sempro/phpunit-pretty-print --dev
```

This package requires >=7.0.0 of phpunit. If you're running on version 6, please use version 1.0.3 of this package.

### Usage
You can specify the printer to use on the phpunit command line:

```bash
php vendor/bin/phpunit --printer 'Sempro\PHPUnitPrettyPrinter\PrettyPrinter' tests/
```

Optionally, you can add it to your project's `phpunit.xml` file instead:

```xml
<phpunit
    bootstrap="bootstrap.php"
    colors="true"
    printerClass="Sempro\PHPUnitPrettyPrinter\PrettyPrinter">
```

### License
MIT © [Sempro AS](http://www.sempro.no)
