<?php

namespace Carica\XpathFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XpathFunctions\TestCase;

  /**
   * @covers \Carica\XpathFunctions\DateTime\Date
   */
  class DateTest extends TestCase {

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["1999-12-31", "1999-12-31"]
     *   ["1999-12-31Z", "1999-12-31Z"]
     *   ["1999-12-31+02:00", "1999-12-31+02:00"]
     *   ["-0002-06-01", "-0002-06-01"]
     */
    public function testParseAndCastToStringTroughStylesheet(
      string $expected, string $input
    ): void {
      $date = new Date($input);
      $this->assertSame($expected, (string)$date);
    }
  }
}
