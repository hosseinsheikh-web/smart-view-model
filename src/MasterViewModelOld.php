<?php

namespace Palpalasi\ViewModel;

use Illuminate\Support\Str;
use Palpalasi\ViewModel\Contracts\BaseViewModelContract;
// use Palpalasi\ViewModel\Contracts\MasterViewModelContract;
use Palpalasi\ViewModel\Traits\RequestTrait;

class MasterViewModel implements MasterViewModelContract
{
    // use ConfigThemeTrait;
    use RequestTrait;

    private $view_model;
    private $module_name;
    private $action_method = 'render'; //default method
    private $result;
    private $route_name;
    private $content = null;
    /**
     * @var BaseViewModelContract
     */
    private $baseViewModel;
    private $namespace = 'App';

    public function __construct(BaseViewModelContract $baseViewModel)
    {
        $this->baseViewModel = $baseViewModel;
    }

    /*public function __destruct()
    {
        return $this->response();
    }*/

    public function setViewModel($viewModel)
    {
        $this->view_model = $viewModel;

        return $this;
    }

    /**
     * @param $moduleName
     * @return $this
     */
    public function setModuleName($moduleName = null)
    {
        // $this->module_name = $moduleName ?: 'core';d
        // dd($this->getConfig("core")->config);
        $this->module_name = $moduleName ?: null;

        return $this;
    }

    public function response()
    {
        if (!empty($this->module_name)){
            $this->setModuleName($this->module_name);
        }

        if (!empty($this->action_method)){
            $this->setActionMethod($this->action_method);
        }


        $viewModel = $this->getViewModelNamespace();

        $viewModel = resolve($viewModel);
        $parameters = $this->getMethodParameters($viewModel);
        return $viewModel->setModuleName($this->getModuleName())->{$this->getActionMethod()}(...$parameters);
    }

    private function getViewModelNamespace()
    {
        if ($this->hasNamespace()) {
            $namespaces = explode('.', $this->getNamespace());

            $viewModel = $this->namespaceToString($namespaces);
            $viewModel =  ltrim($viewModel, '\\');
            $viewModel = $viewModel . "\\ViewModels";
            // dd($viewModel);
        } else {
            $viewModel = "\\Modules\\Api\\" . ucfirst($this->module_name) . "\\ViewModels";
        }

        $viewModelNamespaces = explode('.', $this->view_model);
        $viewModelNamespaces = $this->ifNamespaceHasOneIndex($viewModelNamespaces);

        return $viewModel . $this->namespaceToString($viewModelNamespaces) . "ViewModel";
    }

    /**
     * @param $method
     * @return $this
     */
    public function setActionMethod($method = null)
    {
        $this->action_method = $method ?: app('core')->getMethodName();

        return $this;
    }

    public function getActionMethod()
    {
        return $this->action_method;
    }

    public function getModuleName()
    {
        return $this->module_name;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function hasNamespace()
    {
        return $this->namespace != null;
    }

    /**
     * @param array $namespaces
     * @param string $viewModel
     * @return string
     */
    private function namespaceToString(array $namespaces): string
    {
        $viewModel = '';
        foreach ($namespaces as $namespace) {
            $viewModel .= "\\" . Str::studly($namespace);
        }

        return $viewModel;
    }

    /**
     * @param array $viewModelNamespaces
     * @return array
     */
    private function ifNamespaceHasOneIndex(array $viewModelNamespaces)
    {
        if (!isset($viewModelNamespaces[1]) && isset($viewModelNamespaces[0])) {
            $viewModelNamespaces[1] = $viewModelNamespaces[0];
        }

        return $viewModelNamespaces;
    }

    public function setItemsRequest(array $items = [], $request = null)
    {
        if (!$this->request && !$request) {
            request()->request->add($items);

            return $this;
        }

        if ($request) {
            $this->request = $request;
        }

        if (empty(array_keys($items))) {
            return $this;
        }

        $this->request->request->add($items);

        return $this;
    }

    public function getMethodParameters($viewModel): array
    {
        $viewModelReflection = new \ReflectionObject($viewModel);
        $maybeEncodeReflection = $viewModelReflection->getMethod($this->getActionMethod());
        $maybeEncodeReflection->setAccessible(true);

        $parameters = [];
        foreach ($maybeEncodeReflection->getParameters() as $method) {
            $parameters[] = resolve($method->getClass()->getName());
        }

        return $parameters;
    }
}

