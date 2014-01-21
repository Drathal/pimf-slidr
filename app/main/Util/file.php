<?php

/**
 * some methods for file operations
 * Class file
 */
class file
{
  
  public static $chmodValue = 0755;

  /**
   * filter
   *
   * sample:
   * $filters = array(
   *    DS . '**' . DS . '*~',
   *    DS . '**' . DS . '*.bak',
   *    DS . '**' . DS . '*.orig',
   *    DS . '**' . DS . '*.swp',
   *    DS . '**' . DS . 'ignore_*.*',
   *    DS . '**' . DS . '.DS_Store',
   *    DS . '**' . DS . '.hidden',
   *    DS . '**' . DS . '%layout%' . DS . 'templates' . DS . 'qt_matrix_bars.tpl',
   *  );
   *
   *
   *
   * @param string $path Path
   * @param array $filters Filter patterns
   * @return boolean True if path matched by a filter pattern
   */
  public static function isFiltered($path, array $filters)
  {
    foreach ($filters as $pattern) {
      if (fnmatch($pattern, $path)) {
        return TRUE;
      }
    }
    return FALSE;
  }


  /**
   *
   *
   * sample:
   *
   * file::copyRecursive(
   *  $source,
   *  $target,
   *  array_map(
   *    function ($pattern) {
   *      return str_replace('%layout%', '_base', $pattern);
   *    },
   *    $filters
   *  )
   * );
   *
   * file::copyRecursive(
   *  $source,
   *  $target,
   *  $filters
   * );
   *
   * @param string $source Source path
   * @param string $target Target path
   * @param array $filters
   */
  public static function copyRecursive($source, $target, array $filters = NULL)
  {
    if (!empty($filters) && self::isFiltered($source, $filters)) {
      return;
    }

    if (!is_dir($source)) {
      copy($source, $target);
      chmod($target, self::$chmodValue);
      return;
    }

    if (!is_dir($target)) {
      mkdir($target, self::$chmodValue, TRUE);
    } else {
      chmod($target, self::$chmodValue);
    }

    foreach (array_diff(scandir($source), array('.', '..')) as $dirEntry) {
      self::copyRecursive(
        $source . DIRECTORY_SEPARATOR . $dirEntry,
        $target . DIRECTORY_SEPARATOR . $dirEntry,
        $filters
      );
    }
  }

  /**
   * recursive delete dir / file
   *
   * sample:
   *   file::delTree($target);
   *
   * @param string $path Path
   * @return bool
   */
  public static function delTree($path)
  {
    if (!is_dir($path)) {
      return unlink($path);
    }

    foreach (array_diff(scandir($path), array('.', '..')) as $dirEntry) {
      if (is_dir($path . DIRECTORY_SEPARATOR . $dirEntry)) {
        self::delTree($path . DIRECTORY_SEPARATOR . $dirEntry);
      } else {
        unlink($path . DIRECTORY_SEPARATOR . $dirEntry);
      }
    }

    return rmdir($path);
  }


}
