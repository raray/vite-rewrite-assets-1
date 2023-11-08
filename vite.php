<?php
  define('VITE_DEV_HOST', 'http://localhost:5174');
  define('ASSET_DIR_SRC', '');
  define('ASSET_PATH_DIST', __DIR__ . '/asset-dist');

  function assetUrl(string $entry): string {
      $manifest = getManifest();

      return isset($manifest[$entry]) ? '/' . $manifest[$entry]['file'] : '';
  }

  function cssTag(string $entry): string {
    // not needed on dev, it's inject by Vite
    if (isDev($entry)) {
      return '';
    }

    $tags = '';

    foreach (cssUrls($entry) as $url) {
      $tags .= '<link rel="stylesheet" href="' . $url . '">';
    }

    return $tags;
  }

  function cssUrls(string $entry): array {
      $manifest = getManifest();
      $urls = [];

      if (!empty($manifest[$entry]['css'])) {
        foreach ($manifest[$entry]['css'] as $file) {
          $urls[] = 'asset-dist/' . $file;
        }
      }

      return $urls;
  }

  // Helpers to locate files
  function getManifest(): array {
      $content = file_get_contents(ASSET_PATH_DIST . '/manifest.json');

      return json_decode($content, true);
  }

  function importsUrls(string $entry): array {
    $manifest = getManifest();
    $urls = [];

    if (!empty($manifest[$entry]['imports'])) {
      foreach ($manifest[$entry]['imports'] as $imports) {
        $urls[] = '/' . $manifest[$imports]['file'];
      }
    }

    return $urls;
  }

  // Some dev/prod mechanism would exist in your project
  function isDev(string $entry): bool {
    static $exists = null;

    if ($exists !== null) {
      return $exists;
    }

    $handle = curl_init(VITE_DEV_HOST . '/' . $entry);

    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($handle, CURLOPT_NOBODY, true);

    curl_exec($handle);

    $error = curl_errno($handle);

    curl_close($handle);

    return $exists = !$error;
  }

  function jsPreloadImports(string $entry): string {
    if (isDev($entry)) {
      return '';
    }

    $res = '';

    foreach (importsUrls($entry) as $url) {
      $res .= '<link rel="modulepreload" href="' . $url . '">';
    }

    return $res;
  }

  // Helpers to print tags
  function jsTag(string $entry): string {
    $url = isDev($entry) ? VITE_DEV_HOST . '/' . $entry : assetUrl(ASSET_DIR_SRC . '/' . $entry);

    if (!$url) {
      return '';
    }

    return '<script type="module" crossorigin src="' . $url . '"></script>';
  }

  // Prints all the html entries needed for Vite
  function vite(string $entry): string {
    return "\n" . jsTag($entry) . "\n" . jsPreloadImports($entry) . "\n" . cssTag($entry);
  }
