<?php

namespace Carica\XSLTFunctions\Strings {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\Strings\Comparsion
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
  }
}
