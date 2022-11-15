<?php

namespace Carica\XPathFunctions\Numeric {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XPathFunctions\Namespaces;
  use Carica\XPathFunctions\TestCase;
  use Carica\XPathFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XPathFunctions\MapsAndArrays\Arrays
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

    public function testPutStringTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:put($input, 2, \'Berta\')"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Alice</string>
            <string>Berta</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testInsertBeforeTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:insert-before($input, 2, 42)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Alice</string>
            <number>42</number>
            <string>Bob</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testInsertBeforeWithMultipleItemsTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:insert-before($input, 2, 21, 42, \'test\')"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Alice</string>
            <number>21</number>
            <number>42</number>
            <string>test</string>
            <string>Bob</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testSubArrayTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:subarray($input, 2)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Bob</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testSubArrayWithLengthTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:subarray($input, 2, 1)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Bob</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testRemoveTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:remove($input, 2)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Alice</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testReverseTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:reverse($input)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Charlie</string>
            <string>Bob</string>
            <string>Alice</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testHeadTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:value-of select="array:head($input)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame('Alice', $result->documentElement->textContent);
    }

    public function testTailTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:tail($input)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Bob</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testJoinTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:join($input, $input, $input)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Alice</string>
            <string>Bob</string>
            <string>Charlie</string>
            <string>Alice</string>
            <string>Bob</string>
            <string>Charlie</string>
            <string>Alice</string>
            <string>Bob</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testFlattenTroughStylesheet(): void {
      $fileName = __DIR__.'/TestData/array-nested.json';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" >'.
          '<xsl:variable name="input" select="fn:json-doc(\''.$fileName.'\')"/>'.
          '<xsl:copy-of select="array:flatten($input)"/>'.
          '</result>',
        'MapsAndArrays/JSON',
        'MapsAndArrays/Arrays'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Alice</string>
            <string>Aaron</string>
            <string>Bob</string>
            <string>Berta</string>
            <string>Brodi</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }

    public function testCreateTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.Namespaces::XMLNS_XSL.'" xmlns:array="'.Namespaces::XMLNS_ARRAY.'" xmlns:map="'.Namespaces::XMLNS_MAP.'">'.
          '<xsl:variable name="map" select="map:map(map:entry(\'foo\', \'bar\'))"/>'.
          '<xsl:copy-of select="array:array(\'string\', $map, 42, true(), false())"/>'.
          '</result>',
        'MapsAndArrays/Arrays',
        'MapsAndArrays/Maps'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result xmlns:array="http://www.w3.org/2005/xpath-functions/array" xmlns:map="http://www.w3.org/2005/xpath-functions/map">
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>string</string>
            <map>
              <string key="foo">bar</string>
            </map>
            <number>42</number>
            <boolean>true</boolean>
            <boolean>false</boolean>
          </array>
        </result>',
        $result->saveXML()
      );
    }
  }
}
