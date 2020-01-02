# Carica XPath Functions

This is project tries to add [Xpath 2/3 functions](https://www.w3.org/TR/xpath-functions-31) to PHPs XSLTProcessor.
A complete implementation isn't possible - some of the syntax is not available. But let's see how much can be done.

If you have a function that you would like to have added please open an issue.

## How it works:

* Extends the `XSLTProcessor` with `Carica\XpathFunctions\XSLTProcessor`
* Implements a callback for the XSLTProcessor to call specific PHP functions
* Adds a stream wrapper to load XSLT templates that wrap callbacks to PHP as 
  Xpath functions using EXSLT or implement the function directly.  

# Install 

`composer require carica/xpath-functions`

# Usage   
   
1. Define the namespace for the function
2. Import a module into your XSLT
3. Call the Xpath function

Step 2 is the difference to XSLT 2/3. You need to import the module template with the functions you would like to use.

## Compromises

Xpath/XSLT 1.0 does not of the extensive type system of their successors. So most of the functions
return more basic types. Arrays and maps are emulated with XDM nodes, sequences as XDM arrays.

## Examples

### Use String Comparsion

```php
// import extended XSLTProcessor
use Carica\XpathFunctions\XSLTProcessor;

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
use Carica\XpathFunctions\XSLTProcessor;

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
| **DateTime/Components** |
| fn:dateTime() |
| fn:year-from-dateTime() |
| fn:month-from-dateTime() |
| fn:day-from-dateTime() |
| fn:hours-from-dateTime() |
| fn:minutes-from-dateTime() |
| fn:seconds-from-dateTime() |
| fn:timezone-from-dateTime() |
| fn:year-from-date() |
| fn:month-from-date() |
| fn:day-from-date() |
| fn:hours-from-time() |
| fn:minutes-from-time() |
| fn:seconds-from-time() |
| fn:timezone-from-time() |
| **DateTime/TimezoneAdjust** |
| fn:adjust-dateTime-to-timezone() |
| fn:adjust-date-to-timezone() |
| fn:adjust-time-to-timezone() |
| **Duration/Components** |
| fn:years-from-duration() |
| fn:months-from-duration() |
| fn:days-from-duration() |
| fn:hours-from-duration() |
| fn:minutes-from-duration() |
| fn:seconds-from-duration() |
| **Errors** |
| fn:error() | expects URI as first argument (not QName) |
| **MapsAndArrays/Arrays** |
| array:array() | replacement for `[]` syntax, max 10 arguments
| array:size() |
| array:get() |
| array:put() |
| array:append() |
| array:subarray() |
| array:remove() |
| array:insert-before() |
| array:head() |
| array:tail() |
| array:join() | up to 10 array arguments |
| array:reverse() |
| array:flatten() |
| **MapsAndArrays/JSON** |
| fn:parse-json | returns document element from fn:json-to-xml()  |  
| fn:json-doc |   
| fn:json-to-xml | without options | 
| fn:xml-to-json | without options, ignores namespace |
| **MapsAndArrays/Maps** |
| map:map() | replacement for `{}` syntax, max 10 arguments
| map:size() |  
| map:keys() |  
| map:contains() |
| map:get() |
| map:put() |
| map:remove() |
| map:find() |  
| map:entry() |
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
| **Sequences/Cardinality** |
| fn:zero-or-one() |
| fn:one-or-more() |
| fn:exactly-one() |
| **Sequences/External** |
| fn:unparsed-text() | no relative url resolving, basic encoding handling| 
| fn:unparsed-text-lines() | no relative url resolving, basic encoding handling| 
| **Sequences/Parse** |
| fn:parse-xml() |  
| fn:parse-xml-fragment() |
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

