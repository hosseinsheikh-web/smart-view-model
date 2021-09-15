<?php

namespace HosseinSheikh\ViewModel;

use HosseinSheikh\ViewModel\Contracts\MethodParameterContract;

class MethodParameter implements MethodParameterContract
{
    public static function get($viewModel, $actionMethod)
    {
        $viewModelReflection = new \ReflectionObject($viewModel);
        $maybeEncodeReflection = $viewModelReflection->getMethod($actionMethod);
        $maybeEncodeReflection->setAccessible(true);
        $parameters = [];
        foreach ($maybeEncodeReflection->getParameters() as $method) {
            $parameters[] = resolve($method->getClass()->getName());
        }

        return $parameters;
    }
}
