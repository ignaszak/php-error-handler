<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.09.16
 * Time: 16:34
 */

declare(strict_types=1);

namespace Ignaszak\ErrorHandler\View\Dev;

use Ignaszak\ErrorHandler\View\IView;

/**
 * Class DevView
 * @package Ignaszak\ErrorHandler\View\Dev
 */
class DevView extends IView
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $files = [
        'full'  => [],
        'error'   => [],
        'trace' => []
    ];

    public function execute()
    {//echo '<pre>';
        $this->data = $this->controller->getData();//print_r($this->data);exit;
        $this->setFilesContent();//print_r($this->files);exit;
        include __DIR__ . '/theme/index.html';
    }

    private function setFilesContent()
    {
        foreach($this->data['errors'] as $key => $error) {
            if (! array_key_exists($error['file'], $this->files['full'])) {
                $this->files['full'][$error['file']] = file_get_contents($error['file']);
            }
            $this->files['error'][] = $this->previewFile(
                $error['file'], $error['line']
            );
            foreach ($error['trace'] as $trace) {
                if (! array_key_exists($trace['file'], $this->files['full'])) {
                    $this->files['full'][$trace['file']] = file_get_contents($trace['file']);
                }
                $this->files['trace'][$key][] = $this->previewFile(
                    $trace['file'], $trace['line'], 5
                );
            }
        }
    }

    private function previewFile(string $file, int $line, int $offset = 12): array
    {
        if (file_exists($file) && is_readable($file) && $line > 0) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $diff = $line - $offset;
            $begin = $diff < 0 ? 0 : $diff;
            $body = '';
            $fileIterator = new \LimitIterator(
                new \SplFileObject($file), $begin, $offset*2
            );
            $stop = false;
            $counter = 1;
            foreach ($fileIterator as $row) {
                if (! $stop &&  $row == PHP_EOL){
                    ++ $counter;
                } else {
                    $stop = true;
                }
                $body .= $row;
            }
            $return = [
                'brush'     => $ext,
                'firstLine' => $begin + $counter,
                'highlight' => $line,
                'body'      => $body
            ];
        }
        return $return ?? [];
    }
}
