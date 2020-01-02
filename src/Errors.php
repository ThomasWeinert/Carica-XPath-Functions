<?php
declare(strict_types=1);

namespace Carica\XpathFunctions {

  abstract class Errors {

    public static function error(string $uri, string $description, $context = NULL): void {
      throw new XpathError($uri, $description, $context);
    }
  }
}
