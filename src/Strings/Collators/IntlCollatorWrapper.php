<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Strings\Collators {

  use Collator;

  abstract class IntlCollatorWrapper {

    /**
     * @var Collator
     */
    private $_intlCollator;

    public function __construct(Collator $intlCollator) {
      $this->_intlCollator = $intlCollator;
    }

    public function getIntlCollator(): Collator {
      return $this->_intlCollator;
    }

    public function compare(string $string1, string $string2): int {
      return $this->getIntlCollator()->compare($string1, $string2);
    }
  }
}
