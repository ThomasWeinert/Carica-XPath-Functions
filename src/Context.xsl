<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'context'"/>

  <func:function name="fn:current-dateTime">
    <func:result select="php:function($CALL, $MODULE, 'currentDateTime')"/>
  </func:function>

  <func:function name="fn:current-date">
    <func:result select="php:function($CALL, $MODULE, 'currentDate')"/>
  </func:function>

  <func:function name="fn:current-time">
    <func:result select="php:function($CALL, $MODULE, 'currentTime')"/>
  </func:function>

  <func:function name="fn:implicit-timezone">
    <func:result select="php:function($CALL, $MODULE, 'implicitTimeZone')"/>
  </func:function>

  <func:function name="fn:default-collation">
    <func:result select="php:function($CALL, $MODULE, 'defaultCollation')"/>
  </func:function>

  <func:function name="fn:default-language">
    <func:result select="php:function($CALL, $MODULE, 'defaultLanguage')"/>
  </func:function>

</xsl:stylesheet>
