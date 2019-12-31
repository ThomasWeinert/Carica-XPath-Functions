<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:exsl="http://exslt.org/common"
  xmlns:array="http://www.w3.org/2005/xpath-functions/array"
  xmlns:map="http://www.w3.org/2005/xpath-functions/map"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="exsl func array map php">

  <xsl:variable name="CARICA_CALLBACK" select="'Carica\XSLTFunctions\XSLTProcessor::handleFunctionCall'"/>
  <xsl:variable name="CARICA_MAPS_AND_ARRAYS_MAPS" select="'MapsAndArrays/Maps'"/>

  <func:function name="map:map-from-nodeset">
    <xsl:param name="input"/>
    <!-- if first child has no ancestor element (document, fragment, ...) use first child -->
    <func:result select="($input|$input/*[count(./ancestor::*) = 0])[local-name() = 'map'][1]"/>
  </func:function>

  <func:function name="map:size">
    <xsl:param name="input"/>
    <func:result select="count(map:map-from-nodeset($input)/*)"/>
  </func:function>

  <func:function name="map:keys">
    <xsl:param name="input"/>
    <xsl:variable name="map" select="map:map-from-nodeset($input)"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:for-each select="$map/*[@key]/@key">
          <string><xsl:value-of select="."/></string>
        </xsl:for-each>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <func:function name="map:contains">
    <xsl:param name="input"/>
    <xsl:param name="key"/>
    <func:result select="count(map:map-from-nodeset($input)/*[@key = string($key)]) &gt; 0"/>
  </func:function>

</xsl:stylesheet>
