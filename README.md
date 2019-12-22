# XSLT Functions

This is project tries to add [Xpath 2.0 functions](https://www.w3.org/TR/xpath-functions-31) to PHPs XSLTProcessor.
It does not aim for a complete implementation. Just for some useful features.

If you have a function that you would like to have added please open an issue.

How it works:

1. Extend the `XSLTProcessor` with `Carica\XSLTFunctions\XSLTProcessor`
2. Implements a callback for the XSLTProcessor to call specific PHP functions
3. Adds a stream wrapper to load XSLT templates that wrap callbacks as 
   Xpath functions using EXSLT.

# Usage   
   
1. Import a module (EXSLT template) into your XSLT
2. Call the Xpath function

The goal is to allow the same function calls as in Xpath/XSLT 2.0.  

## Implemented Functions

| Function | Notes |
| :------- | ----- |
| **Context** |
| fn:current-dateTime() | 
| fn:current-date() | 
| fn:current-time() | 
| fn:implicit-timezone() | 
| fn:default-collation() | 
| fn:default-language() | 
| **Numeric/Formatting** |
| fn:format-integer() | partially | 
| **Numeric/Math** |
| math:pi() |
| math:exp() |
| math:exp10() |
| math:log() |
| math:log10() |
| math:pow() |
| math:sqrt() |
| math:sin() |
| math:cos() |
| math:tan() |
| math:asin() |
| math:acos() |
| math:atan() |
| math:atan2() |
| **Numeric/Values** |
| fn:round-half-to-even() |
| **Strings/Comparsion** |
| fn:compare() |  
| fn:codepoint-equal() |  
| fn:collation-key() |  
| fn:contains-token() | 
| **Strings/Values** |
| fn:upper-case() |  
| fn:lower-case() |  
| **Strings/RegExp** |
| fn:matches() |  
| fn:replace() |  
| fn:tokenize() |  
| fn:analyze-string() |  
| **Duration/Components** |
| fn:years-from-duration() |
| fn:months-from-duration() |

## Examples

### Use String Comparsion

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
  <!-- ^- define the function namespace -->
  
  <!-- import the string comparsion module -->
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
```

Output:

```XML
<?xml version="1.0"?>
<html lang="en">
  <body>
    <div>
      <header>Exactly</header>
      <span>André</span>
    </div>
    <div>
      <header>Case Insensitive, Ignore Accents</header>
      <span>Andre</span>
      <span>André</span>
      <span>Andrè</span>
    </div>
  </body>
</html>
```

### Wrap Parts Of Text Nodes Using RegExp 
   
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

