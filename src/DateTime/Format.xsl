<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CARICA_CALLBACK" select="'Carica\XPathFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="CARICA_DATETIME_FORMAT" select="'DateTime/Format'"/>

  <func:function name="fn:format-date">
    <xsl:param name="date"/>
    <xsl:param name="picture" select="''"/>
    <xsl:param name="language" select="''"/>
    <xsl:param name="calendar" select="''"/>
    <xsl:param name="place" select="''"/>
    <func:result
      select="php:function($CARICA_CALLBACK, $CARICA_DATETIME_FORMAT, 'formatDate', string($dateTime), string($timezone))"/>
  </func:function>

  <func:function name="fn:format-dateTime">
    <xsl:param name="date"/>
    <xsl:param name="picture" select="''"/>
    <xsl:param name="language" select="''"/>
    <xsl:param name="calendar" select="''"/>
    <xsl:param name="place" select="''"/>
    <func:result
      select="php:function($CARICA_CALLBACK, $CARICA_DATETIME_FORMAT, 'formatDateTime', string($dateTime), string($timezone))"/>
  </func:function>

</xsl:stylesheet>
