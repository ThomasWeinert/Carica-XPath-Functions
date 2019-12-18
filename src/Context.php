<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions {

  use Carica\XSLTFunctions\Strings\Collators\CollatorFactory;
  use DateTime;
  use Locale;

  abstract class Context {

    public static function currentDateTime() : string {
      return (new DateTime())->format(DateTime::RFC3339_EXTENDED);
    }

    public static function currentDate() : string {
      return (new DateTime())->format('Y-m-d');
    }

    public static function currentTime() : string {
      return (new DateTime())->format('H:i:s.vP');
    }

    public static function implicitTimeZone() : string {
      return preg_replace(
        '(^(?:\\+|([+-]))0?(\\d?\\d):0?(\\d?\\d)$)',
        '$1PT$2H$3M',
        (new DateTime())->format('P')
      );
    }

    public static function defaultCollation(): string {
      return CollatorFactory::getDefaultCollation();
    }

    public static function defaultLanguage(): string {
      return Locale::getDefault();
    }
  }
}
