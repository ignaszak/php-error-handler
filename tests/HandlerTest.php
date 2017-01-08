<?php
/**
 * Created by PhpStorm.
 * User: Tomasz Ignaszak
 * Date: 01.09.16
 * Time: 16:59
 */

declare(strict_types=1);

namespace Test;

use Ignaszak\ErrorHandler\Handler;
use Ignaszak\TestingTools\Test;

/**
 * Class HandlerTest
 * @package Test
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Handler
     */
    private $handler = null;

    public function setUp()
    {
        $this->handler = Handler::start();
        Test::$object = $this->handler;
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Ignaszak\ErrorHandler\Controller\Controller',
            Test::get('controller')
        );
    }

    public function testEmptyConstructor()
    {
        $this->assertNull(Test::get('view'));
    }

    public function testNotEmptyConstructor()
    {
        $mock = $this->getMockBuilder('Ignaszak\ErrorHandler\View\IView')
            ->getMock();
        $mock->expects($this->once())->method('setHandler');
        $this->assertInstanceOf(
            'Ignaszak\ErrorHandler\View\IView',
            Test::get('view', Handler::start($mock))
        );
    }

    public function testAddPlugin()
    {
        $this->handler->addPlugin(
            $this->createMock('Ignaszak\ErrorHandler\Plugin\IPlugin')
        );
        $this->handler->addPlugin(
            $this->createMock('Ignaszak\ErrorHandler\Plugin\IPlugin')
        );
        $this->assertInstanceOf(
            'Ignaszak\ErrorHandler\Plugin\IPlugin',
            Test::get('plugins')[0]
        );
        $this->assertInstanceOf(
            'Ignaszak\ErrorHandler\Plugin\IPlugin',
            Test::get('plugins')[1]
        );
    }

    public function testRegister()
    {
        $mock = $this->getMockBuilder('Ignaszak\ErrorHandler\Controller\Controller')
            ->getMock();
        $mock->expects($this->exactly(1))->method('handle');
        Test::inject('controller', $mock);
        $this->handler->register();
        trigger_error('test error', E_USER_NOTICE);
    }

    public function testDontCatchSuppressed()
    {
        $this->handler->register();
        @include 'nonExistingFile';
    }

    public function testCatch()
    {
        $mock = $this->getMockBuilder('Ignaszak\ErrorHandler\Controller\Controller')
            ->getMock();
        $mock->expects($this->once())->method('handle');
        Test::inject('controller', $mock);
        $this->handler->register();
        try {
            throw new \Exception();
        } catch (\Exception $e) {
            $this->handler->catch($e);
        }
    }
}
