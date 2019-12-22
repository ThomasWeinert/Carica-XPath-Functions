<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'duration/components'"/>

  <func:function name="fn:years-from-duration">
    <xsl:param name="duration"/>
    <func:result select="php:function($CALL, $MODULE, 'yearsFromDuration', string($duration))"/>
  </func:function>

  <func:function name="fn:months-from-duration">
    <xsl:param name="duration"/>
    <func:result select="php:function($CALL, $MODULE, 'monthsFromDuration', string($duration))"/>
  </func:function>

  <func:function name="fn:days-from-duration">
    <xsl:param name="duration"/>
    <func:result select="php:function($CALL, $MODULE, 'daysFromDuration', string($duration))"/>
  </func:function>

</xsl:stylesheet>
