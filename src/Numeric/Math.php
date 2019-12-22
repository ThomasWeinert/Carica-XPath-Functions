<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Numeric {

  abstract class Math {

    public static function pi(): float {
      return M_PI;
    }

    public static  function exp(float $argument): float {
      return exp($argument);
    }

    public static  function exp10(float $argument): float {
      return 10 ** $argument;
    }
  }
}
