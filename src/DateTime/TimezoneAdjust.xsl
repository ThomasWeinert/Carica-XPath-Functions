<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'datetime/timezoneadjust'"/>

  <func:function name="fn:adjust-dateTime-to-timezone">
    <xsl:param name="dateTime"/>
    <xsl:param name="timezone" select="''"/>
    <func:result select="php:function($CALL, $MODULE, 'adjustDateTimeToTimezone', string($dateTime), string($timezone))"/>
  </func:function>

  <func:function name="fn:adjust-date-to-timezone">
    <xsl:param name="date"/>
    <xsl:param name="timezone" select="''"/>
    <func:result select="php:function($CALL, $MODULE, 'adjustDateToTimezone', string($date), string($timezone))"/>
  </func:function>

  <func:function name="fn:adjust-time-to-timezone">
    <xsl:param name="time"/>
    <xsl:param name="timezone" select="''"/>
    <func:result select="php:function($CALL, $MODULE, 'adjustTimeToTimezone', string($time), string($timezone))"/>
  </func:function>

</xsl:stylesheet>
