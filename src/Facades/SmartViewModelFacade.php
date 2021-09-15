<?php

namespace HosseinSheikh\ViewModel\Facades;

use Illuminate\Support\Facades\Facade;

class SmartViewModelFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'smart-view-model';
    }
}
