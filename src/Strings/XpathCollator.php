<?php

namespace Carica\XSLTFunctions\Strings {

  interface XpathCollator {

    public function __construct(string $uri = NULL);

    public function compare(string $string1, string $string2): int;

  }
}
