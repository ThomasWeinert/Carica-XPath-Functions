<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'strings/values'"/>

  <func:function name="fn:upper-case">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'upperCase', string($input))"/>
  </func:function>

  <func:function name="fn:lower-case">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'lowerCase', string($input))"/>
  </func:function>

</xsl:stylesheet>
