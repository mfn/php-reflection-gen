<?php declare(strict_types=1);

namespace Mfn\PHP\Reflection\Generator;

use Mfn\PHP\Reflection\Generator\Formatter\Formatter;
use Mfn\PHP\Reflection\Generator\Listener\Listener;

class Reflector {

  const VERSION = '0.0.5';

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
