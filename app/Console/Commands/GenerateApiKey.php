<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class GenerateApiKey extends Command
{
    protected $signature = 'api-key:generate';
    protected $description = 'Generate a new API_KEY and store it in the .env file';

    public function handle()
    {
        $apiKey = Str::random(32);

        if (File::exists(base_path('.env'))) {
            $this->setEnvironmentValue('API_KEY', $apiKey);
            $this->info("API_KEY generated successfully: {$apiKey}");
        } else {
            $this->error('.env file not found!');
        }
    }

    private function setEnvironmentValue($key, $value)
    {
        $path = base_path('.env');
        $content = File::get($path);

        if (strpos($content, "{$key}=") !== false) {
            $content = preg_replace("/{$key}=.*/", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        File::put($path, $content);
    }
}
