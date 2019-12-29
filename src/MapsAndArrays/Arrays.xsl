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

  <func:function name="array:array-from-nodeset">
    <xsl:param name="input"/>
    <!-- if first child has no ancestor element (document, fragment, ...) use first child -->
    <func:result select="($input|$input/*[count(./ancestor::*) = 0])[local-name() = 'array'][1]"/>
  </func:function>

  <func:function name="array:size">
    <xsl:param name="input"/>
    <func:result select="count(array:array-from-nodeset($input)/*)"/>
  </func:function>

  <func:function name="array:get">
    <xsl:param name="input"/>
    <xsl:param name="position"/>
    <xsl:variable name="array" select="array:array-from-nodeset($input)"/>
    <func:result select="$array/*[$position]"/>
  </func:function>

</xsl:stylesheet>
