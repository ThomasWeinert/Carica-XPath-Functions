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

    /**
     * @param int $expected
     * @param string $duration
     * @testWith
     *   [3, "P3DT10H"]
     *   [5, "P3DT55H"]
     *   [0, "P3Y5M"]
     */
    public function testDaysFromDurationTroughStylesheet(
      int $expected, string $duration
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:days-from-duration(\''.$duration.'\')"/>'.
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
     *   [10, "P3DT10H"]
     *   [12, "P3DT12H32M12S"]
     *   [3, "PT123H"]
     *   [-10, "-P3DT10H"]
     */
    public function testHoursFromDurationTroughStylesheet(
      int $expected, string $duration
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:hours-from-duration(\''.$duration.'\')"/>'.
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
     *   [0, "P3DT10H"]
     *   [-30, "-P5DT12H30M"]
     */
    public function testMinutesFromDurationTroughStylesheet(
      int $expected, string $duration
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:minutes-from-duration(\''.$duration.'\')"/>'.
          '</result>',
        'Duration/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }

    /**
     * @param float $expected
     * @param string $duration
     * @testWith
     *   [12.5, "P3DT10H12.5S"]
     *   [-16.0, "-PT256S"]
     */
    public function testSecondsFromDurationTroughStylesheet(
      float $expected, string $duration
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:seconds-from-duration(\''.$duration.'\')"/>'.
          '</result>',
        'Duration/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }
  }
}
