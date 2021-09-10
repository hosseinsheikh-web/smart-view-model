<?php

namespace Palpalasi\ViewModel\Providers;

use Illuminate\Support\ServiceProvider;
use Palpalasi\ViewModel\BaseViewModel;
use Palpalasi\ViewModel\Contracts\BaseViewModelContract;
use Palpalasi\ViewModel\Contracts\SmartViewModelContract;
use Palpalasi\ViewModel\SmartViewModel;

class ViewModelServiceProvider extends ServiceProvider
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

    }
}
