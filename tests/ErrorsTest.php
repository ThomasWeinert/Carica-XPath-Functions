<?php

namespace Carica\XPathFunctions {

  require_once __DIR__.'/TestCase.php';

  /**
   * @covers \Carica\XPathFunctions\Errors
   */
  class ErrorsTest extends TestCase {

    public function testErrorTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:error()"/>'.
          '</result>',
        'Errors'
      );

      $this->expectException(XpathError::class);
      $this->expectExceptionMessage('http://www.w3.org/2005/xqt-errors#FOER0000, Unidentified error');

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $processor->transformToDoc($this->prepareInputDocument());
    }

    public function testErrorWithURITroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:error(\'urn:a#foo42\')"/>'.
          '</result>',
        'Errors'
      );

      $this->expectException(XpathError::class);
      $this->expectExceptionMessage('urn:a#foo42');

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $processor->transformToDoc($this->prepareInputDocument());
    }
  }
}
