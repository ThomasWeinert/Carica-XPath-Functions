<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:php="http://php.net/xsl"
  xmlns:math="http://www.w3.org/2005/xpath-functions/math"
  extension-element-prefixes="func php">

  <xsl:variable name="CALL" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="MODULE" select="'numeric/math'"/>

  <func:function name="math:pi">
    <func:result select="php:function($CALL, $MODULE, 'pi')"/>
  </func:function>

  <func:function name="math:exp">
    <xsl:param name="argument"/>
    <func:result select="php:function($CALL, $MODULE, 'exp', number($argument))"/>
  </func:function>

  <func:function name="math:exp10">
    <xsl:param name="argument"/>
    <func:result select="php:function($CALL, $MODULE, 'exp10', number($argument))"/>
  </func:function>

  <func:function name="math:log">
    <xsl:param name="argument"/>
    <func:result select="php:function($CALL, $MODULE, 'log', number($argument))"/>
  </func:function>

  <func:function name="math:log10">
    <xsl:param name="argument"/>
    <func:result select="php:function($CALL, $MODULE, 'log10', number($argument))"/>
  </func:function>

  <func:function name="math:pow">
    <xsl:param name="base"/>
    <xsl:param name="exponent"/>
    <func:result select="php:function($CALL, $MODULE, 'pow', number($base), number($exponent))"/>
  </func:function>

  <func:function name="math:sqrt">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'sqrt', number($input))"/>
  </func:function>

  <func:function name="math:sin">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'sin', number($input))"/>
  </func:function>

  <func:function name="math:cos">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'cos', number($input))"/>
  </func:function>

  <func:function name="math:tan">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'tan', number($input))"/>
  </func:function>

  <func:function name="math:asin">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'asin', number($input))"/>
  </func:function>

  <func:function name="math:acos">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'acos', number($input))"/>
  </func:function>

  <func:function name="math:atan">
    <xsl:param name="input"/>
    <func:result select="php:function($CALL, $MODULE, 'atan', number($input))"/>
  </func:function>

  <func:function name="math:atan2">
    <xsl:param name="y"/>
    <xsl:param name="x"/>
    <func:result select="php:function($CALL, $MODULE, 'atan2', number($y), number($x))"/>
  </func:function>

</xsl:stylesheet>
