<?php
declare(strict_types=1);

namespace Carica\XpathFunctions\Sequences {

  use Carica\XpathFunctions\Namespaces;
  use Carica\XpathFunctions\XpathError;

  abstract class Parse {

    public static function parseXML(string $xml): \DOMDocument {
      try {
          $document = new \DOMDocument();
          $document->loadXML($xml);
          return $document;
      } catch (\Throwable $e) {
        throw new XpathError(
          Namespaces::XMLNS_ERR.'#FODC0006',
          'String passed to fn:parse-xml is not a well-formed XML document.'
        );
      }
    }

    public static function parseXMLFragment(string $data): \DOMDocument {
      try {
          $document = new \DOMDocument();
          $fragment = $document->createDocumentFragment();
          $fragment->appendXML($data);
          $document->appendChild($fragment);
          return $document;
      } catch (\Throwable $e) {
        throw new XpathError(
          Namespaces::XMLNS_ERR.'#FODC0006',
          'String passed to fn:parse-xml is not a well-formed XML document.'
        );
      }
    }
  }
}
