<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\MapsAndArrays {

  use Carica\XSLTFunctions\Namespaces;

  abstract class Maps {

    private const DUPLICATES_USE_FIRST = 'use-first';
    private const DUPLICATES_USE_LAST = 'use-last';
    private const DUPLICATES_USE_ANY = 'use-any';
    private const DUPLICATES_REJECT = 'reject';
    private const DUPLICATES_COMBINE = 'combine';

    private const ELEMENT_NAMES = [
      'array', 'boolean', 'map', 'number', 'null', 'string'
    ];

    public static function create(...$arguments): \DOMNode {
      $document = new \DOMDocument('1.0', 'UTF-8');
      $document->appendChild(
        $map = $document->createElementNS(Namespaces::XMLNS_FN, 'map')
      );
      foreach ($arguments as $argument) {
        if (is_array($argument)) {
          $argument = $argument[0];
        }
        if ($argument instanceof \DOMDocument) {
          $argument = $argument->documentElement;
        }
        if (
          $argument instanceof \DOMElement &&
          (string)$argument->getAttribute('key') !== '' &&
          in_array($argument->localName, self::ELEMENT_NAMES, TRUE)
        ) {
          $key = $argument->getAttribute('key');
          if (isset($added[$key])) {
            continue;
          }
          $added[$key] = TRUE;
          $document->appendChild(
            $entry = $document->createElementNS(Namespaces::XMLNS_FN, $argument->localName)
          );
          $entry->setAttribute('key', $key);
          foreach ($argument->childNodes as $childNode) {
            $entry->appendChild($document->importNode($childNode, TRUE));
          }
        }
      }
      return $document->documentElement;
    }

    public static function merge($maps, $options): \DOMNode {
      $document = new \DOMDocument('1.0', 'UTF-8');
      $document->appendChild(
        $map = $document->createElementNS(Namespaces::XMLNS_FN, 'map')
      );
      var_dump($maps, $options);
      return $document->documentElement;
    }

    private static function argumentAsNode($argument): \DOMNode {
      $result = $argument;
      if (is_array($result)) {
        $result = $result[0];
      }
      if ($result instanceof \DOMDocument) {
        $result = $result->documentElement;
      }
      return $result;
    }
  }
}
