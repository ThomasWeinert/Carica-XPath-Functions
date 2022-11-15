<?php

namespace Carica\XPathFunctions\Numeric {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XPathFunctions\TestCase;
  use Carica\XPathFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XPathFunctions\Numeric\Formatting
   */
  class FormattingTest extends TestCase {

    /**
     * @param string $expected
     * @param float $input
     * @param string $picture
     * @param string $language
     * @testWith
     *   ["0123", 123, "0000"]
     *   ["one hundred twenty-three", 123, "w", "en"]
     *   ["ein\u00ADhundert\u00ADdrei\u00ADund\u00ADzwanzig", 123, "w", "de"]
     */
    public function testFormatIntegerTroughStylesheet(
      string $expected, float $input, string $picture, string $language = ''
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:format-integer('.$input.', \''.$picture.'\', \''.$language.'\')"/>'.
          '</result>',
        'Numeric/Formatting'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }
  }
}
