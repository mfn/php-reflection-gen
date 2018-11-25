<?php declare(strict_types=1);

namespace Mfn\PHP\Reflection\Generator;

class Util {

  /**
   * Primitive default PHP warning/notice/whatever to Exception error handler
   *
   * Just make sure you've PHP properly configured to report all errors
   */
  public static function installMinimalError2ExceptionHandler() {
    /** @noinspection PhpUnusedParameterInspection */
    set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
      $msg = "$errstr in $errfile line $errline";
      throw new \RuntimeException($msg, $errno);
    });
  }
}
