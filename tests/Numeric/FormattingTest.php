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
     *   ["c", 3, "a"]
     *   ["aa", 27, "a"]
     *   ["ab", 28, "a"]
     *   ["C", 3, "A"]
     *   ["AA", 27, "A"]
     *   ["iii", 3, "i"]
     *   ["XXVII", 27, "I"]
     *   ["one hundred twenty-three", 123, "w", "en"]
     *   ["ONE HUNDRED TWENTY-THREE", 123, "W", "en"]
     *   ["One Hundred Twenty-three", 123, "Ww", "en"]
     *   ["ein\u00ADhundert\u00ADdrei\u00ADund\u00ADzwanzig", 123, "w", "de"]
     *   ["Ein\u00ADHundert\u00ADDrei\u00ADund\u00ADZwanzig", 123, "Ww", "de"]
     *   ["一百二十三", 123, "W", "zh"]
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
