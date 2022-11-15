<?php

namespace Carica\XPathFunctions\Strings {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XPathFunctions\TestCase;
  use Carica\XPathFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XPathFunctions\Strings\Comparsion
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

    /**
     * @param string $expected
     * @param string $input
     * @param string $collation
     * @testWith
     *   ["TlBMKk5OMg==", "Straße", "http://www.w3.org/2013/collation/UCA?lang=de;strength=primary"]
     *   ["TlBMKk5OMg==", "strasse", "http://www.w3.org/2013/collation/UCA?lang=de;strength=primary"]
     *   ["TlBMKk5OMg==", "Strasse", "http://www.w3.org/2013/collation/UCA?lang=de;strength=primary"]
     *   ["TlBMKk5OMgFCcAY=", "Straße", "http://www.w3.org/2013/collation/UCA?lang=de;strength=secondary"]
     *   ["TlBMKk5OMgEL", "Strasse", "http://www.w3.org/2013/collation/UCA?lang=de;strength=secondary"]
     *   ["TlBMKk5OMgELAdwK", "Strasse", "http://www.w3.org/2013/collation/UCA?lang=de;strength=tertiary"]
     */
    public function testCollationKeyTroughStylesheetWithCollation(string $expected, string $input, string $collation): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:collation-key(\''.$input.'\', \''.$collation.'\')"/>'.
          '</result>',
        'Strings/Comparsion'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, base64_encode($result->documentElement->textContent));
    }

    /**
     * @param bool $expected
     * @param string $input
     * @param string $token
     * @param string $collation
     * @testWith
     *   [true, "andre", "http://www.w3.org/2013/collation/UCA?lang=de;strength=primary"]
     *   [false, "andre", "http://www.w3.org/2013/collation/UCA?lang=de;strength=secondary"]
     *   [true, "andré", "http://www.w3.org/2013/collation/UCA?lang=de;strength=secondary"]
     *   [false, "andré", "http://www.w3.org/2013/collation/UCA?lang=de;strength=tertiary"]
     */
    public function testContainsTokenTroughStylesheet(bool $expected, string $token, string $collation): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:variable name="input">André Joan</xsl:variable>'.
          '<xsl:value-of select="fn:contains-token($input, \''.$token.'\', \''.$collation.'\')"/>'.
          '</result>',
        'Strings/Comparsion'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent === 'true');
    }
  }
}
