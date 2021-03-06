<?php
/*
|--------------------------------------------------------------------------
| Your Application's PHP classes auto-loading
|
| All classes in PIMF are statically mapped. It's just a simple array of
| class to file path maps for ultra-fast file loading.
|--------------------------------------------------------------------------
*/
spl_autoload_register(
  function ($class) {

    // *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
    // FEEL FREE TO CHANGE THE MAPPINGS AND DIRECTORIES
    // *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-

    /**
     * The mappings from class names to file paths.
     */
    static $mappings = array(
      'main\\Controller\\Compile'  => '/main/Controller/Compile.php',
      'Michelf\\Markdown' => '../php-markdown/Michelf/Markdown.php',
      'Michelf\\MarkdownInterface' => '../php-markdown/Michelf/MarkdownInterface.php',
      'AppUtil\\replaceTags' => '/main/Util/replaceTags.php',
      'AppUtil\\file' => '/main/Util/file.php'
    );

    // *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
    //  END OF USER CONFIGURATION!!!
    // *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-

    // load the class from the static heap of classes.
    if (isset($mappings[$class])) {
      return require __DIR__ . DIRECTORY_SEPARATOR . $mappings[$class];
    }

    return false;
  }
);
