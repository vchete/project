<?php

namespace Hcode\Project\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CrudCommand extends Command {

  protected $signature = 'make:hcodeCrud';

  protected $description = 'Initial Crud';

  protected $directoryTo = [
    'Http/Controllers/Auth'    => 'app/Http/Controllers/HcodeRenameToAuth',
    'Http/Controllers/Backend' => 'app/Http/Controllers/HcodeRenameToBackend',
    'Models/Auth'              => 'app/Models/HcodeRenameToAuth',
    'database/seeds'           => 'database/seeds',
  ];

  public function handle() {
    $this->exportFiles();
    $this->info('Hcode Crud, successful.');
  }

  protected function exportFiles() {
    foreach ($this->directoryTo as $directory => $to) {
      File::copyDirectory(__DIR__.'/../../'.$directory, base_path($to));
    }
  }

}