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

</xsl:stylesheet>
