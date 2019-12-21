<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'numeric/formatting'"/>

  <func:function name="fn:format-integer">
    <xsl:param name="input"/>
    <xsl:param name="picture" select="''"/>
    <xsl:param name="language" select="''"/>
    <func:result select="php:function($CALL, $MODULE, 'formatInteger', number($input), string($picture), string($language))"/>
  </func:function>

</xsl:stylesheet>
