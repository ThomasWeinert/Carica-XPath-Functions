<?php

namespace Carica\XPathFunctions {

  use Carica\XPathFunctions\Strings\Collators\CaseInsensitiveASCIICollator;
  use Carica\XPathFunctions\Strings\Collators\CollatorFactory;
  use Carica\XPathFunctions\Strings\Collators\UnicodeCodepointCollator;

  require_once __DIR__.'/TestCase.php';

  /**
   * @covers \Carica\XPathFunctions\Context
   */
  class ContextTest extends TestCase {

    public function testDefaultCollationTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:default-collation()"/>'.
          '</result>',
        'Context'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame(
        UnicodeCodepointCollator::URI,
        $result->documentElement->textContent
      );
    }

    public function testDefaultCollationAfterSetTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:default-collation()"/>'.
          '</result>',
        'Context'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);

      CollatorFactory::setDefaultCollation(CaseInsensitiveASCIICollator::URI);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame(
        CaseInsensitiveASCIICollator::URI,
        $result->documentElement->textContent
      );
    }

    public function testCurrentDateTimeTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:current-dateTime()"/>'.
          '</result>',
        'Context'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertMatchesRegularExpression(
        '(^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}(?:\\.\\d{3})?(?:Z|[+-]\\d{2}:\\d{2})$)',
        $result->documentElement->textContent
      );
    }

    public function testCurrentDateTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:current-date()"/>'.
          '</result>',
        'Context'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertMatchesRegularExpression(
        '(^\\d{4}-\\d{2}-\\d{2}$)',
        $result->documentElement->textContent
      );
    }

    public function testCurrentTimeTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:current-time()"/>'.
          '</result>',
        'Context'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertMatchesRegularExpression(
        '(^\\d{2}:\\d{2}:\\d{2}(?:\\.\\d{3})?(?:Z|[+-]\\d{2}:\\d{2})$)',
        $result->documentElement->textContent
      );
    }

    public function testImplicitTimeZoneTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:implicit-timezone()"/>'.
          '</result>',
        'Context'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame(
        'PT0S', $result->documentElement->textContent
      );
    }

    public function testDefaultLanguageTroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:default-language()"/>'.
          '</result>',
        'Context'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertMatchesRegularExpression(
        '(^[a-z]{2,3}_[A-Z]{2,3}$)',
        $result->documentElement->textContent
      );
    }
  }
}
