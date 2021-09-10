<?php

namespace Palpalasi\ViewModel\Providers;

use Illuminate\Support\ServiceProvider;
use Palpalasi\ViewModel\BaseViewModel;
use Palpalasi\ViewModel\Contracts\BaseViewModelContract;
use Palpalasi\ViewModel\Contracts\MasterViewModelContract;
use Palpalasi\ViewModel\MasterViewModel;

class ViewModelServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton(MasterViewModelContract::class, MasterViewModel::class);
        app()->singleton(BaseViewModelContract::class, BaseViewModel::class);
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
