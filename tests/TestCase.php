<?php

namespace Carica\XSLTFunctions {

  require_once __DIR__.'/../vendor/autoload.php';

  use DOMDocument;
  use PHPUnit\Framework\TestCase as PHPUnitTestCase;

  abstract class TestCase extends PHPUnitTestCase {

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

    protected function prepareStylesheetDocument(string $template, string $import = NULL): DOMDocument {
      $stylesheet = new DOMDocument();
      $stylesheet->preserveWhiteSpace = FALSE;
      $stylesheet->loadXML(self::BASE_XSLT_TEMPLATE);
      if (NULL !== $import) {
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
  }
}
