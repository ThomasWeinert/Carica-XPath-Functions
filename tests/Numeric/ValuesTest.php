<?php

namespace Carica\XSLTFunctions\Numeric {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\Numeric\Values
   */
  class ValuesTest extends TestCase {

    /**
     * @param float $expected
     * @param float $input
     * @param int $precision
     * @testWith
     *   [0.0, 0.5]
     *   [2.0, 1.5]
     *   [2.0, 2.5]
     *   [3567.81e0, 3.567812e+3, 2]
     *   [0.0, 4.7564e-3, 2]
     *   [35600, 35612.25, -2]
     */
    public function testHalfToEvenTroughStylesheet(float $expected, float $input, int $precision = 0): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:round-half-to-even('.$input.', '.$precision.')"/>'.
          '</result>',
        'Numeric/Values'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }
  }
}
