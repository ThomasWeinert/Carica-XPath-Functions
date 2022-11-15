<?php

namespace Carica\XPathFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XPathFunctions\TestCase;

  /**
   * @covers \Carica\XPathFunctions\DateTime\Format
   */
  class FormatTest extends TestCase {

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["12/31/99", "1999-12-31"]
     *   ["December 31, 1999", "1999-12-31", "LONG"]
     *   ["31.12.99", "1999-12-31", "SHORT", "de"]
     *   ["31. Dezember 1999", "1999-12-31", "LONG", "de"]
     */
    public function testFormatDate(
      string $expected,
      string $input,
      string $picture = 'SHORT',
      string $language = 'en'
    ): void {
      $this->assertSame(
        $expected,
        Format::formatDate(
          $input,
          $picture,
          $language
        )
      );
    }

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["12/31/99, 12:34 PM", "1999-12-31T12:34:56Z"]
     *   ["December 31, 1999 at 12:34:56 PM UTC", "1999-12-31T12:34:56Z", "LONG"]
     *   ["31.12.99, 12:34", "1999-12-31T12:34:56Z", "SHORT", "de"]
     *   ["31. Dezember 1999 um 12:34:56 UTC", "1999-12-31T12:34:56Z", "LONG", "de"]
     */
    public function testFormatDateTime(
      string $expected,
      string $input,
      string $picture = 'SHORT',
      string $language = 'en'
    ): void {
      $this->assertSame(
        $expected,
        Format::formatDateTime(
          $input,
          $picture,
          $language
        )
      );
    }
  }
}
