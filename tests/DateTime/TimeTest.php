<?php

namespace Carica\XpathFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XpathFunctions\TestCase;

  /**
   * @covers \Carica\XpathFunctions\DateTime\Time
   */
  class TimeTest extends TestCase {

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["12:00:00", "12:00:00"]
     *   ["00:00:00", "24:00:00"]
     *   ["04:00:00Z", "04:00:00Z"]
     *   ["04:00:00+02:00", "04:00:00+02:00"]
     *   ["13:20:10.500", "13:20:10.5"]
     */
    public function testParseAndCastToStringTroughStylesheet(
      string $expected, string $input
    ): void {
      $time = new Time($input);
      $this->assertSame($expected, (string)$time);
    }
  }
}
