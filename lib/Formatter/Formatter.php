<?php

namespace Mfn\PHP\Reflection\Generator\Formatter;


interface Formatter {

  /**
   * Formats a `value` from a \ReflectParameter
   *
   * @param mixed $value
   * @return string
   */
  public function formatValue($value);

  /**
   * Format all parameters for a function/method.
   *
   * Usually the "stuff" between `(` and `)`
   *
   * @param \ReflectionParameter[] $parameters
   * @return string
   */
  public function formatParameters(array $parameters);

  /**
   * Format a function
   *
   * @param \ReflectionFunction $function
   * @return string
   */
  public function formatFunction(\ReflectionFunction $function);

  /**
   * Format a method
   *
   * @param \ReflectionMethod $method
   * @return string
   */
  public function formatMethod(\ReflectionMethod $method);

  /**
   * Format a class, interface or trait
   *
   * @param \ReflectionClass $class
   * @return string
   */
  public function formatClass(\ReflectionClass $class);
}
