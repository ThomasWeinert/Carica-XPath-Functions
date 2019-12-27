<?php

namespace Carica\XSLTFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XpathError;

  /**
   * @covers \Carica\XSLTFunctions\DateTime\Time
   */
  class TimezoneDurationTest extends TestCase {

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["-PT5H", "-PT5H0M"]
     *   ["PT0S", "-PT0M"]
     * @throws XpathError
     */
    public function testParseAndCastToString(
      string $expected, string $input
    ): void {
      $time = new TimezoneDuration($input);
      $this->assertSame($expected, (string)$time);
    }

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["-05:00", "-PT5H0M"]
     *   ["-00:00", "-PT0M"]
     *   ["+10:00", "PT10H"]
     * @throws XpathError
     */
    public function testParseAndGetOffset(
      string $expected, string $input
    ): void {
      $time = new TimezoneDuration($input);
      $this->assertSame($expected, (string)$time->asOffset());
    }
  }
}
