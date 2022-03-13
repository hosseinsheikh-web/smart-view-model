<?php

namespace HosseinSheikh\ViewModel;

use HosseinSheikh\ViewModel\Contracts\MethodParameterContract;

class MethodParameter implements MethodParameterContract
{

    /**
     * @param $viewModel
     * @param $actionMethod
     * @return array
     * @throws \ReflectionException
     */
    public static function get($viewModel, $actionMethod)
    {
        $viewModelReflection = new \ReflectionClass($viewModel);
        $maybeEncodeReflection = $viewModelReflection->getMethod($actionMethod);
        $maybeEncodeReflection->setAccessible(true);
        $parameters = [];
        foreach ($maybeEncodeReflection->getParameters() as $method) {
            if ($method->getType() && class_exists($method->getType()->getName())) {
                $parameters[] = resolve($method->getType()->getName());
            } elseif ($method->getType() && ! class_exists($method->getType()->getName())) {
                if (gettype(request()->{$method->getName()}) == $method->getType()->getName()) {
                    $parameters[] = request()->{$method->getName()};
                } else {
                    $urlType = gettype(request()->{$method->getName()});
                    $methodVarType = $method->getType()->getName();

                    throw new \TypeError("Type of {$method->getName()} ( $urlType ) is not match with ( $methodVarType ) type of method argument");
                }
            } else {
                $parameters[] = request()->{$method->getName()};
            }
        }

        return $parameters;
    }
}
