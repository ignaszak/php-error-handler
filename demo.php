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

$handler = Handler::start(new DevView());
$handler->register();

function test() {
    $func('test');
}

test();

try {
    throw new Exception();
} catch (Exception $e) {
    $handler->catch($e);
}
