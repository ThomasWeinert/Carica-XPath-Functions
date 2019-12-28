<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\MapsAndArrays {

  use Carica\XSLTFunctions\Namespaces;

  abstract class Maps {

    public function isMap(\DOMNode $node): bool {
      return $node->namespaceURI === Namespaces::XMLNS_FN && $node->localName === 'map';
    }
  }
}
