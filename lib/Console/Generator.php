<?php declare(strict_types=1);

namespace Mfn\PHP\Reflection\Generator\Console;

use Mfn\PHP\Reflection\Generator\Formatter\Standard;
use Mfn\PHP\Reflection\Generator\Listener\Directory;
use Mfn\PHP\Reflection\Generator\Listener\File;
use Mfn\PHP\Reflection\Generator\Reflector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Generator extends Command {

  protected function configure() {
    $this
      ->setName('generate')
      ->setDescription('Generate PHP reflection data')
      ->addOption(
        'directory',
        NULL,
        InputOption::VALUE_OPTIONAL,
        'Directory to write generated reflection data to' . PHP_EOL
        . 'Note: invidual files will be generated for each "extension" a '
        . 'reflection entity belongs to'
      )
      ->addOption(
        'file',
        NULL,
        InputOption::VALUE_OPTIONAL,
        'File to write generated reflection data to'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $file = $input->getOption('file');
    $dir = $input->getOption('directory');
    $generator = new Reflector(new Standard());
    if (NULL === $file && NULL === $dir) {
      $generator->addListener(new File(\STDOUT));
    }
    if (NULL !== $file) {
      $generator->addListener(new File(fopen($file, 'w')));
    }
    if (NULL !== $dir) {
      $generator->addListener(new Directory($dir));
    }
    $generator->generate();
  }
}
