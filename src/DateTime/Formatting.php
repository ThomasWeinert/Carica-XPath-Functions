<?php
declare(strict_types=1);

namespace Carica\XPathFunctions\DateTime {

  use Carica\XPathFunctions\Context;
  use Carica\XPathFunctions\Namespaces;
  use Carica\XPathFunctions\Numeric\Formatting as NumericFormatting;
  use Carica\XPathFunctions\XpathError;
  use IntlDateFormatter;
  use IntlTimeZone;

  abstract class Formatting {

    private static $_FORMATS = [
      'SHORT' => [
        'date' => IntlDateFormatter::SHORT,
        'time' => IntlDateFormatter::SHORT,
      ],
      'MEDIUM' => [
        'date' => IntlDateFormatter::MEDIUM,
        'time' => IntlDateFormatter::MEDIUM,
      ],
      'LONG' => [
        'date' => IntlDateFormatter::LONG,
        'time' => IntlDateFormatter::LONG,
      ],
      'FULL' => [
        'date' => IntlDateFormatter::FULL,
        'time' => IntlDateFormatter::FULL,
      ],
      'RELATIVE_SHORT' => [
        'date' => IntlDateFormatter::RELATIVE_SHORT,
        'time' => IntlDateFormatter::RELATIVE_SHORT,
      ],
      'RELATIVE_MEDIUM' => [
        'date' => IntlDateFormatter::RELATIVE_MEDIUM,
        'time' => IntlDateFormatter::RELATIVE_MEDIUM,
      ],
      'RELATIVE_LONG' => [
        'date' => IntlDateFormatter::RELATIVE_LONG,
        'time' => IntlDateFormatter::RELATIVE_LONG,
      ],
      'RELATIVE_FULL' => [
        'date' => IntlDateFormatter::RELATIVE_FULL,
        'time' => IntlDateFormatter::RELATIVE_FULL,
      ],
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
          $format['date'] ?? IntlDateFormatter::SHORT,
          IntlDateFormatter::NONE,
          self::getTimezoneFromOffset(
            $place,
            $date->getOffset()?->asInteger()
          ),
          $language,
          $place,
          $calendar,
        );
        return $formatter->format($date->asPHPDateTime()) ?: '';
      }
      return preg_replace_callback(
        '(\\[{2}|]{2}|\\[[^\\]]+])',
        static function ($match) use ($date, $language, $place): string {
          if ($match[0] === '[[' || $match[0] === ']]') {
            return $match[0][0];
          }
          return self::interpretVariableMarker(
            $match[0], $date, $language, $place
          );
        },
        $picture
      );
    }

    private static function interpretVariableMarker(
      string $marker, Date|DateTime $date, string $language, string $place
    ): string {
      $matched = preg_match(
        '(^\\[
          (?<component>[a-zA-Z])
          (?:
            (?<modifier>
              (?:\\p{Nd}|\\#|[^\\p{N}\\p{L}])+|
              [aAiIwWnN]|
              Ww|
              Nn
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
      $modifier = $matches['modifier'] ?? '';
      $secondaryModifier = $matches['secondary_modifier'] ?? 'a';
      $minimumWidth = (int)($matches['minimum_width'] ?? -1);
      $maximumWidth = (int)($matches['maximum_width'] ?? -1);
      $useComponentTitle = ($modifier === 'n' || $modifier === 'N' || $modifier === 'Nn');
      $value = NULL;
      $format = '';
      switch ($matches['component']) {
      case 'Y':
        // year (absolute value)
        $value = $date->getYear();
        break;
      case 'M':
        // month in year
        $value = $date->getMonth();
        $format = 'LLLL';
        break;
      case 'D':
        // day in month
        $value = $date->getDay();
        break;
      case 'd':
        // day in year
        $value = $date->getDay();
        break;
      case 'F':
        // day of week
        $value = $date->getDay();
        break;
      case 'W':
        // week in year
        $value = $date->getDay();
        break;
      case 'w':
        // week in month
        $value = $date->getDay();
        break;
      case 'Z':
        // timezone 	01:01
        $value = $date->getDay();
        break;
      case 'z':
        // timezone 	same as Z, but modified where appropriate to include a prefix as a
        // time offset using GMT, for example GMT+1 or GMT-05:00. For this component there
        // is a fixed prefix of GMT, or a localized variation thereof for the chosen language,
        // and the remainder of the value is formatted as for specifier Z. 	01:01
        $value = $date->getDay();
        break;
      case 'C':
        // calendar: the name or abbreviation of a calendar name
        $value = $date->getDay();
        break;
      case 'E':
        // era: the name of a baseline for the numbering of years, for example the reign of a
        // monarch
        $value = $date->getDay();
        break;
      }

      if ($value) {
        if ($useComponentTitle) {
          // get as capitalized word
          $locale = self::getLocale($language, $place);
          $calendar = \IntlCalendar::createInstance(
            $date->getOffset(),
            $locale
          );
          // get as word
        } else {
          return NumericFormatting::formatInteger(
            $value, $modifier, $language
          );
        }
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
      if ($format = self::$_FORMATS[$picture] ?? false) {
        $formatter = self::getDateTimeFormatter(
          $format['date'] ?? IntlDateFormatter::SHORT,
          $format['time'] ?? IntlDateFormatter::SHORT,
          $dateTime->getTimeZone(),
          $language,
          $place,
          $calendar,
        );
        return $formatter->format($dateTime->asPHPDateTime()) ?: '';
      }
      return preg_replace_callback(
        '(\\[{2}|]{2}|\\[[^\\]]+])',
        static function ($match) use ($dateTime, $language, $place): string {
          if ($match[0] === '[[' || $match[0] === ']]') {
            return $match[0][0];
          }
          return self::interpretVariableMarker(
            $match[0], $dateTime, $language, $place
          );
        },
        $picture
      );
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
