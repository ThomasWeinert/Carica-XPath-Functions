<?php

use Carica\XSLTFunctions\XSLTProcessor;

require __DIR__.'/../../vendor/autoload.php';

$xslt = <<<'XSLT'
<?xml version="1.0"?>
<xsl:stylesheet 
  version="1.0" 
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  exclude-result-prefixes="fn">
  
  <xsl:import href="xpath-functions://Strings/RegExp"/>
                
  <xsl:template match="speak//text()">
    <xsl:for-each select="fn:analyze-string(., '\d+')/*">
      <xsl:choose>
        <xsl:when test="local-name() = 'match'">
          <say-as interpret-as="characters"><xsl:value-of select="."/></say-as>
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="."/>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:template>
  
  <xsl:template match="*">
    <xsl:element name="{name()}">
      <xsl:copy-of select="@*"/>
      <xsl:apply-templates/>
    </xsl:element>
   </xsl:template>
</xsl:stylesheet>
XSLT;

$xml = <<<'XML'
<?xml version="1.0"?>
<speak>The test number is 123456789, and some further block of text.</speak>
XML;

$stylesheet = new DOMDocument();
$stylesheet->loadXML($xslt);
$input = new DOMDocument();
$input->loadXML($xml);

$processor = new XSLTProcessor();
$processor->importStylesheet($stylesheet);

echo $processor->transformToXml($input);


