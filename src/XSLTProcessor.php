<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions {


  use BadMethodCallException;

  class XSLTProcessor extends \XSLTProcessor {

    private static $_modules = [
      'strings/comparsion' => Strings\Comparsion::class,
      'strings/regexp' => Strings\RegExp::class
    ];

    public function __construct() {
      self::attachXpathFunctions($this);
    }

    public static function handleFunctionCall(string $module, string $function, ...$arguments) {
      $call = self::getCallback($module, $function);
      return $call(...$arguments);
    }

    private static function attachXpathFunctions(\XSLTProcessor $processor): void {
      $processor->registerPHPFunctions([__CLASS__.'::handleFunctionCall']);
      ModuleLoader::register('xpath-functions', __DIR__);
    }

    private static function getCallback(string $module, string $function): callable {
      $module = strtolower($module);
      if (!isset(self::$_modules[$module])) {
        throw new BadMethodCallException("Invalid XSLT callback module: {$module}");
      }
      $callback = self::$_modules[$module].'::'.$function;
      if (!is_callable($callback)) {
        throw new BadMethodCallException("Invalid XSLT callback function: {$module} -> {$function}");
      }
      return $callback;
    }
  }
}
