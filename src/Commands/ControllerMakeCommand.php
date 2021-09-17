<?php

namespace HosseinSheikh\ViewModel\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'vm:make-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    public function handle()
    {
        parent::handle();
        $nameS = null;
        if ($this->hasOption('namespace')) {
            $nameS = $this->option('namespace');
        }

        $via = null;
        if ($this->hasOption('via')) {
            $via = $this->option('via');
        }

        $this->callViewModel('IndexViewModel', $nameS, $via);
        $this->callViewModel('CreateViewModel', $nameS, $via);
        $this->callViewModel('ShowViewModel', $nameS, $via);
        $this->callViewModel('EditViewModel', $nameS, $via);
        $this->callViewModel('StoreViewModel', $nameS, $via);
        $this->callViewModel('UpdateViewModel', $nameS, $via);
        $this->callViewModel('DestroyViewModel', $nameS, $via);
        // $this->call('vm:make-viewmodel', ['name' => 'CreateViewModel', '--controller' => ucfirst($this->getViewModelName())]);
        // $this->call('vm:make-viewmodel', ['name' => 'ShowViewModel', '--controller' => ucfirst($this->getViewModelName())]);
        // $this->call('vm:make-viewmodel', ['name' => 'EditViewModel', '--controller' => ucfirst($this->getViewModelName())]);
        // $this->call('vm:make-viewmodel', ['name' => 'StoreViewModel', '--controller' => ucfirst($this->getViewModelName())]);
        // $this->call('vm:make-viewmodel', ['name' => 'UpdateViewModel', '--controller' => ucfirst($this->getViewModelName())]);
        // $this->call('vm:make-viewmodel', ['name' => 'DestroyViewModel', '--controller' => ucfirst($this->getViewModelName())]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;
        if ($namespace = $this->option('namespace') && $via = $this->option('via')) {
            $stub = '/stubs/controller.via-method-namespace.stub';
        } elseif ($via = $this->option('via')) {
            $stub = "/stubs/controller.via-method.stub";
            // $stub =Str::replace('{{via}}', $via, $stub);
            // $stub = str_replace('{{via}}', $via, $stub);
        } elseif ($namespace = $this->option('namespace')) {
            $stub = '/stubs/controller.namespace.stub';
        } elseif ($this->option('invokable')) {
            $stub = '/stubs/controller.invokable.stub';
        }

        $stub = $stub ?? '/stubs/controller.simple.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);
        $replace = [/*'{{viewmodel_name}}' => $controllerNamespace*/];
        $replace = $this->buildVeiwModelReplacements($replace);

        /*if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }*/

        if ($this->option('via')) {
            $replace = $this->buildViaReplacements($replace);
        }

        if ($this->option('namespace')) {
            $replace = $this->buildNamespaceReplacements($replace);
        }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return array
     */
    protected function buildViaReplacements(array $replace)
    {
        return array_merge($replace, [
            '{{via}}' => $this->option('via'),
        ]);
    }

    protected function buildNamespaceReplacements(array $replace)
    {
        return array_merge($replace, [
            '{{viewmodel_namespace}}' => str_replace('/', '.', strtolower($this->option('namespace'))),
        ]);
    }

    protected function buildVeiwModelReplacements(array $replace)
    {
        return array_merge($replace, [
            '{{viewmodel_name}}' => $this->getViewModelName(),
        ]);

    }

    private function getViewModelName()
    {
        return str_replace('controller', '', strtolower($this->argument('name')));
    }

    /**
     * Build the model replacement values.
     *
     * @param array $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        /*$modelClass = $this->parseModel($this->option('model'));

        if (! class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', ['name' => $modelClass]);
            }
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
        ]);*/
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param string $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['via', null, InputOption::VALUE_REQUIRED, 'Exclude the create and edit methods from the controller.'],
            ['invokable', 'i', InputOption::VALUE_NONE, 'Generate a single method, invokable controller class.'],
            ['namespace', null, InputOption::VALUE_REQUIRED, 'Generate a nested resource controller class.'],
        ];
    }

    /*protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }*/
    /**
     * @return int
     */
    private function callViewModel($name, $namespace = null, $via = null): int
    {
        $call['name'] = $name;

        $call['--controller'] = ucfirst($this->getViewModelName());
        if (!!$namespace) {
            $call['--namespace'] = $namespace;
        }

        if (!!$via) {
            $call['--via'] = $via;
        }

        return $this->call('vm:make-viewmodel', $call);
    }
}
