<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'mapsandarrays/json'"/>

  <func:function name="fn:parse-json">
    <xsl:param name="data"/>
    <func:result select="php:function($CALL, $MODULE, 'parseJSON', string($data))"/>
  </func:function>

</xsl:stylesheet>
