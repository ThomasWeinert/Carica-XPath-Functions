<?php

namespace Carica\XpathFunctions {

  require_once __DIR__.'/../vendor/autoload.php';

  use DOMDocument;
  use PHPUnit\Framework\TestCase as PHPUnitTestCase;

  abstract class TestCase extends PHPUnitTestCase {

    public const XMLNS_XSL = 'http://www.w3.org/1999/XSL/Transform';

    private const BASE_XSLT_TEMPLATE =
      '<?xml version="1.0" encoding="utf-8"?>'."\n".
      '<xsl:stylesheet'."\n".
      '  version="1.0"'."\n".
      '  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"'."\n".
      '  xmlns:fn="http://www.w3.org/2005/xpath-functions"'."\n".
      '  extension-element-prefixes="fn">'."\n".
      '  <xsl:template match="/">'."\n".
      '  </xsl:template>'."\n".
      '</xsl:stylesheet>';

    public function tearDown(): void {
      parent::tearDown();
      Context::reset();
    }

    protected function prepareStylesheetDocument(string $template, string ...$imports): DOMDocument {
      $stylesheet = new DOMDocument();
      $stylesheet->preserveWhiteSpace = FALSE;
      $stylesheet->loadXML(self::BASE_XSLT_TEMPLATE);
      foreach ($imports as $import) {
        $stylesheet->documentElement->insertBefore(
          $importNode = $stylesheet->createElementNS('http://www.w3.org/1999/XSL/Transform', 'xsl:import'),
          $stylesheet->documentElement->firstChild
        );
        $importNode->setAttribute('href', 'xpath-functions://'.$import);
      }
      $fragment = $stylesheet->createDocumentFragment();
      $fragment->appendXML($template);
      $stylesheet->documentElement->lastChild->appendChild($fragment);
      return $stylesheet;
    }

    protected function prepareInputDocument(string $xml = '<test/>'): DOMDocument {
      $input = new DOMDocument();
      $input->loadXML($xml);
      return $input;
    }

    /**
     * @param string $expected
     * @param callable $trigger
     * @throws XpathError
     */
    public function assertXpathErrorTriggeredBy(string $expected, callable $trigger): void {
      $this->expectException(XpathError::class);
      try {
        $trigger();
      } /** @noinspection PhpRedundantCatchClauseInspection */ catch (XpathError $exception) {
        $this->assertSame($expected, $exception->getURI());
        throw $exception;
      }
    }
  }
}
