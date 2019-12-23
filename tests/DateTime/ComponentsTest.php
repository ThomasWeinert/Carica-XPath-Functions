<?php

namespace Carica\XSLTFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\DateTime\Components
   */
  class ComponentsTest extends TestCase {

    /**
     * @param string $expected
     * @param string $date
     * @param string $time
     * @testWith
     *   ["1999-12-31T12:00:00", "1999-12-31", "12:00:00"]
     *   ["1999-12-31T00:00:00", "1999-12-31", "24:00:00"]
     *   ["1999-12-31T00:00:00Z", "1999-12-31", "24:00:00Z"]
     *   ["1999-12-31T00:00:00Z", "1999-12-31Z", "24:00:00"]
     */
    public function testDateTimeTroughStylesheet(
      string $expected, string $date, string $time
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:dateTime(\''.$date.'\', \''.$time.'\')"/>'.
          '</result>',
        'DateTime/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }

    /**
     * @param int $expected
     * @param string $dateTime
     * @testWith
     *   [1999, "1999-05-31T13:20:00-05:00"]
     *   [1999, "1999-05-31T21:30:00-05:00"]
     *   [1999, "1999-12-31T19:20:00"]
     *   [2000, "1999-12-31T24:00:00"]
     *   [-2, "-0002-06-06T00:00:00"]
     */
    public function testYearFromDateTimeTroughStylesheet(
      int $expected, string $dateTime
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:year-from-dateTime(\''.$dateTime.'\')"/>'.
          '</result>',
        'DateTime/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }

    /**
     * @param int $expected
     * @param string $dateTime
     * @testWith
     *   [5, "1999-05-31T13:20:00-05:00"]
     *   [12, "1999-12-31T19:20:00-05:00"]
     */
    public function testMonthFromDateTimeTroughStylesheet(
      int $expected, string $dateTime
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:month-from-dateTime(\''.$dateTime.'\')"/>'.
          '</result>',
        'DateTime/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }

    /**
     * @param int $expected
     * @param string $dateTime
     * @testWith
     *   [31, "1999-05-31T13:20:00-05:00"]
     *   [31, "1999-12-31T20:00:00-05:00"]
     */
    public function testDayFromDateTimeTroughStylesheet(
      int $expected, string $dateTime
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:day-from-dateTime(\''.$dateTime.'\')"/>'.
          '</result>',
        'DateTime/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }

    /**
     * @param int $expected
     * @param string $dateTime
     * @testWith
     *   [8, "1999-05-31T08:20:00-05:00"]
     *   [21, "1999-12-31T21:20:00-05:00"]
     *   [12, "1999-12-31T12:00:00"]
     *   [0, "1999-12-31T24:00:00"]
     */
    public function testHoursFromDateTimeTroughStylesheet(
      int $expected, string $dateTime
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:hours-from-dateTime(\''.$dateTime.'\')"/>'.
          '</result>',
        'DateTime/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }

    /**
     * @param int $expected
     * @param string $dateTime
     * @testWith
     *   [20, "1999-05-31T13:20:00-05:00"]
     *   [30, "1999-05-31T13:30:00+05:30"]
     */
    public function testMinutesFromDateTimeTroughStylesheet(
      int $expected, string $dateTime
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:minutes-from-dateTime(\''.$dateTime.'\')"/>'.
          '</result>',
        'DateTime/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }

    /**
     * @param float $expected
     * @param string $dateTime
     * @testWith
     *   [0, "1999-05-31T13:20:00-05:00"]
     *   [42, "1999-05-31T13:20:42"]
     *   [12.5, "1999-05-31T13:20:12.500-05:00"]
     */
    public function testSecondsFromDateTimeTroughStylesheet(
      float $expected, string $dateTime
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:seconds-from-dateTime(\''.$dateTime.'\')"/>'.
          '</result>',
        'DateTime/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }

    /**
     * @param string $expected
     * @param string $dateTime
     * @testWith
     *   ["-PT5H", "1999-05-31T13:20:00-05:00"]
     *   ["PT0S", "2000-06-12T13:20:00Z"]
     *   ["", "2004-08-27T00:00:00"]
     */
    public function testTimezoneFromDateTimeTroughStylesheet(
      string $expected, string $dateTime
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:timezone-from-dateTime(\''.$dateTime.'\')"/>'.
          '</result>',
        'DateTime/Components'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }
  }
}
