<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:func="http://exslt.org/functions"
  xmlns:exsl="http://exslt.org/common"
  xmlns:array="http://www.w3.org/2005/xpath-functions/array"
  xmlns:php="http://php.net/xsl"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="exsl func array php">

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

  <func:function name="array:put">
    <xsl:param name="input"/>
    <xsl:param name="position"/>
    <xsl:param name="member"/>
    <xsl:variable name="array" select="array:array-from-nodeset($input)"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:for-each select="$array/*">
          <xsl:choose>
            <xsl:when test="position() = $position">
              <xsl:call-template name="carica-output-item-as-xdm-array-element">
                <xsl:with-param name="item" select="$member"/>
              </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
              <xsl:copy-of select="."/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:for-each>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <func:function name="array:append">
    <xsl:param name="input"/>
    <xsl:param name="member"/>
    <xsl:variable name="array" select="array:array-from-nodeset($input)"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:copy-of select="$array/*"/>
        <xsl:call-template name="carica-output-item-as-xdm-array-element">
          <xsl:with-param name="item" select="$member"/>
        </xsl:call-template>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <func:function name="array:subarray">
    <xsl:param name="input"/>
    <xsl:param name="position"/>
    <xsl:param name="length" select="0"/>
    <xsl:variable name="array" select="array:array-from-nodeset($input)"/>
    <xsl:variable name="end" select="count($array/*) - $position + $length"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:for-each select="$array/*">
          <xsl:if test="position() &gt;= $position and ($length &lt; 1 or position() &lt;= $end)">
            <xsl:copy-of select="."/>
          </xsl:if>
        </xsl:for-each>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <func:function name="array:remove">
    <xsl:param name="input"/>
    <xsl:param name="position"/>
    <xsl:variable name="array" select="array:array-from-nodeset($input)"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:for-each select="$array/*">
          <xsl:if test="position() != $position">
            <xsl:copy-of select="."/>
          </xsl:if>
        </xsl:for-each>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <func:function name="array:insert-before">
    <xsl:param name="input"/>
    <xsl:param name="position"/>
    <xsl:param name="member"/>
    <xsl:variable name="array" select="array:array-from-nodeset($input)"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:for-each select="$array/*">
          <xsl:if test="position() = $position">
            <xsl:call-template name="carica-output-item-as-xdm-array-element">
              <xsl:with-param name="item" select="$member"/>
            </xsl:call-template>
          </xsl:if>
          <xsl:copy-of select="."/>
        </xsl:for-each>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <func:function name="array:head">
    <xsl:param name="input"/>
    <func:result select="array:get($input, 1)"/>
  </func:function>

  <func:function name="array:tail">
    <xsl:param name="input"/>
    <func:result select="array:subarray($input, 2)"/>
  </func:function>

  <func:function name="array:reverse">
    <xsl:param name="input"/>
    <xsl:variable name="array" select="array:array-from-nodeset($input)"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:for-each select="$array/*">
          <xsl:sort select="position()" data-type="number" order="descending"/>
          <xsl:copy-of select="."/>
        </xsl:for-each>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <func:function name="array:join">
    <xsl:param name="a1"/>
    <xsl:param name="a2"/>
    <xsl:param name="a3" select="false()"/>
    <xsl:param name="a4" select="false()"/>
    <xsl:param name="a5" select="false()"/>
    <xsl:param name="a6" select="false()"/>
    <xsl:param name="a7" select="false()"/>
    <xsl:param name="a8" select="false()"/>
    <xsl:param name="a9" select="false()"/>
    <xsl:param name="a10" select="false()"/>
    <xsl:variable name="current" select="array:array-from-nodeset($a1)"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:for-each select="$current/*">
          <xsl:copy-of select="."/>
        </xsl:for-each>
        <xsl:if test="$a2">
          <xsl:for-each select="array:join($a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9, $a10)/*">
            <xsl:copy-of select="."/>
          </xsl:for-each>
        </xsl:if>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <func:function name="array:flatten">
    <xsl:param name="input"/>
    <xsl:variable name="array" select="array:array-from-nodeset($input)"/>
    <xsl:variable name="result">
      <array xmlns="http://www.w3.org/2005/xpath-functions">
        <xsl:for-each select="$array/*">
          <xsl:choose>
            <xsl:when test="local-name()='array'">
              <xsl:for-each select="array:flatten(.)/*">
                <xsl:copy-of select="."/>
              </xsl:for-each>
            </xsl:when>
            <xsl:otherwise>
              <xsl:copy-of select="."/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:for-each>
      </array>
    </xsl:variable>
    <func:result select="exsl:node-set($result)/fn:array"/>
  </func:function>

  <xsl:template name="carica-output-item-as-xdm-array-element">
    <xsl:param name="item"/>
    <xsl:variable name="type" select="exsl:object-type($item)"/>
    <xsl:choose xmlns="http://www.w3.org/2005/xpath-functions">
      <xsl:when test="$type = 'RTF'">
        <xsl:call-template name="carica-output-item-as-xdm-array-element">
          <xsl:with-param name="item" select="exsl:node-set($item)"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:when test="$type = 'number'">
        <number>
          <xsl:value-of select="$item"/>
        </number>
      </xsl:when>
      <xsl:when test="$type = 'boolean'">
        <boolean>
          <xsl:value-of select="$item"/>
        </boolean>
      </xsl:when>
      <xsl:when test="$type = 'null'">
        <null/>
      </xsl:when>
      <xsl:when test="$type = 'node-set' and contains('array map string number boolean null', local-name($item))">
        <xsl:copy-of select="$item"/>
      </xsl:when>
      <xsl:otherwise>
        <string>
          <xsl:value-of select="$item"/>
        </string>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

</xsl:stylesheet>
