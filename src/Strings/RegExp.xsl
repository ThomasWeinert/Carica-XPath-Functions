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
  <xsl:variable name="MODULE" select="'strings/regexp'"/>

  <func:function name="fn:matches">
    <xsl:param name="input"/>
    <xsl:param name="pattern"/>
    <xsl:param name="flags" select="''"/>
    <func:result select="php:function($CALL, $MODULE, 'matches', string($input), string($pattern), string($flags))"/>
  </func:function>

  <func:function name="fn:replace">
    <xsl:param name="input"/>
    <xsl:param name="pattern"/>
    <xsl:param name="replacement"/>
    <xsl:param name="flags" select="''"/>
    <func:result
      select="php:function($CALL, $MODULE, 'replace', string($input), string($pattern), string($replacement), string($flags))"/>
  </func:function>

  <func:function name="fn:tokenize">
    <xsl:param name="input"/>
    <xsl:param name="pattern" select="false()"/>
    <xsl:param name="flags" select="''"/>
    <func:result select="php:function($CALL, $MODULE, 'tokenize', string($input), $pattern, string($flags))/*"/>
  </func:function>

  <func:function name="fn:analyze-string">
    <xsl:param name="input"/>
    <xsl:param name="pattern" select="false()"/>
    <xsl:param name="flags" select="''"/>
    <func:result select="php:function($CALL, $MODULE, 'analyzeString', string($input), $pattern, string($flags))"/>
  </func:function>

</xsl:stylesheet>
