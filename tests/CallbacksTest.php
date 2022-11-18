<?php

namespace Carica\XPathFunctions {

  require_once __DIR__.'/TestCase.php';

  /**
   * @covers \Carica\XPathFunctions\Callbacks
   */
  class CallbacksTest extends TestCase {

    public function testCall(): void {
      $this->assertSame(
        'TEST',
        Callbacks::handleFunctionCall(
        'Strings/Values',
        'upperCase',
        'test'
        )
      );
    }

  }
}
