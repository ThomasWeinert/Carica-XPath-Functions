<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'strings/comparsion'"/>

  <func:function name="fn:compare">
    <xsl:param name="a"/>
    <xsl:param name="b"/>
    <xsl:param name="collation" select="''"/>
    <func:result select="php:function($CALL, $MODULE, 'compare', string($a), string($b), string($collation))"/>
  </func:function>

</xsl:stylesheet>
