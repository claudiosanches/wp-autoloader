<?php

namespace ClaudioSanches\WPAutoloader\Tests;

use ClaudioSanches\WPAutoloader\Autoloader;

class MockAutoloader extends Autoloader
{
    protected $files = [];

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    protected function requireFile(string $file): bool
    {
        return in_array($file, $this->files);
    }
}
