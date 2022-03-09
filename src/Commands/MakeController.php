<?php

namespace HosseinSheikh\ViewModel\Commands;

use Illuminate\Console\Command;

class MakeController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vm:make-controller3 {controller_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create controller with smart view model';

    protected $type = 'class';

    protected function getStub()
    {
        return base_path().'/Commands/stubs/controller.stub';
    }



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*$controllerName = $this->argument('controller_name');
        $replace = [];
        $this->buildModelReplacements($replace ,$controllerName);
        $stb = require_once 'packages/SmartViewModel/src/Commands/stubs/controller.stub';
        dd($stb);*/
        return 0;
    }


    protected function buildModelReplacements(array $replace, $controllerClass)
    {
        $modelClass = $this->parseModel($controllerClass);
        // dd($modelClass);

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
        ]);
    }
}
