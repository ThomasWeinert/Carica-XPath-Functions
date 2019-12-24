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

    public static function hoursFromDateTime(string $dateTime): int {
      return (int)(new DateTimeImmutable($dateTime))->format('H');
    }

    public static function minutesFromDateTime(string $dateTime): int {
      return (int)(new DateTimeImmutable($dateTime))->format('i');
    }

    public static function secondsFromDateTime(string $dateTime): float {
      return (float)(new DateTimeImmutable($dateTime))->format('s.v');
    }

    public static function timezoneFromDateTime(string $dateTime): ?string {
      return self::timezoneFromStringEnd($dateTime);
    }

    public static function yearFromDate(string $date): int {
      return (new Date($date))->getYear();
    }

    public static function monthFromDate(string $date): int {
      return (new Date($date))->getMonth();
    }

    public static function dayFromDate(string $date): int {
      return (new Date($date))->getDay();
    }

    public static function hoursFromTime(string $time): int {
      return (new Time($time))->getHour();
    }

    public static function minutesFromTime(string $time): int {
      return (new Time($time))->getMinute();
    }

    public static function secondsFromTime(string $time): float {
      return (new Time($time))->getSecond();
    }

    public static function timezoneFromTime(string $time): ?string {
      return self::timezoneFromStringEnd($time);
    }

    private static function timezoneFromStringEnd(string $input): ?string {
      if (preg_match('((?:Z|[+-]\\d{2}:\\d{2})$)', $input, $matches)) {
        $offset = new Offset($matches[0]);
        return (string)$offset->getDuration();
      }
      return NULL;
    }
  }
}
