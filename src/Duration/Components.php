<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Duration {

  use DateInterval;

  abstract class Components {

    /**
     * @param string $duration
     * @return int
     */
    public static function yearsFromDuration(string $duration): int {
      $interval = Duration::normalize(Duration::parse($duration));
      return $interval->y * ($interval->invert ? -1 : 1);
    }

    public static function  monthsFromDuration(string $duration): int {
      $interval = Duration::normalize(Duration::parse($duration));
      return $interval->m * ($interval->invert ? -1 : 1);
    }
  }
}
