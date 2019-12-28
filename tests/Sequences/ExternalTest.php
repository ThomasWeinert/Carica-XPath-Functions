<?php

namespace Carica\XSLTFunctions\Sequences {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\Sequences\Parse
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
  }
}