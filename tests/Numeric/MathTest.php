<?php

namespace Carica\XSLTFunctions\Numeric {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\Strings\RegExp
   */
  class MathTest extends TestCase {

    public const XMLNS_MATH = 'http://www.w3.org/2005/xpath-functions/math';

    public function testPITroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.self::XMLNS_XSL.'" xmlns:math="'.self::XMLNS_MATH.'">'.
          '<xsl:value-of select="math:pi()"/>'.
          '</result>',
        'Numeric/Math'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame(M_PI, (float)$result->documentElement->textContent);
    }
  }
}
