<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 01.09.16
 * Time: 16:58
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler;

use Ignaszak\ErrorHandler\Plugin\IPlugin;

/**
 * Interface IHandler
 * @package Ignaszak\ErrorHandler
 */
interface IHandler
{

    /**
     * @param IPlugin $plugin
     */
    public function addPlugin(IPlugin $plugin);

    public function register();

    /**
     * @param \Throwable $e
     */
    public function catch(\Throwable $e);
}
