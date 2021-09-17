<?php

namespace Hcode\Project\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ViewsCommand extends Command {

  protected $signature = 'make:hcodeViews';

  protected $description = 'Copy directories';

  protected $directoryTo = [
    'resources/views/crud'              => 'resources/views/hcode-rename-to-crud',
    'resources/views/backend/admin'     => 'resources/views/hcode-rename-to-backend/admin',
    'resources/views/backend/dashboard' => 'resources/views/hcode-rename-to-backend/dashboard'
  ];

  public function handle() {
    $this->exportFiles();
    $this->info('Hcode Views, directories copied successful.');
  }

  protected function exportFiles() {
    foreach ($this->directoryTo as $directory => $to) {
      File::copyDirectory(__DIR__.'/../../'.$directory, base_path($to));
    }
  }

}