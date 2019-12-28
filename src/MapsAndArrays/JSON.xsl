<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:exsl="http://exslt.org/common"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func exsl php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'mapsandarrays/json'"/>

  <func:function name="fn:parse-json">
    <xsl:param name="data"/>
    <!-- alias for fn:xml-to-json() -->
    <func:result select="php:function($CALL, $MODULE, 'xmlToJSON', string($data))"/>
  </func:function>

  <func:function name="fn:json-doc">
    <xsl:param name="href"/>
    <func:result select="php:function($CALL, $MODULE, 'jsonDoc', string($href))"/>
  </func:function>

  <func:function name="fn:json-to-xml">
    <xsl:param name="data"/>
    <func:result select="php:function($CALL, $MODULE, 'jsonToXML', string($data))"/>
  </func:function>

  <func:function name="fn:xml-to-json">
    <xsl:param name="node"/>
    <func:result select="php:function($CALL, $MODULE, 'xmlToJSON', exsl:node-set($node))"/>
  </func:function>

</xsl:stylesheet>
