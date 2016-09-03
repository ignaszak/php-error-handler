<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.09.16
 * Time: 13:00
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler\Controller;

/**
 * Class Converter
 * @package Ignaszak\ErrorHandler\Controller
 */
class Converter
{

    /**
     * @param int $number
     * @return string
     */
    public function getErrorNameByNumber(int $number): string
    {
        $number &= error_reporting(E_ALL);
        switch ($number) {
            case 0:
                return "Suppressed error";
            case E_ERROR:
                return "Fatal error";
            case E_WARNING:
                return "Warning";
            case E_PARSE:
                return "Parse error";
            case E_NOTICE:
                return "Notice";
            case E_CORE_ERROR:
                return "Core error";
            case E_CORE_WARNING:
                return "Core warning";
            case E_COMPILE_ERROR:
                return "Compile error";
            case E_COMPILE_WARNING:
                return "Compile warning";
            case E_USER_ERROR:
                return "User error";
            case E_USER_WARNING:
                return "User warning";
            case E_USER_NOTICE:
                return "User notice";
            case E_STRICT:
                return "Strict notice";
            case E_RECOVERABLE_ERROR:
                return "Recoverable error";
            case E_DEPRECATED:
                return "Deprecated error";
            case E_USER_DEPRECATED:
                return "User deprecated error";
            default:
                return "Unknown error ($number)";
        }
    }

    /**
     * @param array|null $trace
     * @return array
     */
    public function getTrace(array $trace = null): array
    {
        if (is_null($trace)) {
            $trace = debug_backtrace();
            unset($trace[0], $trace[1]);
        }
        $result = [];
        foreach ($trace as $e) {
            if (isset($e['args'])) $e['args'] = $this->arrayElementsToString($e['args']);
            $result[] = $e;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getGlobals(): array
    {
        $globals['SERVER'] = $_SERVER;
        if (isset($_GET) && ! empty($_GET)) $globals['GET'] = $_GET;
        if (isset($_POST) && ! empty($_POST)) $globals['POST'] = $_POST;
        if (isset($_SESSION) && ! empty($_SESSION)) $globals['SESSION'] = $_SESSION;
        if (isset($_COOKIE) && ! empty($_COOKIE)) $globals['COOKIE'] = $_COOKIE;
        if (isset($_FILES) && ! empty($_FILES)) $globals['FILES'] = $_FILES;
        if (isset($_ENV) && ! empty($_ENV)) $globals['ENF'] = $_ENV;
        $result = [];
        foreach ($globals as $key => $array) {
            $result[$key] = $this->arrayElementsToString($array);
        }
        return $result;
    }

    /**
     * @param array $array
     * @return array
     */
    public function arrayElementsToString(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $type = gettype($value);
            switch ($type) {
                case 'boolean':
                    $result[$key] = $value ? 'true' : 'false';
                    break;
                case 'integer':
                case 'double':
                case 'NULL':
                    $result[$key] = (string)$value;
                    break;
                case 'string':
                    $result[$key] = htmlspecialchars(trim($value));
                    break;
                case 'array':
                    $result[$key] = print_r($value, true);
                    break;
                case 'object':
                    $result[$key] = get_class($value) . ' (object)';
                    break;
                case 'resource':
                    $result[$key] = get_resource_type($value) . ' (resource)';
                    break;
                default:
                    $result[$key] = 'unknown type';
                    break;
            }
        }
        return $result;
    }
}
