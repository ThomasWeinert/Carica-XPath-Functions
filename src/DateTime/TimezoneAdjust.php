<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\DateTime {

  use Carica\XSLTFunctions\XpathError;

  abstract class TimezoneAdjust {

    /**
     * @param string $dateTime
     * @param string $timezone
     * @return string
     * @throws XpathError
     */
    public static function adjustDateTimeToTimezone(string $dateTime, string $timezone): string {
      if ($timezone !== '') {
        return (string)(
          (new DateTime($dateTime))->adjustTimezone(
            new TimezoneDuration($timezone)
          )
        );
      }
      return (string)(
        (new DateTime($dateTime))->adjustTimezone()
      );
    }
  }
}
