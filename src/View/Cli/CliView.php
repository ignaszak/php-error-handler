<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 17.04.17
 * Time: 17:14
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler\View\Cli;

use Colors\Color;
use Ignaszak\ErrorHandler\View\IView;

/**
 * Class CliView
 * @package Ignaszak\ErrorHandler\View\Cli
 */
class CliView extends IView
{

    public function execute()
    {
        $data = $this->controller->getData();
        $c = new Color();
        foreach ($data['errors'] ?? [] as $key => $error) {
            $key++;
            echo $c("\n\n\t{$key}. {$error['name']}")->black()->bold()->highlight('red');
            echo $c("\n\t" . $error['message'] . ' in ')->black()->highlight('red');
            echo $c($error['file'])->black()->bold()->highlight('red');
            echo $c(' on line ')->black()->highlight('red');
            echo $c("{$error['line']}\n")->black()->bold()->highlight('red');
        }
        echo PHP_EOL . PHP_EOL;
    }
}
