<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Duration {

  use DateInterval;

  class Duration {

    public static function parse(string $duration): DateInterval {
      $isNegative = FALSE;
      if (0 === strpos($duration, '-')) {
        $isNegative = TRUE;
        $duration = substr($duration, 1);
      }
      $milliseconds = 0;
      if (preg_match('((\.\d+)S)', $duration, $match)) {
        $milliseconds = (float)('0'.$match[0]);
        $duration = (string)preg_replace('((\.\d+)S)', 'S', $duration);
      }
      try {
        $interval = new DateInterval($duration);
        $interval->f = $milliseconds;
        $interval->invert = $isNegative;
      } catch (\Exception $e) {
        $interval = new DateInterval('P0Y');
      }
      return $interval;
    }

    public static function normalize(DateInterval $interval): DateInterval {
      $interval = clone $interval;
      if ($interval->s >= 60) {
        $interval->i += (int)floor($interval->s / 60);
        $interval->s %= 60;
      }
      if ($interval->i >= 60) {
        $interval->h += (int)floor($interval->i / 60);
        $interval->i %= 60;
      }
      if ($interval->h >= 24) {
        $interval->d += (int)floor($interval->h / 24);
        $interval->h %= 24;
      }
      if ($interval->m >= 12) {
        $interval->y += (int)floor($interval->m / 12);
        $interval->m %= 12;
      }
      return $interval;
    }
  }
}
