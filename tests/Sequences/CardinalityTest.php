<?php

namespace Carica\XSLTFunctions\Sequences {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\Sequences\Cardinality
   */
  class CardinalityTest extends TestCase {

    /**
     * @param bool $expected
     * @param string $input
     * @testWith
     *   [true, "[]"]
     *   [true, "[1]"]
     *   [false, "[1, 2]"]
     */
    public function testZeroOrOneTroughStylesheet(bool $expected, string $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:zero-or-one(fn:parse-json(\''.$input.'\'))"/>'.
          '</result>',
        'Sequences/Cardinality',
        'MapsAndArrays/JSON'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent === 'true');
    }
    /**
     * @param bool $expected
     * @param string $input
     * @testWith
     *   [false, "[]"]
     *   [true, "[1]"]
     *   [true, "[1, 2]"]
     */
    public function testOneOrMoreTroughStylesheet(bool $expected, string $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:one-or-more(fn:parse-json(\''.$input.'\'))"/>'.
          '</result>',
        'Sequences/Cardinality',
        'MapsAndArrays/JSON'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent === 'true');
    }
    /**
     * @param bool $expected
     * @param string $input
     * @testWith
     *   [false, "[]"]
     *   [true, "[1]"]
     *   [false, "[1, 2]"]
     */
    public function testExactlyOneTroughStylesheet(bool $expected, string $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:exactly-one(fn:parse-json(\''.$input.'\'))"/>'.
          '</result>',
        'Sequences/Cardinality',
        'MapsAndArrays/JSON'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent === 'true');
    }
  }
}
