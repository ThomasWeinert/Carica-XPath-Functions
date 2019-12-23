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
    public function testYearsFromDurationTroughStylesheet(
      string $expected, string $date, string $time
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:datetime(\''.$date.'\', \''.$time.'\')"/>'.
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
