<?php

namespace HosseinSheikh\ViewModel;

use HosseinSheikh\ViewModel\Contracts\BaseViewModelContract;
use HosseinSheikh\ViewModel\Contracts\SmartViewModelContract;
use HosseinSheikh\ViewModel\Traits\RequestTrait;
use Throwable;

class SmartViewModel implements SmartViewModelContract
{
    // use ConfigThemeTrait;
    use RequestTrait;

    private $view_model;
    private $view_model_full_path;
    private $method = 'render'; //default method
    private $result;
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
        return $this->thenReturn();
    }*/

    public function throughViewModel($viewModel)
    {
        $this->view_model = $viewModel;

        return $this;
    }

    public function through($viewModel)
    {
        $this->view_model_full_path = $viewModel;

        return $this;
    }

    public function thenReturn()
    {
        try {
            $this->via($this->method);

            $viewModel = $this->getViewModelNamespace();

            $viewModel = resolve($viewModel);

            if ($viewModel->showQueryLog){
                 \DB::enableQueryLog();
             }

            $parameters = $this->getMethodParameters($viewModel);

            $data = $viewModel->{$this->getMethod()}(...$parameters);

            if ($viewModel->showQueryLog){
                return \DB::getQueryLog();
             }
             
             return $data;
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    private function getViewModelNamespace()
    {
        if (!!$this->view_model_full_path) {
            return resolve(ViewModelNameSpace::class)->getFullPath($this->view_model_full_path);
        }

        return resolve(ViewModelNameSpace::class)->get($this->getNamespace(), $this->view_model);
    }

    /**
     * @param $method
     * @return $this
     */
    public function via($method = null)
    {
        $this->method = $method ?: $this->method;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
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

    public function addItems(array $items = [], $request = null)
    {
        $items = collect($items)->filter(function ($items, $index) {
            return !is_numeric($index);
        })->toArray();

        if (!$this->request && !$request) {
            request()->request->add($items);

            return $this;
        }

        if ($request) {
            $this->request = $request;
        }

        $this->request->request->add($items);

        return $this;
    }

    public function getMethodParameters($viewModel): array
    {
        return MethodParameter::get($viewModel, $this->getMethod());
    }

    /**
     * Handle the given exception.
     *
     * @param \Throwable $e
     * @return mixed
     *
     * @throws \Throwable
     */
    protected function handleException(Throwable $e)
    {
        throw $e;
    }
}

