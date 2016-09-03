<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 01.09.16
 * Time: 18:26
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler\Plugin;

use Ignaszak\ErrorHandler\Controller\IController;

/**
 * Interface IPlugin
 * @package Ignaszak\ErrorHandler\Plugin
 */
interface IPlugin
{

    /**
     * @param IController $controller
     */
    public function setController(IController $controller);

    public function execute();
}
