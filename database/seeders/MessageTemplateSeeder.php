<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MessageTemplate;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = MessageTemplate::getDefaultTemplates();
        
        foreach ($templates as $template) {
            MessageTemplate::create($template);
        }
        
        $this->command->info('Templates de messages créés avec succès !');
    }
}