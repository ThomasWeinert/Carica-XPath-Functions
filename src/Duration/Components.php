<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Duration {

  use DateInterval;

  abstract class Components {

    public static function yearsFromDuration(string $duration): int {
      $interval = Duration::normalize(Duration::parse($duration));
      return $interval->y * ($interval->invert ? -1 : 1);
    }

    public static function monthsFromDuration(string $duration): int {
      $interval = Duration::normalize(Duration::parse($duration));
      return $interval->m * ($interval->invert ? -1 : 1);
    }

    public static function daysFromDuration(string $duration): int {
      $interval = Duration::normalize(Duration::parse($duration));
      return $interval->d * ($interval->invert ? -1 : 1);
    }

    public static function hoursFromDuration(string $duration): int {
      $interval = Duration::normalize(Duration::parse($duration));
      return $interval->h * ($interval->invert ? -1 : 1);
    }

    public static function minutesFromDuration(string $duration): int {
      $interval = Duration::normalize(Duration::parse($duration));
      return $interval->i * ($interval->invert ? -1 : 1);
    }
  }
}
