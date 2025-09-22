<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class InitializeSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize default settings in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initializing default settings...');
        
        Setting::initializeDefaults();
        
        $this->info('Default settings initialized successfully!');
        $this->line('You can now configure your SMTP and PayPal settings through the admin panel.');
        
        return 0;
    }
}