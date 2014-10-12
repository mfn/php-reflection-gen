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
namespace Mfn\PHP\Reflection\Generator\Listener;

use Mfn\PHP\Reflection\Generator\Reflector;

class File implements Listener {

  /**
   * File pointer to write to
   * @var resource
   */
  private $fp;

  public function __construct($fp) {
    if (!is_resource($fp)) {
      throw new \RuntimeException(
        'File pointer (resource) to write to required but ' . gettype($fp)
        . ' given'
      );
    }
    $this->fp = $fp;
  }

  /**
   * Called before generating starts
   */
  public function beforeGenerate() {
    $this->writeln('<?php');
    $this->writeln(
      '# Generated by mfn/php-reflection-gen ' . Reflector::VERSION);
    $this->writeln('# PHP Version ' . phpversion());
  }

  private function writeln($str) {
    $this->write($str . "\n");
  }

  private function write($str) {
    fwrite($this->fp, $str);
  }

  /**
   * Called for every reflected function
   *
   * @param \ReflectionFunction $function
   * @param string $generated
   * @param $generated
   */
  public function generateFunction(\ReflectionFunction $function, $generated) {
    $this->write($generated);
  }

  /**
   * Called for every reflected class, interface or trait
   *
   * @param \ReflectionClass $class
   * @param string $generated
   * @param $generated
   */
  public function generateClass(\ReflectionClass $class, $generated) {
    $this->write($generated);
  }

  /**
   * Called when generating is finished
   */
  public function afterGenerate() {
    // TODO: Implement afterGenerate() method.
  }
}
