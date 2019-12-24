<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'datetime/components'"/>

  <func:function name="fn:dateTime">
    <xsl:param name="date"/>
    <xsl:param name="time"/>
    <func:result select="php:function($CALL, $MODULE, 'dateTime', string($date), string($time))"/>
  </func:function>

  <func:function name="fn:year-from-dateTime">
    <xsl:param name="dateTime"/>
    <func:result select="php:function($CALL, $MODULE, 'yearFromDateTime', string($dateTime))"/>
  </func:function>

  <func:function name="fn:month-from-dateTime">
    <xsl:param name="dateTime"/>
    <func:result select="php:function($CALL, $MODULE, 'monthFromDateTime', string($dateTime))"/>
  </func:function>

  <func:function name="fn:day-from-dateTime">
    <xsl:param name="dateTime"/>
    <func:result select="php:function($CALL, $MODULE, 'dayFromDateTime', string($dateTime))"/>
  </func:function>

  <func:function name="fn:hours-from-dateTime">
    <xsl:param name="dateTime"/>
    <func:result select="php:function($CALL, $MODULE, 'hoursFromDateTime', string($dateTime))"/>
  </func:function>

  <func:function name="fn:minutes-from-dateTime">
    <xsl:param name="dateTime"/>
    <func:result select="php:function($CALL, $MODULE, 'minutesFromDateTime', string($dateTime))"/>
  </func:function>

  <func:function name="fn:seconds-from-dateTime">
    <xsl:param name="dateTime"/>
    <func:result select="php:function($CALL, $MODULE, 'secondsFromDateTime', string($dateTime))"/>
  </func:function>

  <func:function name="fn:timezone-from-dateTime">
    <xsl:param name="dateTime"/>
    <func:result select="php:function($CALL, $MODULE, 'timezoneFromDateTime', string($dateTime))"/>
  </func:function>

  <func:function name="fn:year-from-date">
    <xsl:param name="date"/>
    <func:result select="php:function($CALL, $MODULE, 'yearFromDate', string($date))"/>
  </func:function>

  <func:function name="fn:month-from-date">
    <xsl:param name="date"/>
    <func:result select="php:function($CALL, $MODULE, 'monthFromDate', string($date))"/>
  </func:function>

  <func:function name="fn:day-from-date">
    <xsl:param name="date"/>
    <func:result select="php:function($CALL, $MODULE, 'dayFromDate', string($date))"/>
  </func:function>

</xsl:stylesheet>
