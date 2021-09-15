<?php

namespace HosseinSheikh\ViewModel\Providers;

use Illuminate\Support\ServiceProvider;
use HosseinSheikh\ViewModel\BaseViewModel;
use HosseinSheikh\ViewModel\Commands\ControllerMakeCommand;
use HosseinSheikh\ViewModel\Commands\ViewModelMakeCommand;
use HosseinSheikh\ViewModel\Contracts\BaseViewModelContract;
use HosseinSheikh\ViewModel\Contracts\SmartViewModelContract;
use HosseinSheikh\ViewModel\SmartViewModel;

class SmartViewModelServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton(SmartViewModelContract::class, SmartViewModel::class);
        app()->singleton(BaseViewModelContract::class, BaseViewModel::class);
        app()->singleton('smart-view-model', SmartViewModel::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerMakeCommand::class,
                ViewModelMakeCommand::class
            ]);
        }
    }
}
