<?php

namespace Carica\XSLTFunctions\Strings {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\Strings\RegExp
   */
  class RegExpTest extends TestCase {

    /**
     * @param bool $expected
     * @param string $input
     * @param string $pattern
     * @testWith
     *   [true, "abracadabra", "bra"]
     *   [true, "abracadabra", "^a.*a$"]
     *   [false, "abracadabra", "^bra"]
     */
    public function testMatchesTroughStylesheet(bool $expected, string $input, string $pattern): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:matches(\''.$input.'\', \''.$pattern.'\')"/>'.
          '</result>',
        'Strings/RegExp'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent === 'true');
    }

    /**
     * @param bool $expected
     * @param string $pattern
     * @param string $flags
     * @testWith
     *   [false, "Kaum.*krähen"]
     *   [true, "Kaum.*krähen", "s"]
     *   [true, "^Kaum.*gesehen,$", "m"]
     *   [false, "^Kaum.*gesehen,$"]
     *   [true, "kiki", "i"]
     */
    public function testMatchesTroughStylesheetWithFlags(
      bool $expected, string $pattern, string $flags = ''
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:matches(/poem, \''.$pattern.'\', \''.$flags.'\')"/>'.
          '</result>',
        'Strings/RegExp'
      );

      $input = $this->prepareInputDocument(
        '<poem author="Wilhelm Busch">'."\n".
          'Kaum hat dies der Hahn gesehen,'."\n".
          'Fängt er auch schon an zu krähen:'."\n".
          'Kikeriki! Kikikerikih!!'."\n".
          'Tak, tak, tak! - da kommen sie.'."\n".
          '</poem>'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($input);

      $this->assertSame($expected, $result->documentElement->textContent === 'true');
    }

    /**
     * @param string $expected
     * @param string $input
     * @param string $pattern
     * @param string $replacement
     * @testWith
     *   ["a*cada*", "abracadabra", "bra", "*"]
     *   ["*", "abracadabra", "a.*a", "*"]
     *   ["*c*bra", "abracadabra", "a.*?a", "*"]
     *   ["brcdbr", "abracadabra", "a", ""]
     *   ["abbraccaddabbra", "abracadabra", "a(.)", "a$1$1"]
     *   ["b", "AAAA", "A+", "b"]
     *   ["bbbb", "AAAA", "A+?", "b"]
     *   ["carted", "darted", "^(.*?)d(.*)$", "$1c$2"]
     */
    public function testReplaceTroughStylesheet(
      string $expected, string $input, string $pattern, string $replacement
    ): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:replace(\''.$input.'\', \''.$pattern.'\', \''.$replacement.'\')"/>'.
          '</result>',
        'Strings/RegExp'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }

    public function testTokenizeWithoutPatternTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:for-each select="fn:tokenize(\'   red    green    blue\')">'.
          '<xsl:value-of select="."/> - '.
          '</xsl:for-each>'.
          '</result>',
        'Strings/RegExp'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame('red - green - blue - ', $result->documentElement->textContent);
    }
  }
}
