<h1 align="center">Narrowspark Skeleton Generators</h1>
<p align="center">
    <a href="https://github.com/narrowspark/skeleton-generators/releases"><img src="https://img.shields.io/packagist/v/narrowspark/skeleton-generators.svg?style=flat-square"></a>
    <a href="https://php.net/"><img src="https://img.shields.io/badge/php-%5E7.2.0-8892BF.svg?style=flat-square"></a>
    <a href="https://codecov.io/gh/narrowspark/skeleton-generators"><img src="https://img.shields.io/codecov/c/github/narrowspark/skeleton-generators/master.svg?style=flat-square"></a>
    <a href="#"><img src="https://img.shields.io/badge/style-level%207-brightgreen.svg?style=flat-square&label=phpstan"></a>
    <a href="http://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
</p>

Branch Status
------------
[![Travis branch](https://img.shields.io/travis/narrowspark/skeleton-generators/master.svg?longCache=false&style=for-the-badge)](https://travis-ci.org/narrowspark/skeleton-generators)
[![Appveyor branch](https://img.shields.io/appveyor/ci/narrowspark/skeleton-generators/master.svg?longCache=false&style=for-the-badge)](https://ci.appveyor.com/project/narrowspark/skeleton-generators/branch/master)

Installation
-------------

> **Note:** This package can be only used with [automatic](https://github.com/narrowspark/automatic).

Use [Composer](https://getcomposer.org/) to install this package:

```sh
composer require narrowspark/skeleton-generators
```

Default Directories
-------------
Narrowspark automatically ships with a default directory structure. 
You can easily override this directory structure to create your own. 
The default directory structure is:

```markdown
your-project/
    ├─ app/
    │  ├─ Console/
    │  ├─  └─ Kernel.php
    │  ├─ Provider/
    │  └─ ...
    ├─ config/
    │  └─ ...
    ├─ public/
    │  └─ index.php
    ├─ resources/
    │  └─ ...
    ├─ routes/
    │  ├─ api.php
    │  └─ ...
    ├─ storage/
    │  ├─ app/
    │  ├─ framework/
    │  ├─ logs/
    │  └─ ...
    ├─ tests/
    │  ├─ AbstractTestCase.php
    │  ├─ bootstrap.php
    │  └─ ...
    ├─ cerebro
    └─ vendor/
```
### Override the Directories
You can change the default directories by overriding the composer extra section of your application:
```json
{
  "extra": {
    "app-dir": "app",
    "config-dir": "config",
    "database-dir": "database",
    "public-dir": "public",
    "resources-dir": "resources",
    "routes-dir": "routes",
    "storage-dir": "storage",
    "tests-dir": "tests"
  }
}
```
The `root-dir` key should be only used if your project is in a sub folder of your main project.

Generators
-------------

#### - Console
The `Console Generator` is a micro-framework that provides an elegant starting point for your console application.

#### - Framework
The `Framework Generator` provides a full framework for your next web projects.

#### - Micro
The `Micro Generator` is a micro-framework that provides an elegant starting point for your next web application.

Testing
-------------

You need to run:
``` bash
$ php vendor/bin/phpunit
```

Contributing
------------

If you would like to help take a look at the [list of issues](http://github.com/narrowspark/testing-helper/issues) and check our [Contributing](CONTRIBUTING.md) guild.

> **Note:** Please note that this project is released with a Contributor Code of Conduct. By participating in this project you agree to abide by its terms.

Credits
-------------

- [Daniel Bannert](https://github.com/prisis)
- [All Contributors](../../contributors)

License
-------------

The MIT License (MIT). Please see [License File](LICENSE) for more information.
