<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.09.16
 * Time: 11:55
 */

declare(strict_types=1);

namespace Test\Controller;

use Ignaszak\ErrorHandler\Controller\Controller;
use Ignaszak\TestingTools\Test;

/**
 * Class ControllerTest
 * @package Test\Controller
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Controller
     */
    private $controller = null;

    public function setUp()
    {
        $this->controller = new Controller();
        Test::$object = $this->controller;
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Ignaszak\ErrorHandler\Controller\Converter',
            Test::get('converter')
        );
    }

    public function testSuppressedError()
    {
        set_error_handler(function ($number, $message, $file, $line) {
            $this->assertTrue(Test::call('isErrorSuppressed'));
        });
        @triggerSuppressedError;
    }

    public function testNoSuppressedError()
    {
        set_error_handler(function ($number, $message, $file, $line) {
            $this->assertFalse(Test::call('isErrorSuppressed'));
        });
        trigger_error('test');
    }

    public function testRegisterOnlyNonSuppressedErrors()
    {
        set_error_handler(function ($number, $message, $file, $line) {
            $this->assertTrue(Test::call('hasErrorPermissionToRegister'));
        });
        trigger_error('test');

        set_error_handler(function ($number, $message, $file, $line) {
            $this->assertFalse(Test::call('hasErrorPermissionToRegister'));
        });
        @triggerSuppressedError;
    }

    public function testRegisterAllErrors()
    {
        $this->controller->showSuppressedErrors = true;
        set_error_handler(function ($number, $message, $file, $line) {
            $this->assertTrue(Test::call('hasErrorPermissionToRegister'));
        });
        trigger_error('test');

        set_error_handler(function ($number, $message, $file, $line) {
            $this->assertTrue(Test::call('hasErrorPermissionToRegister'));
        });
        @triggerSuppressedError;
    }

    public function testHandleErrors()
    {
        set_error_handler(function ($number, $message, $file, $line) {
            $this->controller->handle($number, $message, $file, $line);
        });
        trigger_error('test');
        $this->assertNotEmpty(Test::get('errors'));
    }

    public function testHandleNotPermittedErrors()
    {
        set_error_handler(function ($number, $message, $file, $line) {
            $this->controller->handle($number, $message, $file, $line);
        });
        @triggerSuppressedError;
        $this->assertEmpty(Test::get('errors'));
    }
}
