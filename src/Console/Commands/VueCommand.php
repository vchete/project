<?php

namespace Hcode\Project\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class VueCommand extends Command {

  protected $signature = 'make:hcodeVue';

  protected $description = 'Copy directories Vue';

  protected $directoryTo = [
    'resources/js/components' => 'resources/js/hcode-rename-to-components',
    'esources/js/utils'       => 'resources/js/hcode-rename-to-utils'
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