<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Strings\Collators {

  use Carica\XSLTFunctions\Strings\XpathCollator;
  use Collator;
  use InvalidArgumentException;

  class UnicodeCodepointCollator extends IntlCollatorWrapper implements XpathCollator {

    public const URI = 'http://www.w3.org/2005/xpath-functions/collation/codepoint';

    public function __construct(string $uri = NULL) {
      if (NULL !== $uri && $uri !== self::URI) {
        throw new InvalidArgumentException(
          sprintf('Invalid URI argument for %s', __METHOD__)
        );
      }
      parent::__construct($collator = new Collator('root'));
      $collator->setStrength(Collator::IDENTICAL);
    }
  }
}