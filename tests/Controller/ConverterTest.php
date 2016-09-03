<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.09.16
 * Time: 13:04
 */

declare(strict_types=1);

namespace Test\Controller;

use Ignaszak\ErrorHandler\Controller\Converter;

/**
 * Class ConverterTest
 * @package Test\Controller
 */
class ConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Converter
     */
    private $converter = null;

    public function setUp()
    {
        $this->converter = new Converter();
    }

    public function testGetErrorNameByNumber()
    {
        $this->assertEquals(
            'Suppressed error',
            $this->converter->getErrorNameByNumber(0)
        );
        $this->assertEquals(
            'Fatal error',
            $this->converter->getErrorNameByNumber(E_ERROR)
        );
        $this->assertEquals(
            'Warning',
            $this->converter->getErrorNameByNumber(E_WARNING)
        );
        $this->assertEquals(
            'Parse error',
            $this->converter->getErrorNameByNumber(E_PARSE)
        );
        $this->assertEquals(
            'Notice',
            $this->converter->getErrorNameByNumber(E_NOTICE)
        );
        $this->assertEquals(
            'Core error',
            $this->converter->getErrorNameByNumber(E_CORE_ERROR)
        );
        $this->assertEquals(
            'Core warning',
            $this->converter->getErrorNameByNumber(E_CORE_WARNING)
        );
        $this->assertEquals(
            'Compile error',
            $this->converter->getErrorNameByNumber(E_COMPILE_ERROR)
        );
        $this->assertEquals(
            'Compile warning',
            $this->converter->getErrorNameByNumber(E_COMPILE_WARNING)
        );
        $this->assertEquals(
            'User error',
            $this->converter->getErrorNameByNumber(E_USER_ERROR)
        );
        $this->assertEquals(
            'User warning',
            $this->converter->getErrorNameByNumber(E_USER_WARNING)
        );
        $this->assertEquals(
            'User notice',
            $this->converter->getErrorNameByNumber(E_USER_NOTICE)
        );
        $this->assertEquals(
            'Strict notice',
            $this->converter->getErrorNameByNumber(E_STRICT)
        );
        $this->assertEquals(
            'Recoverable error',
            $this->converter->getErrorNameByNumber(E_RECOVERABLE_ERROR)
        );
        $this->assertEquals(
            'Deprecated error',
            $this->converter->getErrorNameByNumber(E_DEPRECATED)
        );
        $this->assertEquals(
            'User deprecated error',
            $this->converter->getErrorNameByNumber(E_USER_DEPRECATED)
        );
        $this->assertRegExp(
            '/Unknown error \([0-9]*\)/',
            $this->converter->getErrorNameByNumber(722956451465)
        );
    }
}
