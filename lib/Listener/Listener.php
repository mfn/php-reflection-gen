<?php

namespace Mfn\PHP\Reflection\Generator\Listener;


interface Listener {

  /**
   * Called before generating starts
   */
  public function beforeGenerate();

  /**
   * Called for every reflected function
   *
   * @param \ReflectionFunction $function
   * @param string $generated
   * @param $generated
   */
  public function generateFunction(\ReflectionFunction $function, $generated);

  /**
   * Called for every reflected class, interface or trait
   *
   * @param \ReflectionClass $class
   * @param string $generated
   * @param $generated
   */
  public function generateClass(\ReflectionClass $class, $generated);

  /**
   * Called when generating is finished
   */
  public function afterGenerate();
}
