<?php

namespace Hcode\Project\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TemplateCommand extends Command {

  protected $signature = 'make:hcodeTemplate {template}';

  protected $description = 'Copy directories';

  protected $directoryTo = [
    'adminLTE' => [
      'Templates/AdminLTE/auth'    => 'resources/views/hcode-rename-to-auth',
      'Templates/AdminLTE/layouts' => 'resources/views/hcode-rename-to-layouts',
      'Templates/AdminLTE/sources' => 'public/hcode-rename-to-sources',
    ],
    'apaxy' => [
      'Templates/Apaxy/auth'    => 'resources/views/hcode-rename-to-auth',
      'Templates/Apaxy/layouts' => 'resources/views/hcode-rename-to-layouts',
      'Templates/Apaxy/sources' => 'public/hcode-rename-to-sources',
    ]
  ];

  public function handle() {
    $template = $this->argument('template');

    if (!array_key_exists($template, $this->directoryTo)) {
      $this->info("Template not exist");
      return;
    }
    $this->exportFiles($template);
    $this->info("Hcode Template {$template}, successful.");
  }

  protected function exportFiles($template) {
    foreach ($this->directoryTo[$template] as $directory => $to) {
      File::copyDirectory(__DIR__.'/../../'.$directory, base_path($to));
    }
  }
}