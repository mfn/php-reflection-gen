<?php declare(strict_types=1);

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
