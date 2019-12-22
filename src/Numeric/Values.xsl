<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'numeric/values'"/>

  <func:function name="fn:round-half-to-even">
    <xsl:param name="input"/>
    <xsl:param name="precision" select="0"/>
    <func:result select="php:function($CALL, $MODULE, 'roundHalfToEven', number($input), number($precision))"/>
  </func:function>

</xsl:stylesheet>