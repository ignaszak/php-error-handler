<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.09.16
 * Time: 16:19
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler\Controller;

/**
 * Interface IController
 * @package Ignaszak\ErrorHandler\Controller
 */
interface IController
{

    /**
     * @return array
     */
    public function getData(): array;
}
