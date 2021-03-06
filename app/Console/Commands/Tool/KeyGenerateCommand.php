<?php

namespace App\Console\Commands\Tool;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class KeyGenerateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application key';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->laravel->configure('app');

        $key = $this->getRandomKey($this->laravel['config']['app.cipher']);

        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'</comment>');
        }

        $path = base_path('config/env_'.getenv('ENV_LUMEN_ENV'));

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'APP_KEY='.$this->laravel['config']['app.key'], 'APP_KEY='.$key, file_get_contents($path)
            ));
        }

        $this->laravel['config']['app.key'] = $key;

        $this->info("Application key [$key] set successfully.");
    }

    /**
     * Generate a random key for the application.
     *
     * @param  string  $cipher
     * @return string
     */
    protected function getRandomKey($cipher)
    {
        if ($cipher === 'AES-128-CBC') {
            return Str::random(16);
        }

        return Str::random(32);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['show', null, InputOption::VALUE_NONE, 'Simply display the key instead of modifying files.'],
        ];
    }
}
