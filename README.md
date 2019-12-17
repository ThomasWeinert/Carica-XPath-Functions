# XSLT Functions

This is project tries to add Xpath/XSLT 2.0 function to PHPs XSLTProcessor.

How it works:

1. Extend the `XSLTProcessor` as `Carica\XSLTFunctions\XSLTProcessor`
2. Implements a callback for the XSLTProcessor to call specific PHP functions
3. Adds a stream wrapper to load XSLT templates that wrap callbacks as 
   Xpath functions using EXSLT.
   
```php
// import extended XSLTProcessor
use Carica\XSLTFunctions\XSLTProcessor;

$xslt = <<<'XSLT'
<?xml version="1.0"?>
<xsl:stylesheet 
  version="1.0" 
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  exclude-result-prefixes="fn">
  
  <!-- import RegExp functions -->
  <xsl:import href="xpath-functions://Strings/RegExp"/>
                
  <xsl:template match="speak//text()">
    <!-- use RegExp function -->
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
```

Output:

```XML
<?xml version="1.0"?>
<speak>The test number is <say-as interpret-as="characters">123456789</say-as>, and some further block of text.</speak>
```

