<?php

namespace HosseinSheikh\ViewModel;

use Str;

class ViewModelNameSpace
{
    private $namespaces = [];

    public function get($namespace, $viewModelPath)
    {
        $viewModel = $this->dotToNamespace($namespace);

        $viewModel = ltrim($viewModel, '\\');

        $viewModel = $viewModel . "\\ViewModels";

        if (Str::contains($this->dotToNamespace($viewModelPath), 'ViewModel')) {
            return $viewModel . $this->dotToNamespace($viewModelPath);
        }

        return $viewModel . $this->dotToNamespace($viewModelPath) . "ViewModel";

    }

    public function getFullPath($viewModelPath)
    {
        $viewModel = $this->dotToNamespace($viewModelPath);

        $viewModel = ltrim($viewModel, '\\');
return  rtrim($this->dotToNamespace($viewModelPath), 'ViewModel'). "ViewModel";
        if (Str::contains($this->dotToNamespace($viewModelPath), 'ViewModel')) {
            return $viewModel;
        }

        return $viewModel . "ViewModel";

    }

    public function dotToNamespace($namespaces): string
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
