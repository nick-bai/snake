<?php

use Qsnh\Think\Log\Models\Log;
use PHPUnit\Framework\TestCase;

class LogModelTest extends TestCase
{

    public function testFilesMethod()
    {
        $logModel = new Log;
        $files = $logModel->files();
        $this->assertTrue(is_array($files));
    }

}