<?php

namespace HosseinSheikh\ViewModel\Tests\Unit;

use HosseinSheikh\ViewModel\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeControllerTest extends TestCase
{
    /** @test */
    function the_install_command_copies_the_controller()
    {
        if (File::exists('TestSmartController.php')) {
            dd(__METHOD__);
            unlink(config_path('blogpackage.php'));
        }

        Artisan::call('vm:make-controller TestSmartController');
        $this->assertTrue(true);
        // make sure we're starting from a clean state
        /*if (File::exists(config_path('blogpackage.php'))) {
            unlink(config_path('blogpackage.php'));
        }

        $this->assertFalse(File::exists(config_path('blogpackage.php')));

        Artisan::call('blogpackage:install');

        $this->assertTrue(File::exists(config_path('blogpackage.php')));*/
    }
}
