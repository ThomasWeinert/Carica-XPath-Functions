<?php

namespace Carica\XpathFunctions\Numeric {

  require_once __DIR__.'/../TestCase.php';

  use Carica\XpathFunctions\Namespaces;
  use Carica\XpathFunctions\TestCase;
  use Carica\XpathFunctions\XSLTProcessor;

  /**
   * @covers \Carica\XpathFunctions\Numeric\Math
   */
  class MathTest extends TestCase {

    public const XMLNS_MATH = Namespaces::XMLNS_MATH;

    public function testPITroughStylesheet(): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.self::XMLNS_XSL.'" xmlns:math="'.self::XMLNS_MATH.'">'.
          '<xsl:value-of select="math:pi()"/>'.
          '</result>',
        'Numeric/Math'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame(M_PI, (float)$result->documentElement->textContent);
    }

    /**
     * @param float $expected
     * @param float $input
     * @testWith
     *   [1.0e0, 0]
     *   [2.7182818284590455e0, 1]
     *   [7.38905609893065e0, 2]
     *   [0.36787944117144233e0, -1]
     */
    public function testExpTroughStylesheet(float $expected, float $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.self::XMLNS_XSL.'" xmlns:math="'.self::XMLNS_MATH.'">'.
          '<xsl:value-of select="math:exp('.$input.')"/>'.
          '</result>',
        'Numeric/Math'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }

    /**
     * @param float $expected
     * @param float $input
     * @testWith
     *   [1.0e0, 0]
     *   [1.0e1, 1]
     *   [3.1622776601683795e0, 0.5]
     *   [1.0e-1, -1]
     */
    public function testExp10TroughStylesheet(float $expected, float $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.self::XMLNS_XSL.'" xmlns:math="'.self::XMLNS_MATH.'">'.
          '<xsl:value-of select="math:exp10('.$input.')"/>'.
          '</result>',
        'Numeric/Math'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }

    /**
     * @param float $expected
     * @param float $input
     * @testWith
     *   [-6.907755278982137e0, 1.0e-3]
     *   [0.6931471805599453e0, 2]
     */
    public function testLogTroughStylesheet(float $expected, float $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.self::XMLNS_XSL.'" xmlns:math="'.self::XMLNS_MATH.'">'.
          '<xsl:value-of select="math:log('.$input.')"/>'.
          '</result>',
        'Numeric/Math'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }

    /**
     * @param float $expected
     * @param float $input
     * @testWith
     *   [3.0e0, 1.0e3]
     *   [-3.0e0, 1.0e-3]
     *   [0.3010299956639812e0, 2]
     */
    public function testLog10TroughStylesheet(float $expected, float $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.self::XMLNS_XSL.'" xmlns:math="'.self::XMLNS_MATH.'">'.
          '<xsl:value-of select="math:log10('.$input.')"/>'.
          '</result>',
        'Numeric/Math'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }

    /**
     * @param float $expected
     * @param float $base
     * @param float $exponent
     * @testWith
     *   [8.0e0, 2, 3]
     *   [-8.0e0, -2, 3]
     *   [0.125e0, 2, -3]
     *   [-0.125e0, -2, -3]
     */
    public function testPowTroughStylesheet(float $expected, float $base, float $exponent): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.self::XMLNS_XSL.'" xmlns:math="'.self::XMLNS_MATH.'">'.
          '<xsl:value-of select="math:pow('.$base.', '.$exponent.')"/>'.
          '</result>',
        'Numeric/Math'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }

    /**
     * @param float $expected
     * @param float $input
     * @testWith
     *   [0.0e0, 0.0e0]
     *   [-0.0e0, -0.0e0]
     *   [1.0e3, 1.0e6]
     *   [1.4142135623730951e0, 2.0e0]
     */
    public function testSqrtTroughStylesheet(float $expected, float $input): void {
      $stylesheet = $this->prepareStylesheetDocument(
        '<result xmlns:xsl="'.self::XMLNS_XSL.'" xmlns:math="'.self::XMLNS_MATH.'">'.
          '<xsl:value-of select="math:sqrt('.$input.')"/>'.
          '</result>',
        'Numeric/Math'
      );

      $processor = new XSLTProcessor();
      $processor->importStylesheet($stylesheet);
      $result = $processor->transformToDoc($this->prepareInputDocument());

      $this->assertSame($expected, (float)$result->documentElement->textContent);
    }
  }
}
