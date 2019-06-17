<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Strings {

  use Collator;

  class Comparsion {

    /**
     * @param string $input
     * @param string $token
     * @param string $collation
     * @return int
     */
    public static function compare(string $input, string $token, string $collation = 'root'): int {
      return self::createCollator($collation)->compare($input, $token);
    }

    private static function createCollator(string $collation): Collator {
      return Collator::create($collation);
    }
  }
}
