<?php

namespace Carica\XSLTFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;

  /**
   * @covers \Carica\XSLTFunctions\DateTime\Date
   */
  class DateTest extends TestCase {

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["1999-12-31", "1999-12-31"]
     *   ["1999-12-31Z", "1999-12-31Z"]
     *   ["1999-12-31+02:00", "1999-12-31+02:00"]
     */
    public function testParseAndCastToStringTroughStylesheet(
      string $expected, string $input
    ): void {
      $date = new Date($input);
      $this->assertSame($expected, (string)$date);
    }
  }
}