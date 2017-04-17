# ignaszak/error-handler
---
This package provides error handler interface

# Installation

```
"require" : {
    "ignaszak/error-handler" : "dev-master"
}
```

# Requirements

* PHP >= 7.0.0
* PHPUnit >= 6.0.0

# Usage

## Development mode

```php
<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.09.16
 * Time: 14:45
 */

declare(strict_types=1);

use Ignaszak\ErrorHandler\Handler;
use Ignaszak\ErrorHandler\View\Dev\DevView;

include __DIR__ . '/vendor/autoload.php';
include 'included_file.php';

define('CONSTANT', 'demo');

// Start and register error handler with development interface
$handler = Handler::start(new DevView());
$handler->register();

// Make some errors for test

function test() {
    $func('test');
}

test();

try {
    throw new Exception();
} catch (Exception $e) {
    $handler->catch($e);
}
```

## Cli mode

If You are running your app in console you can use ```CliView```.

```php
<?php

use Ignaszak\ErrorHandler\Handler;
use Ignaszak\ErrorHandler\View\Cli\CliView;

include __DIR__ . '/vendor/autoload.php';

$handler = Handler::start(new CliView());
$handler->register();

```
