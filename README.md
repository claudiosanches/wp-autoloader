# WPAutoloader

Loads namespaces and classes following the [WordPress naming convertions](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#naming-conventions).

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/claudiosanches/wp-autoloader/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/claudiosanches/wp-autoloader/?branch=master)
[![Code Climate](https://codeclimate.com/github/claudiosanches/wp-autoloader/badges/gpa.svg)](https://codeclimate.com/github/claudiosanches/wp-autoloader)
[![Code Coverage](https://scrutinizer-ci.com/g/claudiosanches/wp-autoloader/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/claudiosanches/wp-autoloader/?branch=master)
[![Build Status](https://travis-ci.org/claudiosanches/wp-autoloader.svg?branch=master)](https://travis-ci.org/claudiosanches/wp-autoloader)

## Installation

```
composer require claudiosanches/wp-autoloader
```

## Usage

```php
require __DIR__ . '/vendor/autoload.php';

use ClaudioSanches\WPAutoloader\Autoloader;

$autoloader = new Autoloader();

// Add namespace.
$autoloader->addNamespace('ClaudioSanches\Foo\Bar');

// Register all autoload.
$autoloader->register();
```

## Release History

- 2017-01-17 - 1.0.0 - Stable release.
