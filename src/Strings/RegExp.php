<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Strings {

  use DOMDocument;
  use DOMElement;
  use DOMNodeList;
  use InvalidArgumentException;

  class RegExp {

    /**
     * @param string $input
     * @param string $pattern
     * @param string $flags
     * @return bool
     */
    public static function matches(string $input, string $pattern, string $flags = ''): bool {
      return (bool)preg_match('('.$pattern.')u'.$flags, $input);
    }

    public static function replace(string $input, string $pattern, string $replacement, string $flags = ''): string {
      return preg_replace('('.$pattern.')u'.$flags, $replacement, $input);
    }

    /**
     * @param string $input
     * @param string|FALSE $pattern
     * @param string $flags
     * @return \DOMElement
     */
    public static function tokenize(string $input, $pattern = ' ', string $flags = ''): DOMElement {
      if ($pattern === FALSE) {
        $pattern = '\\s+';
        $input = trim($input);
      } elseif (empty($pattern)) {
        throw new InvalidArgumentException('Empty pattern argument is not allowed');
      }
      $document = new DOMDocument();
      $document->appendChild($document->createElement('tokens'));
      foreach (preg_split('(('.$pattern.'))u'.$flags, $input) as $tokenString) {
        $token = $document->documentElement->appendChild($document->createElement('token'));
        $token->textContent = $tokenString;
      }
      return $document->documentElement;
    }
  }
}
