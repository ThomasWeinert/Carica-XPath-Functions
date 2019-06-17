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
                
  <xsl:template match="/root">
    <html lang="en">
      <body>
        <!-- Replace \r\n and \n with <br/> -->
        <xsl:value-of select="fn:replace(value, '\r\n|\n', '&lt;br/>')" disable-output-escaping="yes"/>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
XSLT;

$xml = <<<'XML'
<?xml version="1.0"?>
<root>
  <value><![CDATA[
    Hello
    world!
  ]]></value>
</root>
XML;

$stylesheet = new DOMDocument();
$stylesheet->loadXML($xslt);
$input = new DOMDocument();
$input->loadXML($xml);

$processor = new XSLTProcessor();
$processor->importStylesheet($stylesheet);

echo $processor->transformToXml($input);


