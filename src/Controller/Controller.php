<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 01.09.16
 * Time: 20:43
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler\Controller;

/**
 * Class Controller
 * @package Ignaszak\ErrorHandler\Controller
 */
class Controller implements IController
{

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @var bool
     */
    public $showSuppressedErrors = false;

    /**
     * @var int
     */
    public $suppressedErrorsCounter = 0;

    /**
     * @var Converter
     */
    private $converter = null;

    /**
     * @var int
     */
    private $lastErrorType = 0;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string[]
     */
    private $vars = [];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->converter = new Converter();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (empty($this->data)) {
            $count = count($this->errors);
            if ($count > 0) {
                $this->data['errorsLength'] = $count;
                if ($this->suppressedErrorsCounter > 0) {
                    $this->data['suppressedErrorsLength'] = $this->suppressedErrorsCounter;
                }
                $this->data['globals'] = $this->converter->getGlobals();
                $cons = get_defined_constants(true);
                if (isset($cons['user'])) $this->data['const'] = $cons['user'];
                $vars = $GLOBALS;
                unset(
                    $vars['_GET'],
                    $vars['_POST'],
                    $vars['_COOKIE'],
                    $vars['_FILES'],
                    $vars['_ENV'],
                    $vars['_REQUEST'],
                    $vars['_SERVER'],
                    $vars['GLOBALS']
                );
                if (! empty($vars)) $this->data['vars'] = $this->converter->arrayElementsToString($vars);
                $this->data['errors'] = $this->errors;
            }
        }
        return $this->data;
    }

    /**
     * @param int|null    $number     Error number (for exceptions null)
     * @param string      $message    Error message
     * @param string      $file       Error file
     * @param int         $line       Error line
     * @param array|null  $vars       Array of defined vars (not for exceptions and fatal errors)
     * @param string      $type       Error type
     * @param array|null  $trace      Backtrace (for exceptions)
     * @param string|null $class      Exception class
     * @param int|null    $code       Exception code
     * @param bool|null   $isUncaught Uncaught exception
     */
    public function handle(
        int $number = null,
        string $message,
        string $file,
        int $line,
        array $vars = null,
        string $type = 'error',
        array $trace = null,
        string $class = null,
        int $code = null,
        bool $isUncaught = null
    ) {
        $this->lastErrorType = error_reporting();
        if ($this->hasErrorPermissionToRegister()) {
            $inf = [
                'type'    => $type,
                'name'    => is_null($number) ?
                    $class : $this->converter->getErrorNameByNumber($number),
                'message' => $message,
                'file'    => $file,
                'line'    => $line,
                'trace'   => $this->converter->getTrace($trace),
            ];
            if (! is_null($code)) $inf['code'] = $code;
            if (! is_null($isUncaught)) $inf['isUncaught'] = $isUncaught;
            if ($this->isErrorSuppressed()) {
                ++ $this->suppressedErrorsCounter;
                $inf['isSuppressed'] = true;
            }
            $this->errors[] = $inf;
        }
    }

    /**
     * @return bool
     */
    private function hasErrorPermissionToRegister(): bool
    {
        if ($this->isErrorSuppressed()) {
            return $this->showSuppressedErrors;
        } else {
            return true;
        }
    }

    /**
     * Returns true if last error type is equals to 0 (is suppressed)
     *
     * @return bool
     */
    private function isErrorSuppressed(): bool
    {
        return $this->lastErrorType === 0;
    }
}
