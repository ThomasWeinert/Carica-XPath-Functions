<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Strings {

  use DOMDocument;
  use DOMElement;
  use InvalidArgumentException;

  class RegExp {

    private const XMLNS_FUNCTIONS = 'http://www.w3.org/2005/xpath-functions';

    /**
     * @param string $input
     * @param string $pattern
     * @param string $flags
     * @return bool
     */
    public static function matches(string $input, string $pattern, string $flags = ''): bool {
      self::validatePattern($pattern);
      return (bool)preg_match('('.$pattern.')u'.$flags, $input);
    }

    public static function replace(string $input, string $pattern, string $replacement, string $flags = ''): string {
      self::validatePattern($pattern);
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
      } else {
        self::validatePattern($pattern);
      }
      $document = new DOMDocument();
      $document->appendChild(
        $document->createElementNS(self::XMLNS_FUNCTIONS, 'tokens')
      );
      foreach (preg_split('(('.$pattern.'))u'.$flags, $input) as $tokenString) {
        $token = $document->documentElement->appendChild(
          $document->createElementNS(self::XMLNS_FUNCTIONS, 'token')
        );
        $token->textContent = $tokenString;
      }
      return $document->documentElement;
    }

    public static function analyzeString(string $input, string $pattern, string $flags): DOMElement {
      self::validatePattern($pattern);
      $matchPattern = '('.$pattern.')u'.$flags;
      $document = new DOMDocument();
      $document->appendChild(
        $document->createElementNS(self::XMLNS_FUNCTIONS, 'analyze-string-result')
      );
      $offset = 0;
      preg_match_all($matchPattern, $input, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
      foreach ($matches as $matchGroup) {
        [$matchContent, $matchOffset] = $matchGroup[0];
        if ($offset < $matchOffset) {
          $nonMatchNode = $document->documentElement->appendChild(
            $document->createElementNS(self::XMLNS_FUNCTIONS, 'non-match')
          );
          $nonMatchNode->textContent = substr($input, $offset, $matchOffset - $offset);
        }
        $offset = $matchOffset + strlen($matchContent);
        $matchNode = $document->documentElement->appendChild(
          $document->createElementNS(self::XMLNS_FUNCTIONS, 'match')
        );
        if (count($matchGroup) === 1) {
          $matchNode->textContent = $matchContent;
        } else {
          $index = 1;
          $groupOffset = $matchOffset;
          for ($i = 1, $c = count($matchGroup); $i < $c; $i++) {
            [$subMatchContent, $subMatchOffset] = $matchGroup[$i];
            if ($subMatchOffset < $groupOffset) {
              continue;
            }
            if ($subMatchOffset > $groupOffset) {
              $matchNode->appendChild(
                $document->createTextNode(
                  substr($input, $groupOffset, $subMatchOffset - $groupOffset)
                )
              );
            }
            $groupOffset = $subMatchOffset + strlen($subMatchContent);
            $matchNode->appendChild(
              $matchGroupNode = $document->createElementNS(self::XMLNS_FUNCTIONS, 'group')
            );
            $matchGroupNode->setAttribute('nr', (string)$index++);
            $matchGroupNode->textContent = $subMatchContent;
          }
        }
      }
      return $document->documentElement;
    }

    private static function validatePattern(string $pattern): void {
      if (empty($pattern)) {
        throw new InvalidArgumentException('Empty pattern argument is not allowed');
      }
    }
  }
}
