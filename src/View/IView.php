<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 01.09.16
 * Time: 18:00
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler\View;

use Ignaszak\ErrorHandler\Controller\IController;

/**
 * Interface IView
 * @package Ignaszak\ErrorHandler\View
 */
abstract class IView
{

    /**
     * @var IController
     */
    protected $controller = null;

    abstract public function execute();

    /**
     * @param IController $controller
     */
    public function setController(IController $controller)
    {
        $this->controller = $controller;
    }
}
