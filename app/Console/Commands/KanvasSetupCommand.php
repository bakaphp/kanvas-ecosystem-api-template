<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema as FacadesSchema;
use Kanvas\Enums\AppEnums;

class KanvasSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanvas:setup-ecosystem';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Setup the ecosystem for Kanvas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (FacadesSchema::hasTable('migration')) {
            $this->info('Some migrations have already been run. Meaning the ecosystem is already setup, Skipping setup.');

            return;
        }

        $commands = [
            'migrate',
            'db:seed',
            'kanvas:create-role Admin',
            'kanvas:create-role Users',
            'kanvas:create-role Agents',
            'kanvas:filesystem-setup',
        ];

        foreach ($commands as $command) {
            $this->line('Running command: ' . $command);
            $exitCode = Artisan::call($command);

            if ($exitCode !== 0) {
                $this->error('Command failed: ' . $command);

                break;
            }
        }

        $this->info('All commands executed successfully - Welcome to Kanvas Ecosystem ' . AppEnums::VERSION->getValue());

        return;
    }
}
