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

    public static function log(float $argument): float {
       return log($argument);
    }

    public static function log10(float $argument): float {
       return log10($argument);
    }

    public static function pow(float $base, float $exponent): float {
      return $base ** $exponent;
    }

    public static function sqrt(float $input): float {
      return sqrt($input);
    }

    public static function sin(float $input): float {
      return sin($input);
    }

    public static function cos(float $input): float {
      return cos($input);
    }

    public static function tan(float $input): float {
      return tan($input);
    }

    public static function asin(float $input): float {
      return asin($input);
    }

    public static function acos(float $input): float {
      return acos($input);
    }

    public static function atan(float $input): float {
      return atan($input);
    }

    public static function atan2(float $y, float $x): float {
      return atan2($y, $x);
    }
  }
}
