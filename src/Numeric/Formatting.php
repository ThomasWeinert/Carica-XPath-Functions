<?php
declare(strict_types=1);

namespace Carica\XPathFunctions\Numeric {

  use Carica\XPathFunctions\Namespaces;
  use Carica\XPathFunctions\XpathError;
  use NumberFormatter;

  abstract class Formatting {

    private const IGNORE = [
      'de' => ['und']
    ];

    /**
     * @param float $input
     * @param string $picture
     * @param string $language
     * @return string
     * @throws XpathError
     */
    public static function formatInteger(float $input, string $picture, string $language): string {
      switch ($picture) {
      case 'A':
        return self::toSequenceValue((int) $input, 'ABCDEFGHIJKLMNOPQRTSUVWXYZ');
      case 'a':
        return self::toSequenceValue((int) $input, 'abcdefghijklmnopqrtsuvwxyz');
      case 'I':
        return self::toRomanNumber((int) $input);
      case 'i':
        return strtolower(self::toRomanNumber((int) $input));
      case 'w':
        $formatter = NumberFormatter::create($language, NumberFormatter::SPELLOUT);
        return \Carica\XPathFunctions\Strings\Values::lowerCase(
          $formatter->format((int)$input)
        );
      case 'W':
        $formatter = NumberFormatter::create($language, NumberFormatter::SPELLOUT);
        return \Carica\XPathFunctions\Strings\Values::upperCase(
          $formatter->format((int)$input)
        );
      case 'Ww':
        $formatter = NumberFormatter::create($language, NumberFormatter::SPELLOUT);
        return self::capitalizeNumberWords($formatter->format((int)$input), $language);
      }
      $hasDigit = preg_match('(\\p{Nd})u', $picture);
      if ($hasDigit && !preg_match('(^((\\p{Nd}|#|[^\\p{N}\\p{L}])+?)$)Du', $picture)) {
        throw new XpathError(
          Namespaces::XMLNS_ERR.'#FODF1310',
          ' Invalid decimal format picture string'
        );
      }
      $formatter = NumberFormatter::create($language, NumberFormatter::PATTERN_DECIMAL);
      if (!$formatter->setPattern($picture)) {
        throw new XpathError(Namespaces::XMLNS_ERR.'#FODF1310', 'Invalid decimal format picture string.');
      }
      return $formatter->format((int)$input);
    }

    private static function toSequenceValue(int $input, string $sequence): string {
      $isNegative = $input < 0;
      $result = '';
      $current = abs($input);
      $sequenceLength = strlen($sequence);
      if ($current <= $sequenceLength) {
        $result = $sequence[$current - 1];
      } else {
        while ($current > 0) {
          $result = $sequence[(--$current) % $sequenceLength].$result;
          $current = (int)($current / $sequenceLength);
        }
      }
      return $isNegative ? '-'.$result : $result;
    }

    private static function toRomanNumber(int $input): string {
      $map = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1
      ];
      $result = '';
      while ($input > 0) {
        foreach ($map as $roman => $value) {
          if($input >= $value) {
            $input -= $value;
            $result .= $roman;
            break;
          }
        }
      }
      return $result;
    }

    private static function capitalizeNumberWords(bool|string $input, string $language) {
      $ignoreWords = self::IGNORE[$language] ?? [];
      $transliterator = \Transliterator::create('Any-Upper');
      return preg_replace_callback(
        "(
            (?<prefix>^|[\u{00AD}\\s-])
            (?<word>
              (?<first_char>[^\u{00AD}\\s])
              (?<chars>[^\u{00AD}\\s]*)
            )
          )ux",
        static function($match) use ($transliterator, $ignoreWords): string {
          if (in_array($match['word'], $ignoreWords, TRUE)) {
            return $match[0];
          }
          return $match['prefix'].$transliterator->transliterate($match['first_char']).$match['chars'];
        },
        $input
      );
    }
  }
}
