<?php

namespace Carica\XSLTFunctions\Numeric {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XSLTFunctions\TestCase;
  use Carica\XSLTFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XSLTFunctions\MapsAndArrays\JSON
   */
  class JSONTest extends TestCase {

    public function testXMLtoJSONTroughStylesheet(): void {
      $input = htmlspecialchars(
        '{
          "_id":"53e3c6ed-9bfc-2730-e053-0100007f6afb",
          "content":{
            "name":"object one",
            "type":1,
            "isNew":true,
            "clientId":null,
            "values":[
              {"name":"x", "v":1},
              {"name":"y", "v":2}
            ]
          }
        }'
      );
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:copy-of select="fn:xml-to-json(\''.$input.'\')"/>'.
          '</result>',
        'MapsAndArrays/JSON'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<?xml version="1.0"?>
        <result>
          <map xmlns="http://www.w3.org/2005/xpath-functions">
            <string key="_id">53e3c6ed-9bfc-2730-e053-0100007f6afb</string>
            <map key="content">
              <string key="name">object one</string>
              <number key="type">1</number>
              <boolean key="isNew">true</boolean>
              <null key="clientId"/>
              <array key="values">
                <map>
                  <string key="name">x</string>
                  <number key="v">1</number>
                </map>
                <map>
                  <string key="name">y</string>
                  <number key="v">2</number>
                </map>
              </array>
            </map>
          </map>
        </result>',
        $result->saveXML()
      );
    }

    public function testJsonDocTroughStylesheet(): void {
      $input = htmlspecialchars(__DIR__.'/TestData/example.json');
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="http://www.w3.org/1999/XSL/Transform">'.
          '<xsl:copy-of select="fn:json-doc(\''.$input.'\')//*[@key=\'phoneNumbers\']"/>'.
          '</result>',
        'MapsAndArrays/JSON'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertXmlStringEqualsXmlString(
        '<?xml version="1.0"?>
        <result>
          <array xmlns="http://www.w3.org/2005/xpath-functions" key="phoneNumbers">
            <map>
              <string key="type">home</string>
              <string key="number">212 555-1234</string>
            </map>
            <map>
              <string key="type">office</string>
              <string key="number">646 555-4567</string>
            </map>
            <map>
              <string key="type">mobile</string>
              <string key="number">123 456-7890</string>
            </map>
          </array>
        </result>',
        $result->saveXML()
      );
    }
  }
}
