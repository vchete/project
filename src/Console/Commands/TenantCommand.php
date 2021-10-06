<?php

namespace Hcode\Project\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TenantCommand extends Command {

  protected $signature = 'make:hcodeTenant';

  protected $description = 'Copy directories';

  protected $directoryTo = [
    'Models/Tenant' => 'app/Models/HcodeRenameToTenant',
    'routes'        => 'app/routes',
  ];

  public function handle() {
    $this->exportFiles();
    $this->info('Hcode Tenant, successful.');
  }

  protected function exportFiles() {
    foreach ($this->directoryTo as $directory => $to) {
      File::copyDirectory(__DIR__.'/../../'.$directory, base_path($to));
    }
  }

}