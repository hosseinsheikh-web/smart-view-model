<?php

namespace HosseinSheikh\ViewModel\Tests;

use HosseinSheikh\ViewModel\Providers\SmartViewModelServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
abstract class TestCase extends BaseTestCase
{
    public function getPackageProviders()
    {
        return [SmartViewModelServiceProvider::class];
    }

    public function getEnvironmentSetUp() { }

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

}
