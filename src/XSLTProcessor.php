<?php
declare(strict_types=1);

namespace Carica\XPathFunctions {

  class XSLTProcessor extends \XSLTProcessor {

    public function __construct() {
      self::attachXPathFunctions($this);
    }

    private static function attachXPathFunctions(\XSLTProcessor $processor): void {
      $processor->registerPHPFunctions([Callbacks::class.'::handleFunctionCall']);
      ModuleLoader::register('xpath-functions', __DIR__);
    }

    public function registerPHPFunctions($restrict = NULL): void {
      if (NULL === $restrict) {
        throw new \LogicException('Please restrict the PHP functions that XSLT can call.');
      }
      $restrict[] = Callbacks::class.'::handleFunctionCall';
      parent::registerPHPFunctions($restrict);
    }
  }
}
