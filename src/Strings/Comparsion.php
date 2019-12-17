<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Strings {

  use Carica\XSLTFunctions\Strings\Collators\CollatorFactory;
  use Carica\XSLTFunctions\Strings\Collators\UnicodeCodepointCollator;

  abstract class Comparsion {

    /**
     * @param string $input
     * @param string $token
     * @param string $collationURI
     * @return int
     */
    public static function compare(
      string $input,
      string $token,
      string $collationURI = ''
    ): int {
      return self::createCollator($collationURI)->compare($input, $token);
    }

    private static function createCollator(string $collationURI): XpathCollator {
      return CollatorFactory::createFromURI($collationURI);
    }
  }
}
