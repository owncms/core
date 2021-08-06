<?php


namespace Modules\Core\src\FormBuilder\Console;

use Illuminate\Console\Command;
use \Module;

class FormMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-form {module} {form_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an form instance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->checkModuleExist();
        $this->createFormClass();
    }

    public function checkModuleExist()
    {
        // todo
        $modules = ['admin', 'core'];
        if (!in_array(strtolower($this->argument('module')), $modules)) {
            return $this->error('The module ' . $this->argument('module') . ' was not found');
        }
    }

    public function createFormClass()
    {
        $module_path = module_path(ucfirst($this->argument('module')));
        if (!file_exists($module_path . '/Forms/')) {
            mkdir($module_path . '/Forms/', 0777);
            $this->info("The directory src has been created.");
        }
        if ($this->argument('form_name')) {
            if (file_exists($module_path . '/Forms/' . $this->argument('form_name') . '.php')) {
                return $this->error('This file already exists.');
            }

            $stub_content_file = file_get_contents($this->getStubFile());
            $stub_content_file = str_replace('{{class}}', $this->argument('form_name'), $stub_content_file);
            $namespace = 'Module\\' . ucfirst($this->argument('module')) . '\\Forms';
            $stub_content_file = str_replace('{{namespace}}', $namespace, $stub_content_file);
            file_put_contents($module_path . '/Forms/' . $this->argument('form_name') . '.php', $stub_content_file);
        }
    }

    protected function getStubFile()
    {
        return __DIR__ . '/stubs/form-class.stub';
    }

}
