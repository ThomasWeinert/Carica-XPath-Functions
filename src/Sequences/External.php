<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Sequences {

  use Carica\XSLTFunctions\XpathError;

  abstract class External {

    public static function doc(string $href): \DOMDocument {
      $document = new \DOMDocument();
      $document->load($href);
      return $document;
    }

    public static function unparsedText(string $href, string $encoding = 'utf-8'): string {
      try {
        $data = file_get_contents($href);
      } catch (\Throwable $e) {
        throw new XpathError('err:FOUT1170', 'Invalid $href argument to fn:unparsed-text(): '.$href);
      }
      if ($encoding && $encoding !== 'utf-8') {
        $converter = new \UConverter(\UConverter::UTF8, $encoding);
        return $converter->convert($data, FALSE);
      }
      return $data;
    }
  }
}
