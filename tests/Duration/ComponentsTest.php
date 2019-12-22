<?php

namespace Carica\XSLTFunctions\Duration {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\Duration\Components
   */
  class ComponentsTest extends TestCase {

    /**
     * @param int $expected
     * @param string $duration
     * @testWith
     *   [21, "P20Y15M"]
     *   [-1, "-P15M"]
     *   [0, "P2DT15H"]
     */
    public function testYearsFromDurationTroughStylesheet(
      int $expected, string $duration
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:years-from-duration(\''.$duration.'\')"/>'.
          '</result>',
        'Duration/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }

    /**
     * @param int $expected
     * @param string $duration
     * @testWith
     *   [3, "P20Y15M"]
     *   [-3, "-P15M"]
     *   [-6, "-P20Y18M"]
     *   [0, "-P2DT15H0M0S"]
     */
    public function testMonthsFromDurationTroughStylesheet(
      int $expected, string $duration
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:months-from-duration(\''.$duration.'\')"/>'.
          '</result>',
        'Duration/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }
  }
}
