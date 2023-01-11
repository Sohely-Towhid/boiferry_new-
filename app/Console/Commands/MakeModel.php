<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use Str;

class MakeModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:_model {name} {opt=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Model with BTL Template';

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
        $cmd        = '';
        $controller = false;
        $c_name     = false;
        if ($this->argument('opt')) {
            $cmd        = 'make:model ' . $this->argument('name') . " -" . $this->argument('opt');
            $controller = (preg_match("/cr/", $this->argument('opt'))) ? true : false;
        } else {
            $cmd = 'make:model ' . $this->argument('name');
        }

        Artisan::call($cmd);
        echo Artisan::output();

        if ($controller) {
            $model_name = $this->argument('name');
            $table      = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));
            $c_name     = $this->argument('name') . 'Controller';
            $c_path     = app_path("Http\\Controllers\\" . $c_name . ".php");
            $c_data     = file_get_contents(storage_path('btl_template/controller.txt'));
            $c_data     = str_replace(['<<model>>', '<<Model>>', '<<table>>'], [strtolower($model_name), $model_name, $table], $c_data);
            // Check for Moded Controller
            if (!preg_match('/BTL Controller Template/i', @file_get_contents($c_path))) {
                file_put_contents($c_path, $c_data);
            }

            // Make Views
            @mkdir(config('view.paths')[0] . "\\" . strtolower($model_name), 0777, true);
            $views['index']  = config('view.paths')[0] . "\\" . strtolower($model_name) . "\\index.blade.php";
            $views['create'] = config('view.paths')[0] . "\\" . strtolower($model_name) . "\\create.blade.php";
            $views['edit']   = config('view.paths')[0] . "\\" . strtolower($model_name) . "\\edit.blade.php";
            $views['form']   = config('view.paths')[0] . "\\" . strtolower($model_name) . "\\form.blade.php";
            $views['show']   = config('view.paths')[0] . "\\" . strtolower($model_name) . "\\show.blade.php";
            foreach ($views as $key => $view) {
                if (!preg_match('/BTL Template/i', @file_get_contents($view))) {
                    $template  = file_get_contents(storage_path("btl_template/{$key}.txt"));
                    $view_data = str_replace(['<<model>>', '<<Model>>', '<<table>>'], [strtolower($model_name), $model_name, $table], $template);
                    file_put_contents($view, $view_data);
                }
            }
        }
    }
}
