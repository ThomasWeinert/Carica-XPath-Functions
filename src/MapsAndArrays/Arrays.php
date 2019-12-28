<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\MapsAndArrays {

  use Carica\XSLTFunctions\Namespaces;

  abstract class Arrays {

    public function isArray(\DOMNode $node): bool {
      return $node->namespaceURI === Namespaces::XMLNS_FN && $node->localName === 'array';
    }
  }
}
