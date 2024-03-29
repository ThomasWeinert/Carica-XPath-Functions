<?php

namespace Carica\XPathFunctions\DateTime {

  use Carica\XPathFunctions\Duration\Duration;
  use Carica\XPathFunctions\XpathError;

  class Offset {

    private const PATTERN = '(
      ^
      (?<sign>[+-])
      (?<hours>\\d{2})
      :
      (?<minutes>\\d{2})
      $
    )x';

    private $_isNegative = FALSE;
    private $_isStandard = FALSE;
    private $_hours = 0;
    private $_minutes = 0;

    public function __construct(string $offset) {
      if ($offset === 'Z') {
        $this->_isStandard = TRUE;
      } elseif (preg_match(self::PATTERN, $offset, $matches)) {
        $this->_isNegative = ($matches['sign'] ?? '') === '-';
        $this->_hours = (int)($matches['hours'] ?? 0);
        $this->_minutes = (int)($matches['minutes'] ?? 0);
      }
    }

    public function __toString(): string {
      if ($this->_isStandard) {
        return 'Z';
      }
      return sprintf(
        '%1$s%2$02d:%3$02d',
        $this->_isNegative ? '-' : '+',
        $this->_hours,
        $this->_minutes
      );
    }

    public function isNegative(): bool {
      return $this->_isNegative;
    }

    public function getHours(): int {
      return $this->_hours * ($this->_isNegative ? -1 : 1);
    }

    public function getMinutes(): int {
      return $this->_minutes * ($this->_isNegative ? -1 : 1);
    }

    public function asDuration(): Duration {
      return new Duration(
        ($this->_isNegative ? '-' : '').'PT'.
        $this->_hours.'H'.$this->_minutes.'M'
      );
    }

    public function asInteger(): int {
      return $this->getHours() * 60 + $this->getMinutes();
    }

    /**
     * @return TimezoneDuration
     * @throws XpathError
     */
    public function asTimezoneDuration(): TimezoneDuration {
      return new TimezoneDuration(
        ($this->_isNegative ? '-' : '').'PT'.
        $this->_hours.'H'.$this->_minutes.'M'
      );
    }

    public function compareWith(self $offset): int {
      return $this->asDuration()->compareWith($offset->asDuration());
    }

    public function asPHPTimezone(): \DateTimeZone {
      return new \DateTimeZone(
        sprintf(
          '%1$s%2$.02d%3$.02d',
          $this->_isNegative ? '-' : '+',
          $this->_hours,
          $this->_minutes
        )
      );
    }
  }
}
