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
namespace Mfn\PHP\Reflection\Generator;

use Mfn\PHP\Reflection\Generator\Formatter\Formatter;
use Mfn\PHP\Reflection\Generator\Listener\Listener;

class Reflector {

  const VERSION = '0.0.4';

  /** @var Formatter */
  private $formatter;
  /** @var Listener[] */
  private $listeners = [];

  public function __construct(Formatter $formatter) {
    $this->formatter = $formatter;
  }

  public function generate() {
    $this->notifyBeforeGenerate();

    $formatter = $this->formatter;

    $allFunctions = get_defined_functions();
    foreach ($allFunctions['internal'] as $functionName) {
      $function = new \ReflectionFunction($functionName);
      $this->notifyGenerateFunction(
        $function,
        $this->formatter->formatFunction($function)
      );
    }

    # Mix classes/interfaces/etc.
    $classes = array_merge(
      get_declared_classes(),
      get_declared_interfaces()
    );

    foreach ($classes as $name) {
      $class = new \ReflectionClass($name);
      if (!$class->isInternal()) {
        continue;
      }
      $this->notifyGenerateClass(
        $class,
        $formatter->formatClass($class)
      );
    }
    $this->notifyAfterGenrate();
  }

  private function notifyBeforeGenerate() {
    foreach ($this->listeners as $listener) {
      $listener->beforeGenerate();
    }
  }

  private function notifyGenerateFunction(\ReflectionFunction $function,
                                          $generated) {
    foreach ($this->listeners as $listener) {
      $listener->generateFunction($function, $generated);
    }
  }

  private function notifyGenerateClass(\ReflectionClass $class, $generated) {
    foreach ($this->listeners as $listener) {
      $listener->generateClass($class, $generated);
    }
  }

  private function notifyAfterGenrate() {
    foreach ($this->listeners as $listener) {
      $listener->afterGenerate();
    }
  }

  /**
   * @return Formatter
   */
  public function getFormatter() {
    return $this->formatter;
  }

  /**
   * @param Formatter $formatter
   * @return $this
   */
  public function setFormatter($formatter) {
    $this->formatter = $formatter;
    return $this;
  }

  /**
   * @param Listener $listener
   * @return $this
   */
  public function addListener(Listener $listener) {
    $this->listeners[] = $listener;
    return $this;
  }
}
