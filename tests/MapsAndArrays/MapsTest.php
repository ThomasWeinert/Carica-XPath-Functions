<?php

namespace Carica\XSLTFunctions\Numeric {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\Namespaces;
  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\MapsAndArrays\Maps
   */
  class MapsTest extends TestCase {

    public function testSizeTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/example.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:map="'.Namespaces::XMLNS_MAP.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:value-of select="map:size($input)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Maps'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame(8, (int)$result->documentElement->textContent);
    }

    public function testKeysTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/example.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:map="'.Namespaces::XMLNS_MAP.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="map:keys($input)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Maps'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:map="http://www.w3.org/2005/xpath-functions/map">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>firstName</string>
            <string>lastName</string>
            <string>isAlive</string>
            <string>age</string>
            <string>address</string>
            <string>phoneNumbers</string>
            <string>children</string>
            <string>spouse</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    /**
     * @param bool $expected
     * @param string $key
     * @testWith
     *   [true, "lastName"]
     *   [false, "lastname"]
     *   [false, "non-existing"]
     *   [true, "children"]
     */
    public function testContainsTroughStylesheet(bool $expected, string $key): void {
      $fileName = __DIR__.'/TestData/example.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:map="'.Namespaces::XMLNS_MAP.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:value-of select="map:contains($input, \''.$key.'\')"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Maps'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent === 'true');
    }

    /**
     * @param string $expected
     * @param string $key
     * @testWith
     *   ["John", "firstName"]
     *   ["Smith", "lastName"]
     *   ["", "non-existing"]
     */
    public function testGetTroughStylesheet(string $expected, string $key): void {
      $fileName = __DIR__.'/TestData/example.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:map="'.Namespaces::XMLNS_MAP.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:value-of select="map:get($input, \''.$key.'\')"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Maps'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }

    public function testFindTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/example.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:map="'.Namespaces::XMLNS_MAP.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="map:find($input, \'type\')"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Maps'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:map="http://www.w3.org/2005/xpath-functions/map">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>home</string>
            <string>office</string>
            <string>mobile</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }
  }
}
