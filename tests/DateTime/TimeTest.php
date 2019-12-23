<?php

namespace Carica\XSLTFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;

  /**
   * @covers \Carica\XSLTFunctions\DateTime\Time
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
     */
    public function testParseAndCastToStringTroughStylesheet(
      string $expected, string $input
    ): void {
      $time = new Time($input);
      $this->assertSame($expected, (string)$time);
    }
  }
}