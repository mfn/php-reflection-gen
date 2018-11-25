<?php declare(strict_types=1);

namespace Mfn\PHP\Reflection\Generator\Formatter;

class Standard implements Formatter {

  public function formatFunction(\ReflectionFunction $function) {
    $func = sprintf('function %s(%s) {}',
      $function->getName(),
      $this->formatParameters($function->getParameters())
    );
    return $func . "\n";
  }

  static private function isValidVariableName($name) {
    return preg_match(';[a-z][a-z0-9]*;i', $name);
  }

  public function formatParameters(array $parameters) {
    $pos = 1;
    $params = array_map(function (\ReflectionParameter $param) use(&$pos) {
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
      $name = $param->getName();
      if (!self::isValidVariableName($name)) {
        $name = 'param' . $pos;
      }
      $str .= '$' . $name;
      if ($param->isDefaultValueAvailable()) {
        $str .= ' = ' . $this->formatValue($param->getDefaultValue());
      }
      $pos++;
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
    $parts = [];
    if ($class->isAbstract() && !$class->isInterface()) {
      $parts[] = 'abstract';
    }
    if ($class->isInterface()) {
      $parts[] = 'interface';
    } else if ($class->isTrait()) {
      $parts[] = 'trait';
    } else {
      $parts[] = 'class';
    }
    # TODO : properly handle namespaces
    $parts[] = $class->getName();
    $parent = $class->getParentClass();
    if (false !== $parent) {
      # TODO : properly handle namespaces
      $parts[] = 'extends ' . $parent->getName();
    }
    # TODO : properly handle namespaces
    $interfaces = $class->getInterfaceNames();
    if (count($interfaces) > 0) {
      if ($class->isInterface()) {
        $parts[] = 'extends';
      } else {
        $parts[] = 'implements';
      }
      $parts[] = join(', ', $interfaces);
    }
    $str = join(' ', $parts) . "{\n";
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
    if ($method->isAbstract() && !$method->getDeclaringClass()->isInterface()) {
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
