<?php
declare(strict_types=1);

namespace Carica\XPathFunctions {

  use Carica\XPathFunctions\DateTime\TimezoneDuration;
  use Carica\XPathFunctions\Strings\Collators\CollatorFactory;
  use DateTime;
  use DateTimeInterface;
  use Locale;

  abstract class Context {

    /**
     * @var TimezoneDuration | NULL
     */
    private static TimezoneDuration | NULL $_implicitTimezone = NULL;

    public static function currentDateTime() : string {
      return (new DateTime())->format(DateTimeInterface::RFC3339_EXTENDED);
    }

    public static function currentDate() : string {
      return (new DateTime())->format('Y-m-d');
    }

    public static function currentTime() : string {
      return (new DateTime())->format('H:i:s.vP');
    }

    /**
     * @return TimezoneDuration
     * @throws XpathError
     */
    public static function implicitTimezone() : TimezoneDuration {
      if (NULL === self::$_implicitTimezone) {
        self::$_implicitTimezone = new TimezoneDuration(
            preg_replace(
            '(^(?:\\+|([+-]))0?(\\d?\\d):0?(\\d?\\d)$)',
            '$1PT$2H$3M',
            (new DateTime())->format('P')
          )
        );
      }
      return self::$_implicitTimezone;
    }

    public static function setImplicitTimezone(TimezoneDuration $duration): void {
      self::$_implicitTimezone = $duration;
    }

    public static function defaultCollation(): string {
      return CollatorFactory::getDefaultCollation();
    }

    public static function setDefaultCollation(string $collation): void {
      CollatorFactory::setDefaultCollation($collation);
    }

    public static function defaultLanguage(): string {
      return Locale::getDefault();
    }

    public static function reset(): void {
      CollatorFactory::reset();
      self::$_implicitTimezone = NULL;
    }
  }
}
