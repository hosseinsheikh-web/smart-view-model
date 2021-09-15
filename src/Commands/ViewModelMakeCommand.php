<?php

namespace Palpalasi\ViewModel\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class ViewModelMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'vm:make-viewmodel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'ViewModel';

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
        } elseif ($namespace = $this->option('namespace')) {
            $stub = '/stubs/viewmodel.stub';
        }

        $stub = $stub ?? '/stubs/viewmodel.stub';

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
        /*if (!!$this->option('namespace')){
            return ''.str_replace('/', '\\', $this->option('namespace'));
        }*/
        $c = str_replace('Controller', '', $this->option('controller'));

        return $rootNamespace . '\\ViewModels\\' . $c;
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


            $replace = !!$this->option('via') ?
                $this->buildViaReplacements($replace, $this->option('via')) :
                $this->buildViaReplacements($replace, 'render');


        // if ($this->option('namespace')) {
            $replace = $this->buildNamespaceReplacements($replace);
        // }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return array
     */
    protected function buildViaReplacements(array $replace, $via)
    {
        return array_merge($replace, [
            '{{ viewmodel_method }}' => $via,
        ]);
    }

    protected function buildNamespaceReplacements(array $replace)
    {
        return array_merge($replace, [
            '{{ viewmodel_namespace }}' => $this->option('namespace') ?
                str_replace('/', '\\', $this->option('namespace')) :
                str_replace('\\\\', "\\", $this->getDefaultNamespace($this->rootNamespace())),
        ]);
    }

    protected function buildVeiwModelReplacements(array $replace)
    {
        return array_merge($replace, [
            '{{ viewmodel_name }}' => $this->argument('name'),
        ]);

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
            ['controller', null, InputOption::VALUE_REQUIRED, 'Exclude the create and edit methods from the controller.'],
            ['namespace', null, InputOption::VALUE_REQUIRED, 'Generate a nested resource controller class.'],
        ];
    }

    /*protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }*/

    public function handle()
    {
        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');

            return false;
        }

        $name = $this->qualifyClass($this->getNameInput());
        if (!!$this->option('namespace')) {
            $name = $this->arguments('name')['name'];
            $path = Str::replace('/app', '', $this->laravel['path']);
            $name_space = $this->option('namespace');
            $path = $path . '/' . str_replace('\\', '/', $name_space) . '/'.$name.'.php';
        } else {
            $path = $this->getPath($name);
            $name_space = $this->getDefaultNamespace($this->rootNamespace());
        }




        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exists!');

            return false;
        }
// dd($path);
        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);


        // $build = str_replace('{{ viewmodel_namespace }}', $name_space, $this->buildClass($name));
        $this->files->put($path, $this->sortImports($this->buildClass($name)));


        $this->info($this->type . ' created successfully.');
    }
}
