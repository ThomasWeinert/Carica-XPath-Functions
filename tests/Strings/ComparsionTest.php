<?php

namespace Carica\XSLTFunctions\Strings {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\Strings\Comparsion
   */
  class ComparsionTest extends TestCase {

    /**
     * @param int $expected
     * @param string $a
     * @param string $b
     * @testWith
     *   [-1, "a", "b"]
     *   [1, "b", "a"]
     *   [0, "a", "a"]
     */
    public function testCompareTroughStylesheet(int $expected, string $a, string $b): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:compare(\''.$a.'\', \''.$b.'\')"/>'.
          '</result>',
        'Strings/Comparsion'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }

    /**
     * @param int $expected
     * @param string $a
     * @param string $b
     * @param string $collation
     * @testWith
     *   [-1, "Strasse", "Straße", "http://www.w3.org/2013/collation/UCA?lang=de;strength=secondary"]
     *   [0, "Strasse", "Straße", "http://www.w3.org/2013/collation/UCA?lang=de;strength=primary"]
     *   [0, "strasse", "Straße", "http://www.w3.org/2013/collation/UCA?lang=de;strength=primary"]
     *   [0, "strasse", "Strasse", "http://www.w3.org/2013/collation/UCA?lang=de;strength=secondary"]
     *   [-1, "strasse", "Strasse", "http://www.w3.org/2013/collation/UCA?lang=de;strength=tertiary"]
     *   [1, "Strassen", "Straße", "http://www.w3.org/2013/collation/UCA?lang=de;strength=primary"]
     */
    public function testCompareTroughStylesheetWithCollation(int $expected, string $a, string $b, string $collation): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:compare(\''.$a.'\', \''.$b.'\', \''.$collation.'\')"/>'.
          '</result>',
        'Strings/Comparsion'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (int)$result->documentElement->textContent);
    }
  }
}
