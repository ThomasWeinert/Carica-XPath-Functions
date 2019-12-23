<?php

namespace Carica\XSLTFunctions\Strings\Collators {

  require_once __DIR__.'/../../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Locale;

  /**
   * @covers Carica\XSLTFunctions\Strings\Collators\CollatorFactory
   */
  class CollatorFactoryTest extends TestCase {

    public function testCreateFromURIExpectingPrimaryGerman(): void {
      /** @var IntlCollatorWrapper $collator */
      $collator = CollatorFactory::getByURI('http://www.w3.org/2013/collation/UCA?lang=de;strength=primary');
      $this->assertSame('de', $collator->getIntlCollator()->getLocale(Locale::VALID_LOCALE));
      $this->assertSame(0, $collator->getIntlCollator()->getStrength());
    }
  }

}
