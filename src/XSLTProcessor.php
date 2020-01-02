<?php
declare(strict_types=1);

namespace Carica\XpathFunctions {

  use BadMethodCallException;

  class XSLTProcessor extends \XSLTProcessor {

    private static $_modules = [
      'Context' => Context::class,
      'DateTime/Components' => DateTime\Components::class,
      'DateTime/TimezoneAdjust' => DateTime\TimezoneAdjust::class,
      'Duration/Components' => Duration\Components::class,
      'Errors' => Errors::class,
      'MapsAndArrays/Arrays' => MapsAndArrays\Arrays::class,
      'MapsAndArrays/JSON' => MapsAndArrays\JSON::class,
      'MapsAndArrays/Maps' => MapsAndArrays\Maps::class,
      'Numeric/Formatting' => Numeric\Formatting::class,
      'Numeric/Math' => Numeric\Math::class,
      'Numeric/Values' => Numeric\Values::class,
      'Sequences/Cardinality' => Sequences\Cardinality::class,
      'Sequences/External' => Sequences\External::class,
      'Sequences/Parse' => Sequences\Parse::class,
      'Strings/Comparsion' => Strings\Comparsion::class,
      'Strings/RegExp' => Strings\RegExp::class,
      'Strings/Values' => Strings\Values::class
    ];

    public function __construct() {
      self::attachXpathFunctions($this);
    }

    public static function handleFunctionCall(string $module, string $function, ...$arguments) {
      $call = self::getCallback($module, $function);
      return $call(...$arguments);
    }

    public function registerPHPFunctions($restrict = NULL): void {
      if (NULL === $restrict) {
        throw new \LogicException('Please restrict the PHP functions that XSLT can call.');
      }
      $restrict[] = __CLASS__.'::handleFunctionCall';
      parent::registerPHPFunctions($restrict);
    }

    private static function attachXpathFunctions(\XSLTProcessor $processor): void {
      $processor->registerPHPFunctions([__CLASS__.'::handleFunctionCall']);
      ModuleLoader::register('xpath-functions', __DIR__);
    }

    private static function getCallback(string $module, string $function): callable {
      $moduleName = isset(self::$_modules[$module]) ? $module : strtolower($module);
      if (!isset(self::$_modules[$moduleName])) {
        throw new BadMethodCallException("Invalid XSLT callback module: {$module}");
      }
      $callback = self::$_modules[$moduleName].'::'.$function;
      if (!is_callable($callback)) {
        throw new BadMethodCallException("Invalid XSLT callback function: {$module} -> {$function}");
      }
      return $callback;
    }
  }
}
