<?php

namespace Carica\XPathFunctions\DateTime {

  use Carica\XPathFunctions\Duration\Duration;
  use Carica\XPathFunctions\Namespaces;
  use Carica\XPathFunctions\XpathError;

  class TimezoneDuration extends Duration {

    /**
     * @param string $duration
     * @throws XpathError
     */
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

    public function asPHPTimezone(): \DateTimeZone {
      return new \DateTimeZone($this->asOffset());
    }

  }
}
