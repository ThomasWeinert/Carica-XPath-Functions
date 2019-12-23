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
  }
}
