<?php
declare(strict_types=1);

namespace Carica\XPathFunctions\DateTime {

  use Carica\XPathFunctions\Context;
  use Carica\XPathFunctions\Namespaces;
  use Carica\XPathFunctions\XpathError;
  use DateTime as PHPDateTime;
  use IntlDateFormatter;
  use IntlTimeZone;

  abstract class Formatting {

    private static $_FORMATS = [
      'SHORT' => IntlDateFormatter::SHORT,
      'MEDIUM' => IntlDateFormatter::MEDIUM,
      'LONG' => IntlDateFormatter::LONG,
      'FULL' => IntlDateFormatter::FULL,
      'RELATIVE_SHORT' => IntlDateFormatter::RELATIVE_SHORT,
      'RELATIVE_MEDIUM' => IntlDateFormatter::RELATIVE_MEDIUM,
      'RELATIVE_LONG' => IntlDateFormatter::RELATIVE_LONG,
      'RELATIVE_FULL' => IntlDateFormatter::RELATIVE_FULL,
    ];

    private static $_CALENDARS = [
      'AD' => IntlDateFormatter::GREGORIAN,
    ];

    /**
     * @param string $dateString
     * @param string $picture
     * @param string $language
     * @param string $calendar
     * @param string $place
     * @return string
     * @throws XpathError
     */
    public static function formatDate(
      string $dateString,
      string $picture,
      string $language = 'en',
      string $calendar = 'AD',
      string $place = ''
    ): string {
      if (empty($dateString)) {
        return '';
      }
      $date = new Date($dateString);
      if ($format = self::$_FORMATS[$picture] ?? false) {
        $formatter = self::getDateTimeFormatter(
          $format,
          IntlDateFormatter::NONE,
          self::getTimezoneFromOffset(
            $place,
            $date->getOffset()?->asInteger()
          ),
          $language,
          $place,
          $calendar,
        );
        return $formatter->format(new PHPDateTime((string)$date)) ?: '';
      }
      return preg_replace_callback(
        '(\\[{2}|]{2}|\\[[^\\]]+])',
        static function ($match) use ($date): string {
          if ($match[0] === '[[' || $match[0] === ']]') {
            return $match[0][0];
          }
          var_dump($match);
          return self::interpretVariableMarker($match[0], $date);
        },
        $picture
      );
    }

    private static function interpretVariableMarker(
      string $marker, Date $date
    ): string {
      $matched = preg_match(
        '(^\\[
          (?<component>[a-zA-Z])
          (?:
            (?<modifier>
              (?:\\p{Nd}|\\#|[^\\p{N}\\p{L}])+|
              [aAiIwW]|
              Ww
            )
            (?<secondary_modifier>[atco])?
          )?
          (?:,(?<minimum_width>[\d*]+)(?:-(?<maximum_width>[\d*]+))?)?
        ])x',
        $marker,
        $matches
      );
      if (!$matched) {
        throw new XpathError(
          Namespaces::XMLNS_ERR.'#FOFD1340',
          'Invalid date/time formatting parameters.'
        );
      }
      switch ($matches['component']) {
      case 'Y':
        return (string)$date->getYear();
      case 'M':
        return (string)$date->getMonth();
      case 'D':
        return (string)$date->getDay();
      }
      return '';
    }

    /**
     * @param string $dateTimeString
     * @param string $picture
     * @param string $language
     * @param string $calendar
     * @param string $place
     * @return string
     * @throws XpathError
     */
    public static function formatDateTime(
      string $dateTimeString,
      string $picture,
      string $language = 'en',
      string $calendar = 'AD',
      string $place = ''
    ): string {
      if (empty($dateTimeString)) {
        return '';
      }
      $dateTime = new DateTime($dateTimeString);
      $formatter = self::getDateTimeFormatter(
        self::$_FORMATS[$picture] ?: IntlDateFormatter::SHORT,
        self::$_FORMATS[$picture] ?: IntlDateFormatter::SHORT,
        $dateTime->getTimeZone(),
        $language,
        $place,
        $calendar,
      );
      return $formatter->format($dateTime->getTimestamp()) ?: '';
    }

    /**
     * @param string|int $dateFormat
     * @param string|int $timeFormat
     * @param string|IntlTimeZone $timezone
     * @param string $language
     * @param string $place
     * @param string $calendar
     * @return IntlDateFormatter
     * @throws XpathError
     */
    private static function getDateTimeFormatter(
      string|int $dateFormat,
      string|int $timeFormat,
      string|IntlTimeZone $timezone,
      string $language,
      string $place,
      string $calendar = '',
    ): IntlDateFormatter {
      $locale = self::getLocale($language, $place);
      $formatter = IntlDateFormatter::create(
        $locale,
        $dateFormat,
        $timeFormat,
        $timezone,
        self::$_CALENDARS[$calendar] ?: IntlDateFormatter::GREGORIAN
      );
      if (!$formatter) {
        throw new XpathError(
          Namespaces::XMLNS_ERR.'#FOFD1340',
          'Invalid date/time formatting parameters.'
        );
      }
      return $formatter;
    }

    private static function getTimezoneFromOffset(
      string   $country,
      int|null $offset,
    ): IntlTimeZone {
      $timezones = FALSE;
      if ($country && $offset) {
        $timezones = IntlTimeZone::createTimeZoneIDEnumeration(
          IntlTimeZone::TYPE_CANONICAL,
          $country,
          $offset,
        );
      } elseif ($country) {
        $timezones = IntlTimeZone::createEnumeration(
          $country
        );
      } elseif ($offset) {
        $timezones = IntlTimeZone::createEnumeration(
          $offset
        );
      }
      if ($timezones) {
        return $timezones->current();
      }
      return IntlTimeZone::createDefault();
    }

    /**
     * @param string $language
     * @param string $place
     * @return false|string
     */
    public static function getLocale(string $language, string $place): string|false {
      if ($language && $place) {
        return \Locale::composeLocale(
          ['language' => $language, 'region' => $place]
        );
      }
      if ($language) {
        return \Locale::composeLocale(
          ['language' => $language]
        );
      }
      if ($place) {
        return \Locale::composeLocale(
          ['region' => $place]
        );
      }
      return Context::defaultLanguage();
    }
  }
}
