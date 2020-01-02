<?php

namespace Carica\XPathFunctions {

  require_once __DIR__.'/TestCase.php';

  /**
   * @covers \Carica\XpathFunctions\XpathError
   */
  class XpathErrorTest extends TestCase {

    public function testCreateXpathError(): void {
      $error = new XpathError('urn:a#42');
      $this->assertSame('urn:a#42', $error->getURI());
      $this->assertSame('urn:a#42', $error->getMessage());
    }

    public function testCreateXpathErrorWithDescription(): void {
      $error = new XpathError('urn:a#42', 'a message');
      $this->assertSame('urn:a#42', $error->getURI());
      $this->assertSame('urn:a#42, a message', $error->getMessage());
    }

    public function testCreateXpathErrorWithContext(): void {
      $error = new XpathError('urn:a#42', 'a message', $context = new \stdClass());
      $this->assertSame($context, $error->getContext());
    }
  }

}
