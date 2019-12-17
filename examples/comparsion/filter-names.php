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
  
  <xsl:import href="xpath-functions://Strings/Comparsion"/>
  <xsl:output indent="yes" method="xml"/>
                
  <xsl:template match="/names">
    <html lang="en">
      <body>
        <div>
          <header>Exactly</header>
          <xsl:for-each select="name[fn:compare(., 'André') = 0]">
            <span><xsl:value-of select="."/></span>
          </xsl:for-each>
        </div>
        <div>
          <header>Case Insensitive, Ignore Accents</header>
          <xsl:variable name="collation">http://www.w3.org/2013/collation/UCA?strength=primary</xsl:variable>
          <xsl:for-each select="name[fn:compare(., 'andre', $collation) = 0]">
            <span><xsl:value-of select="."/></span>
          </xsl:for-each>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
XSLT;

$xml = <<<'XML'
<?xml version="1.0"?>
<names>
  <name>Andreas</name>
  <name>Andre</name>
  <name>André</name>
  <name>Andrè</name>
</names>
XML;

$stylesheet = new DOMDocument();
$stylesheet->loadXML($xslt);
$input = new DOMDocument();
$input->loadXML($xml);

$processor = new XSLTProcessor();
$processor->importStylesheet($stylesheet);

echo $processor->transformToXml($input);


