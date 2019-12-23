<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\DateTime {

  use Carica\XSLTFunctions\XpathError;
  use DateTimeImmutable;

  abstract class Components {

    public static function dateTime(string $dateString, string $timeString): string {
      $date = new Date($dateString);
      $time = new Time($timeString);
      $dateOffset = $date->getOffset();
      $timeOffset = $time->getOffset();
      if ($dateOffset !== NULL && $timeOffset !== NULL) {
        if ($dateOffset->compareWith($timeOffset) !== 0) {
          throw new XpathError(
            'err:FORG0008',
            'The two arguments to fn:dateTime have inconsistent timezones.'
          );
        }
        $offset = $dateOffset;
      } elseif ($dateOffset !== NULL) {
        $offset = $dateOffset;
      } elseif ($timeOffset !== NULL) {
        $offset = $timeOffset;
      } else {
        $offset = '';
      }
      $dateTime = new DateTimeImmutable(
        $date->withoutOffset().'T'.$time->withoutOffset().($offset ?: 'Z')
      );
      return str_replace(
        '.000',
        '',
        $dateTime->format('Y-m-d\\TH:i:s.v')
      ).$offset;
    }

    public static function yearFromDateTime(string $dateTime): int {
      return (int)(new DateTimeImmutable($dateTime))->format('Y');
    }

    public static function monthFromDateTime(string $dateTime): int {
      return (int)(new DateTimeImmutable($dateTime))->format('m');
    }

    public static function dayFromDateTime(string $dateTime): int {
      return (int)(new DateTimeImmutable($dateTime))->format('d');
    }
  }
}
