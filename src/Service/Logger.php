<?php


namespace App\Service;


use Symfony\Component\HttpKernel\KernelInterface;

class Logger
{
    private $kernel;
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function info($text, $file = 'logs') {
        file_put_contents($this->kernel->getProjectDir(). '/'. $file .'.txt', $text.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
}
