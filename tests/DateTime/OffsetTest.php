<?php

namespace Carica\XpathFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XpathFunctions\TestCase;

  /**
   * @covers \Carica\XpathFunctions\DateTime\Offset
   */
  class OffsetTest extends TestCase {

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["+00:00", "+00:00"]
     *   ["-00:00", "-00:00"]
     *   ["-02:00", "-02:00"]
     *   ["+02:00", "+02:00"]
     *   ["-04:30", "-04:30"]
     *   ["+04:30", "+04:30"]
     *   ["Z", "Z"]
     */
    public function testParseAndCastToStringTroughStylesheet(
      string $expected, string $input
    ): void {
      $offset = new Offset($input);
      $this->assertSame($expected, (string)$offset);
    }
  }
}
