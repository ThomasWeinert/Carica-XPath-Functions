<?php

namespace Carica\XSLTFunctions\DateTime {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\Context;
  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XpathError;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\DateTime\Components
   */
  class TimezoneAdjustTest extends TestCase {

    /**
     * @param string $expected
     * @param string $dateTime
     * @param string $timezone
     * @testWith
     *   ["2002-03-07T10:00:00-05:00", "2002-03-07T10:00:00"]
     *   ["2002-03-07T12:00:00-05:00", "2002-03-07T10:00:00-07:00"]
     *   ["2002-03-07T10:00:00-10:00", "2002-03-07T10:00:00", "-PT10H"]
     *   ["2002-03-07T07:00:00-10:00", "2002-03-07T10:00:00-07:00", "-PT10H"]
     *   ["2002-03-08T03:00:00+10:00", "2002-03-07T10:00:00-07:00", "PT10H"]
     *   ["2002-03-06T15:00:00-08:00", "2002-03-07T00:00:00+01:00", "-PT8H"]
     * @throws XpathError
     */
    public function testAdjustDateTimeTimezoneTroughStylesheet(
      string $expected, string $dateTime, string $timezone = ''
    ): void {
      Context::setImplicitTimezone(new TimezoneDuration('-PT5H'));
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:value-of select="fn:adjust-dateTime-to-timezone(\''.$dateTime.'\', \''.$timezone.'\')"/>'.
          '</result>',
        'DateTime/TimezoneAdjust'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, $result->documentElement->textContent);
    }
  }
}
