<?php
declare(strict_types=1);

namespace Carica\XPathFunctions\Numeric {

  abstract class Math {

    public static function pi(): float {
      return M_PI;
    }

    public static  function exp10(float $argument): float {
      return 10 ** $argument;
    }

    public static function log10(float $argument): float {
       return log10($argument);
    }

    public static function sqrt(float $input): float {
      return sqrt($input);
    }
  }
}
