<?php
declare(strict_types=1);

namespace Carica\XPathFunctions\Strings\Collators {

  use Carica\XPathFunctions\Strings\XpathCollator;
  use Collator;
  use InvalidArgumentException;

  class CaseInsensitiveASCIICollator extends IntlCollatorWrapper implements XpathCollator {

    public const URI = 'http://www.w3.org/2005/xpath-functions/collation/html-ascii-case-insensitive';

    public function __construct(string $uri = NULL) {
      if (NULL !== $uri && $uri !== self::URI) {
        throw new InvalidArgumentException(
          sprintf('Invalid URI argument for %s', __METHOD__)
        );
      }
      parent::__construct($collator = new Collator('root'));
      $collator->setStrength(Collator::SECONDARY);
    }
  }
}
