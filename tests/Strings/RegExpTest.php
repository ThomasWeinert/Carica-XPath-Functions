<?php

namespace Carica\XpathFunctions\Strings {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XpathFunctions\Namespaces;
  use Carica\XpathFunctions\TestCase;
  use Carica\XpathFunctions\XpathError;
  use Carica\XpathFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XpathFunctions\Strings\RegExp
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
     * @throws XpathError
     */
    public function testMatchesWithEmptyPatternExpectingException(): void {
      $this->assertXpathErrorTriggeredBy(
        Namespaces::XMLNS_ERR.'#FORX0002',
        static function() {
          RegExp::replace('','', '');
        }
      );
    }

    /**
     * @throws XpathError
     */
    public function testMatchesWithInvalidPatternExpectingException(): void {
      $this->assertXpathErrorTriggeredBy(
        Namespaces::XMLNS_ERR.'#FORX0002',
        static function() {
          RegExp::replace('','(', '');
        }
      );
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

    /**
     * @param string $replacement
     * @testWith
     *   ["a$b"]
     *   ["a\\b"]
     * @throws XpathError
     */
    public function testReplaceWithInvalidReplacementExpectingException(string $replacement): void {
      $this->assertXpathErrorTriggeredBy(
        Namespaces::XMLNS_ERR.'#FORX0004',
        static function() use ($replacement) {
          RegExp::replace('', '(.+)', $replacement);
        }
      );
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

    public function testAnalyzeStringTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:copy-of select="fn:analyze-string(\'The cat sat on the mat.\', \'\\w+\')"/>'.
          '</result>',
        'Strings/RegExp'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result>
          <analyze-string-result xmlns="http://www.w3.org/2005/xpath-functions">
            <match>The</match>
            <non-match> </non-match>
            <match>cat</match>
            <non-match> </non-match>
            <match>sat</match>
            <non-match> </non-match>
            <match>on</match>
            <non-match> </non-match>
            <match>the</match>
            <non-match> </non-match>
            <match>mat</match>
            <non-match>.</non-match>
          </analyze-string-result>
        </result>',
        $result->saveXML()
      );
    }

    public function testAnalyzeStringTroughStylesheetExpectingSingleMatchWithGroups(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:copy-of select="fn:analyze-string(\'2008-12-03\', \'^(\d+)\-(\d+)\-(\d+)$\')"/>'.
          '</result>',
        'Strings/RegExp'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result>
          <analyze-string-result xmlns="http://www.w3.org/2005/xpath-functions">
            <match><group nr="1">2008</group>-<group nr="2">12</group>-<group nr="3">03</group></match>
          </analyze-string-result>
        </result>',
        $result->saveXML()
      );
    }

    public function testAnalyzeStringTroughStylesheetExpectingMultipleMatchesWithGroups(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:copy-of select="fn:analyze-string(\'A1,C15,,D24, X50,\', \'([A-Z])([0-9]+)\')"/>'.
          '</result>',
        'Strings/RegExp'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<result>
          <analyze-string-result xmlns="http://www.w3.org/2005/xpath-functions">
            <match>
              <group nr="1">A</group>
              <group nr="2">1</group>
            </match>
            <non-match>,</non-match>
            <match>
              <group nr="1">C</group>
              <group nr="2">15</group>
            </match>
            <non-match>,,</non-match>
            <match>
              <group nr="1">D</group>
              <group nr="2">24</group>
            </match>
            <non-match>, </non-match>
            <match>
              <group nr="1">X</group>
              <group nr="2">50</group>
            </match>
            <non-match>,</non-match>
          </analyze-string-result>
        </result>',
        $result->saveXML()
      );
    }
  }
}
