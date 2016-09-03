<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 01.09.16
 * Time: 16:57
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler;

use Ignaszak\ErrorHandler\Controller\Controller;
use Ignaszak\ErrorHandler\Plugin\IPlugin;
use Ignaszak\ErrorHandler\View\IView;

/**
 * Class Handler
 * @package Ignaszak\ErrorHandler
 */
class Handler implements IHandler
{

    /**
     * @var Controller
     */
    private $controller = null;

    /**
     * @var IView
     */
    private $view = null;

    /**
     * @var IPlugin[]
     */
    private $plugins = [];

    /**
     * @param IView|null $view
     * @return IHandler
     */
    public static function start(IView $view = null): IHandler
    {
        return new Handler($view);
    }

    /**
     * Handler constructor.
     * @param IView $view
     */
    private function __construct($view)
    {
        $this->controller = new Controller();
        if ($view instanceof IView) {
            $this->view = $view;
            $this->view->setController($this->controller);
        }
    }

    public function __destruct()
    {
        if (! empty($this->controller->getData())) {
            if ($this->view instanceof IView) {
                if (ob_get_contents()) ob_clean();
                $this->view->execute();
            }
            foreach ($this->plugins as $plugin) {
                $plugin->setController($this->controller);
                $plugin->execute();
            }
        }
    }

    /**
     * @param IPlugin $plugin
     */
    public function addPlugin(IPlugin $plugin)
    {
        $this->plugins[] = $plugin;
    }

    public function register()
    {
        set_error_handler([$this->controller, 'handle']);
        register_shutdown_function(function () {
            $error = error_get_last();
            if (count($error)) { // If not empty
                $this->controller->handle(
                    (int)$error['type'],
                    (string)$error['message'],
                    (string)$error['file'],
                    (int)$error['line'],
                    null,
                    'shutdown'
                );
            }
        });
        set_exception_handler(function (\Throwable $e) {
            $this->controller->handle(
                null,
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                null,
                'throwable',
                $e->getTrace(),
                get_class($e),
                $e->getCode(),
                true
            );
        });
        ob_start();
    }

    /**
     * @param \Throwable $e
     */
    public function catch(\Throwable $e)
    {
        $this->controller->handle(
            null,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            null,
            'throwable',
            $e->getTrace(),
            get_class($e),
            $e->getCode()
        );
    }
}
