<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions {

  abstract class Namespaces {

    public const XMLNS_XS = 'http://www.w3.org/2001/XMLSchema';
    public const XMLNS_FN = 'http://www.w3.org/2005/xpath-functions';
    public const XMLNS_MATH = 'http://www.w3.org/2005/xpath-functions/math';
    public const XMLNS_MAP = 'http://www.w3.org/2005/xpath-functions/map';
    public const XMLNS_ARRAY = 'http://www.w3.org/2005/xpath-functions/array';
    public const XMLNS_ERR = 'http://www.w3.org/2005/xqt-errors';
    public const XMLNS_OUTPUT = 'http://www.w3.org/2010/xslt-xquery-serialization';
  }
}