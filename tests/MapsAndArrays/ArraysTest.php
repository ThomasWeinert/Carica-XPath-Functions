<?php

namespace Carica\XSLTFunctions\Numeric {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\Namespaces;
  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\MapsAndArrays\Arrays
   */
  class ArraysTest extends TestCase {

    public function testSizeTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:value-of select="array:size($input)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame(3, (int)$result->documentElement->textContent);
    }

    public function testSizeWithXDMDocumentTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.xml';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:doc(\''.$fileName.'\')"/>'.
          '<xsl:value-of select="array:size($input)"/>'.
          '</result>',
        'Sequences/External',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame(3, (int)$result->documentElement->textContent);
    }

    /**
     * @param string $expected
     * @param int $position
     * @testWith
     *   ["Alice", 1]
     *   ["Bob", 2]
     *   ["Charlie", 3]
     */
    public function testGetTroughStylesheet(string $expected, int $position): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:value-of select="array:get($input, '.$position.')"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }
  }
}
