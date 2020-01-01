<?php

namespace Carica\XpathFunctions\Sequences {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XpathFunctions\TestCase;
  use Carica\XpathFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XpathFunctions\Sequences\Parse
   */
  class ParseTest extends TestCase {

    public function testParseXMLTroughStylesheet(): void {
      $input = '<alpha>abcd</alpha>';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:copy-of select="fn:parse-xml(\''.htmlspecialchars($input).'\')"/>'.
          '</result>',
        'Sequences/Parse'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXMLStringEqualsXMLString(
        '<result><alpha>abcd</alpha></result>', $result->saveXML()
      );
    }

    public function testParseXMLFragmentTroughStylesheet(): void {
      $input = 'He was <i>so</i> kind';
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:copy-of select="fn:parse-xml-fragment(\''.htmlspecialchars($input).'\')"/>'.
          '</result>',
        'Sequences/Parse'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXMLStringEqualsXMLString(
        '<result>He was <i>so</i> kind</result>', $result->saveXML()
      );
    }
  }
}
