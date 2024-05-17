<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageLinkCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and create storage link if it does not exist';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');

        if (!File::exists($linkPath)) {
            File::link($targetPath, $linkPath);
            $this->info('The [public/storage] link has been connected.');
        } else {
            $this->info('The [public/storage] link already exists.');
        }

        return 0;
    }
}
