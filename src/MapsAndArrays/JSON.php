<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\MapsAndArrays {

  use Carica\XSLTFunctions\Namespaces;
  use Carica\XSLTFunctions\Sequences\External;
  use Carica\XSLTFunctions\XpathError;
  use JsonException;

  abstract class JSON {

    public static function xmlToJSON(string $jsonData): \DOMDocument {
      $document = new \DOMDocument('1.0', 'UTF-8');
      try {
        $json = json_decode($jsonData, FALSE, 512, JSON_THROW_ON_ERROR);
        self::transferTo($document, $json);
      } catch (JsonException $e) {
        throw new XpathError('err:FOJS0001', 'JSON syntax error.');
      }
      return $document;
    }

    public static function jsonDoc(string $href): \DOMDocument {
      return self::xmlToJSON(External::unparsedText($href));
    }

    private static function transferTo(\DOMNode $parent, $value, string $key = NULL): void {
      $document = $parent instanceof \DOMDocument ? $parent : $parent->ownerDocument;
      if (
        $document instanceof \DOMDocument &&
        (
          $parent instanceof \DOMDocument || $parent instanceof \DOMElement
        )
      ) {
        $child = NULL;
        if ($value instanceof \stdClass) {
          $parent->appendChild(
            $child = $document->createElementNS(Namespaces::XMLNS_FN, 'map')
          );
          foreach (get_object_vars($value) as $childKey => $childValue) {
            self::transferTo($child, $childValue, $childKey);
          }
        } elseif (is_array($value)) {
          $parent->appendChild(
            $child = $document->createElementNS(Namespaces::XMLNS_FN, 'array')
          );
          foreach ($value as $childValue) {
            self::transferTo($child, $childValue);
          }
        } elseif (NULL === $value) {
          $parent->appendChild(
            $child = $document->createElementNS(Namespaces::XMLNS_FN, 'null')
          );
        } elseif (is_bool($value)) {
          $parent->appendChild(
            $child = $document->createElementNS(Namespaces::XMLNS_FN, 'boolean')
          );
          $child->textContent = $value ? 'true' : 'false';
        } elseif (is_int($value) || is_float($value)) {
          $parent->appendChild(
            $child = $document->createElementNS(Namespaces::XMLNS_FN, 'number')
          );
          $child->textContent = $value;
        } elseif (is_string($value)) {
          $parent->appendChild(
            $child = $document->createElementNS(Namespaces::XMLNS_FN, 'string')
          );
          $child->textContent = $value;
        }
        if ($child && $key) {
          $child->setAttribute('key', $key);
        }
      }
    }
  }
}
