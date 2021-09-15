<?php

namespace HosseinSheikh\ViewModel\Contracts;

use Illuminate\Http\Request;

interface MethodParameterContract
{
    public static function get($viewModel, $actionMethod);
}
