<?php

namespace Carica\XSLTFunctions\DateTime {

  class Date {

    private const PATTERN = '(
      ^
      (?<year>\\d{4})
      -
      (?<month>\\d{2})
      -
      (?<day>\\d{2})
      (?<offset>(?:Z|[+-]\\d{2}:\\d{2}))?
      $
    )x';

    private $_year = 2000;
    private $_month = 1;
    private $_day = 1;
    private $_offset;

    public function __construct(string $date) {
      if (preg_match(self::PATTERN, $date, $matches)) {
        $this->_year = (int)($matches['year'] ?? 0);
        $this->_month = (int)($matches['month'] ?? 0);
        $this->_day = (int)($matches['day'] ?? 0);
        if (isset($matches['offset'])) {
          $this->_offset = new Offset($matches['offset']);
        }
      }
    }

    public function __toString(): string {
      $result = sprintf(
        '%1$04d-%2$02d-%3$02d', $this->_year, $this->_month, $this->_day
      );
      if (NULL !== $this->_offset) {
        $result .= $this->_offset;
      }
      return $result;
    }

    public function getYear(): int {
      return $this->_year;
    }

    public function getMonth(): int {
      return $this->_month;
    }

    public function getDay(): int {
      return $this->_day;
    }

    public function getOffset(): ?Offset {
      return $this->_offset;
    }

    public function withoutOffset(): self {
      $date = clone $this;
      $date->_offset = NULL;
      return $date;
    }
  }
}
