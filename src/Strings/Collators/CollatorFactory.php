<?php
declare(strict_types=1);

namespace Carica\XSLTFunctions\Strings\Collators {

  use Carica\XSLTFunctions\Strings\XpathCollator;

  abstract class CollatorFactory {

    private static $_defaultCollation = UnicodeCodepointCollator::URI;

    private static $_collationURIs = [
      UnicodeCodepointCollator::URI => UnicodeCodepointCollator::class,
      CaseInsensitiveASCIICollator::URI => CaseInsensitiveASCIICollator::class,
      ParameterizedCollator::URI => ParameterizedCollator::class,
    ];

    public static function createFromURI(string $uri): XpathCollator {
      if ('' === $uri) {
        $uri = self::getDefaultCollation();
      }
      $collatorClass = self::getCollatorClass($uri);
      if (
        class_exists($collatorClass) &&
        ($collator = new $collatorClass($uri)) &&
        $collator instanceof XpathCollator
      ) {
        return $collator;
      }
      throw new \InvalidArgumentException('Unknown collation URI: '.$uri);
    }

    public static function setDefaultCollation(string $uri): void {
      if (NULL === self::getCollatorClass($uri)) {
        throw new \InvalidArgumentException('Can not set default collation to unknown URI: '.$uri);
      }
      self::$_defaultCollation = $uri;
    }

    public static function getDefaultCollation(): string {
      return self::$_defaultCollation;
    }

    /**
     * @param string $uri
     * @return NULL|string
     */
    private static function getCollatorClass(string $uri): ?string {
      $collatorClass = NULL;
      if (isset(self::$_collationURIs[$uri])) {
        $collatorClass = self::$_collationURIs[$uri];
      } else {
        $baseURI = preg_replace('([?#].*$)s', '', $uri);
        if (isset(self::$_collationURIs[$baseURI])) {
          $collatorClass = self::$_collationURIs[$baseURI];
        }
      }
      return $collatorClass;
    }
  }
}
