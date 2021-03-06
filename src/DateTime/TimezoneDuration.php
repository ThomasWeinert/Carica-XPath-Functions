<?php

namespace Carica\XpathFunctions\DateTime {

  use Carica\XpathFunctions\Duration\Duration;
  use Carica\XpathFunctions\Namespaces;
  use Carica\XpathFunctions\XpathError;

  class TimezoneDuration extends Duration {

    public function __construct(string $duration) {
      parent::__construct($duration);
      if (
        $this->compareWith(new Duration('PT14H')) > 0 ||
        $this->compareWith(new Duration('-PT14H')) < 0
      ) {
        throw new XpathError(Namespaces::XMLNS_ERR.'#FODT0003', 'Invalid timezone value.');
      }
    }

    public function asOffset(): Offset {
      return new Offset(
        sprintf(
          '%1$s%2$02d:%3$02d',
          ($this->isNegative() ? '-' : '+'),
          abs($this->getHours()),
          abs($this->getMinutes())
        )
      );
    }

  }
}
