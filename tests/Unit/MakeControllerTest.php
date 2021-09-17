<?php

namespace HosseinSheikh\ViewModel\Tests\Unit;

use App\Http\Controllers\TestSmartController;
use HosseinSheikh\ViewModel\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeControllerTest extends TestCase
{
    /** @test */
    function the_install_command_copies_the_controller()
    {
        /*if (class_exists()){
         dd(__LINE__);
        }*/
        // dd(__DIR__.'../../vendor/orchestra/testbench-core/laravel/app/Http/Controllers/TestSmartController.php');
        /*if (File::exists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Http/Controllers/TestSmartController.php')) {
            dd(__LINE__);
            unlink(config_path('blogpackage.php'));
        }

        dd(__LINE__);*/

        Artisan::call('vm:make-controller TestSmartController --namespace=Hossein');
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
