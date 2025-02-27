<?php

namespace App\Console\Commands;

use App\Models\FileKnowledgeBase;
use Illuminate\Console\Command;

class ManageFileKnowledgeBase extends Command
{
    protected $signature = 'file-knowledge:manage {--clear} {--status}';
    protected $description = 'Manage file-based knowledge base';

    public function handle()
    {
        if ($this->option('clear')) {
            FileKnowledgeBase::truncate();
            $this->info('File knowledge base cleared.');
        }

        if ($this->option('status')) {
            $count = FileKnowledgeBase::count();
            $types = FileKnowledgeBase::select('file_type')
                ->distinct()
                ->pluck('file_type');
            
            $this->info("Total entries: {$count}");
            $this->info("File types: " . $types->join(', '));
        }
    }
}