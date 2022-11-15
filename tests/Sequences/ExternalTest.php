<?php

namespace Carica\XPathFunctions\Sequences {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XPathFunctions\TestCase;
  use Carica\XPathFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XPathFunctions\Sequences\Parse
   */
  class ExternalTest extends TestCase {

    public function testUnparsedTextTroughStylesheet(): void {
      $href = __DIR__.'/TestData/hello.txt';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="normalize-space(fn:unparsed-text(\''.htmlspecialchars($href).'\'))"/>'.
          '</result>',
        'Sequences/External'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXMLStringEqualsXMLString(
        '<result>Hello World!</result>', $result->saveXML()
      );
    }

    public function testUnparsedTextLinesTroughStylesheet(): void {
      $href = __DIR__.'/TestData/names.txt';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:copy-of select="fn:unparsed-text-lines(\''.htmlspecialchars($href).'\')"/>'.
          '</result>',
        'Sequences/External'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXMLStringEqualsXMLString(
        '<result>
          <array xmlns="http://www.w3.org/2005/xpath-functions">
            <string>Alice</string>
            <string>Bob</string>
            <string>Charlie</string>
          </array>
        </result>',
        $result->saveXML()
      );
    }
  }
}
