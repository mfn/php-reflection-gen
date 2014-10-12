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
namespace Mfn\PHP\Reflection\Generator\Formatter;

class Standard implements Formatter {

  public function formatFunction(\ReflectionFunction $function) {
    $func = sprintf('function %s(%s) {}',
      $function->getName(),
      $this->formatParameters($function->getParameters())
    );
    return $func . "\n";
  }

  public function formatParameters(array $parameters) {
    $params = array_map(function (\ReflectionParameter $param) {
      $str = '';
      if ($param->isArray()) {
        $str .= 'array ';
      }
      if ($param->isCallable()) {
        $str .= 'callable ';
      }
      if (NULL !== $param->getClass()) {
        $str .= $param->getClass()->getName() . ' ';
      }
      if ($param->isPassedByReference()) {
        $str .= '&';
      }
      $str .= '$' . $param->getName();
      if ($param->isDefaultValueAvailable()) {
        $str .= ' = ' . $this->formatValue($param->getDefaultValue());
      }
      return $str;
    }, $parameters);
    return join(', ', $params);
  }

  public function formatValue($value) {
    switch (gettype($value)) {
      case 'boolean':
        return $value === true ? 'true' : 'false';
      case 'double':
      case 'integer':
        return (string) $value;
      case 'string':
        return '\'' . str_replace('\'', '\\\'', $value) . '\'';
      case 'NULL':
        return 'NULL';
      case 'array':
        $tuples = [];
        foreach ($value as $k => $v) {
          $tuples[] = $this->formatValue($k) . ' => ' . $this->formatValue($v);
        }
        return '[ ' . join(', ', $tuples) . ' ]';
      default:
        throw new \RuntimeException('Unsupported type ' . gettype($value));
    }
  }

  public function formatClass(\ReflectionClass $class) {
    $str = '';
    if ($class->isInterface()) {
      $str .= 'interface';
    } else if ($class->isTrait()) {
      $str .= 'trait';
    } else {
      $str .= 'class';
    }
    # TODO : properly handle namespaces
    $str .= ' ' . $class->getName() . ' ';
    $parent = $class->getParentClass();
    if (false !== $parent) {
      # TODO : properly handle namespaces
      $str .= 'extends ' . $parent->getName();
    }
    # TODO : properly handle namespaces
    $interfaces = $class->getInterfaceNames();
    if (count($interfaces) > 0) {
      if ($class->isInterface()) {
        $str .= 'extends ';
      } else {
        $str .= 'implements ';
      }
      $str .= join(', ', $interfaces);
    }
    $str .= "{\n";
    foreach ($class->getMethods() as $method) {
      $str .= '  ' . $this->formatMethod($method);
    }
    return $str . "}\n";
  }

  public function formatMethod(\ReflectionMethod $method) {
    $str = '';
    if ($method->isFinal()) {
      $str .= 'final ';
    }
    if ($method->isStatic()) {
      $str .= 'static ';
    }
    if ($method->isAbstract()) {
      $str .= 'abstract ';
    }
    if ($method->isPrivate()) {
      $str .= 'private ';
    }
    if ($method->isProtected()) {
      $str .= 'protected ';
    }
    if ($method->isPublic()) {
      $str .= 'public ';
    }
    $str .= sprintf('function %s(%s) {}',
      $method->getName(),
      $this->formatParameters($method->getParameters())
    );
    return $str . "\n";
  }

}
