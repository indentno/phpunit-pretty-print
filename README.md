# phpunit-pretty-print
> Prettify PHPUnit output

[![Travis](https://img.shields.io/travis/sempro/phpunit-pretty-print.svg?style=flat-square)](https://travis-ci.com/sempro/phpunit-pretty-print/builds)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)
[![psr-2](https://img.shields.io/badge/code_style-PSR_2-blue.svg?style=flat-square)](http://www.php-fig.org/psr/psr-2/)


### Installation
```bash
composer require sempro/phpunit-pretty-print --dev
```

This package requires `>=7.0.0` of PHPUnit.
If you're running on `6.x`, please use version `1.0.3`.

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

<img src="https://raw.githubusercontent.com/Sempro/phpunit-pretty-print/master/preview.gif" width="100%" alt="phpunit-pretty-print">

### License
MIT © [Sempro AS](http://www.sempro.no)
