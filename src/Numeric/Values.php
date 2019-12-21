<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Numeric {

  abstract class Values {

    public static function roundHalfToEven(float $input, float $precision): float {
      return round($input, (int)$precision, PHP_ROUND_HALF_EVEN);
    }
  }
}
