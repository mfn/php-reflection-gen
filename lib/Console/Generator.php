<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Markus Fischer <markus@fischer.name>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
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
        'Directory to write generated reflection data to'
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
