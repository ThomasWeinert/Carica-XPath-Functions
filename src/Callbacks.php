<?php
declare(strict_types=1);

namespace Carica\XPathFunctions {

  use BadMethodCallException;

  abstract  class Callbacks {

    private static array $_modules = [
      'Context' => Context::class,
      'DateTime/Components' => DateTime\Components::class,
      'DateTime/Formatting' => DateTime\Formatting::class,
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

    public static function handleFunctionCall(
      string $module, string $function, ...$arguments
    ): mixed {
      $call = self::getCallback($module, $function);
      return $call(...$arguments);
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
