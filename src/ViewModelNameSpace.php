<?php

namespace HosseinSheikh\ViewModel;

use Illuminate\Support\Str;

class ViewModelNameSpace
{
    private $namespaces = [];
    public  function get($namespace, $viewModelPath)
    {
        $viewModel = $this->dotToNamespace($namespace);

        $viewModel =  ltrim($viewModel, '\\');
        $viewModel = $viewModel . "\\ViewModels";

        return $viewModel . $this->dotToNamespace($viewModelPath) . "ViewModel";

    }

  public   function dotToNamespace($namespaces): string
    {
        $this->namespaces = explode('.', $namespaces);
        $viewModel = '';
        foreach ($this->namespaces as $namespace) {
            $viewModel .= "\\" . Str::studly($namespace);
        }

        return $viewModel;
    }

    public function ifNamespaceHasOneIndex(array $viewModelNamespaces)
    {
        if (!isset($viewModelNamespaces[1]) && isset($viewModelNamespaces[0])) {
            $viewModelNamespaces[1] = $viewModelNamespaces[0];
        }

        return $viewModelNamespaces;
    }
}
