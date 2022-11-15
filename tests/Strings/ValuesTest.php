<?php

namespace Carica\XPathFunctions\Strings {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XPathFunctions\TestCase;
  use Carica\XPathFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XPathFunctions\Strings\Values
   */
  class ValuesTest extends TestCase {

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["ABCD0", "abCd0"]
     *   ["ABC!D", "ABc!D"]
     *   ["ÄÖÜ", "äöü"]
     */
    public function testUpperCaseTroughStylesheet(string $expected, string $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:upper-case(\''.$input.'\')"/>'.
          '</result>',
        'Strings/Values'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }

    /**
     * @param string $expected
     * @param string $input
     * @testWith
     *   ["abcd0", "abCd0"]
     *   ["abc!d", "ABc!D"]
     *   ["äöü", "ÄÖÜ"]
     */
    public function testLowerCaseTroughStylesheet(string $expected, string $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:lower-case(\''.$input.'\')"/>'.
          '</result>',
        'Strings/Values'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }
  }
}
