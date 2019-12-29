<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:array="http://www.w3.org/2005/xpath-functions/array"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="func array php">

  <xsl:variable name="CARICA_CALLBACK" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="CARICA_MAPS_AND_ARRAYS_ARRAYS" select="'MapsAndArrays/Arrays'"/>

  <func:function name="array:is-array">
    <xsl:param name="array"/>
    <func:result select="($array and local-name($array) = 'array')"/>
  </func:function>

  <func:function name="array:array-size">
    <xsl:param name="array"/>
    <xsl:variable name="result">
      <xsl:choose>
        <xsl:when test="array:is-array($array)">
          <xsl:value-of select="count($array/*)"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>0</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <func:result select="number($result)"/>
  </func:function>

</xsl:stylesheet>
