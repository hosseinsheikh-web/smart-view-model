<?php

namespace Palpalasi\ViewModel;

use Palpalasi\ViewModel\Contracts\MethodParameterContract;

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
