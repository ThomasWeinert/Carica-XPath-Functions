<?php

namespace Carica\XSLTFunctions {

  class XpathError extends \Exception {

    private $_identifier;
    private $_context;

    public function __construct(string $identifier, string $message = '', $context = NULL) {
      parent::__construct($identifier.', '.$message);
      $this->_identifier = $identifier;
      $this->_context = $context;
    }

    public function getIdentifier(): string {
      return $this->_identifier;
    }

    public function getContext() {
      return $this->_context;
    }
  }
}
