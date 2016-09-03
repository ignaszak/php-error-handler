<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.09.16
 * Time: 14:45
 */

declare(strict_types=1);

use Ignaszak\ErrorHandler\Handler;

include __DIR__ . '/vendor/autoload.php';
define('SRAKA', 'ptaka');
$handler = Handler::start(new \Ignaszak\ErrorHandler\View\Dev\DevView());
$handler->register();

$cons = get_defined_constants(true);
function sraka(){ss;}
function dupa($dupa){sraka();}

dupa(new Exception(), $cons);
s;s;s;s;s;s;s;
