<?php

namespace AppUtil;

/**
 * A small Class to add additional tags to markdown
 * Class replaceTags
 * @package AppUtil
 */
class replaceTags
{

  /**
   * regex that add animations
   * @var array
   */
  public static $animations = array(
    '#<(\w+)>\[anim (bounceInLeft|bounceInRight|bounceInUp|bounceInDown|fadeIn|flipInX|flipInY|rollIn|rotateIn)\](.*?)</(?1)>#ims' => '<$1 class="Off-$2 animate">$3</$1>',    
  );

  /**
   * the whole page is wrapped into that figure
   * @var array
   */
  public static $page = array(
    '<p>[page:title]</p>'   => '<figure class="bg title"><div>{content}</div></figure>',
    '<p>[page:divider]</p>' => '<figure class="bg divider"><div>{content}</div></figure>'
  );

  /**
   * additional tags
   * @var array
   */
  public static $tags = array( 
  
    // background class
    '|<p>\[bgclass (.*)\]</p>|i' => '<figure class="bg $1"></figure>',

    // background image
    '|<p>\[bgimage (.*)\]</p>|i' => '<figure class="bg image"><img alt="background" class="bgimage" src="image/$1" /></figure>',

    // background iframe
    '|<p>\[iframe src="(.*)" height="([0-9*])" width="([0-9*])"\]</p>|i' => '<iframe class="bg iframe" src="$1" height="$2" width="$3"></iframe>',
    '|<p>\[iframe src="(.*)" width="([0-9*])" height="([0-9*])"\]</p>|i' => '<iframe class="bg iframe" src="$1" width="$2" height="$3"></iframe>',
    '|<p>\[iframe src="(.*)"\]</p>|i' => '<iframe class="bg iframe" src="$1" ></iframe>',
    '|<p>\[iframe (.*)\]</p>|i' => '<iframe class="bg iframe" src="$1" ></iframe>',

    // circle
    '|\[circle\]|i' => '<span class="circle"></span>',
    '|\[square\]|i' => '<span class="square"></span>'
  );

  /**
   * tags that should be used before we transform the string into html
   * @var array
   */
  public static $beforeTags = array( 
    // syntax highlight
    '|\[script (\w+)\](.*?)\[/script\]|ims' => '<script type="syntaxhighlighter" class="brush: $1"><![CDATA[ $2 ]]></script>',
    
    // colorise
    '@\[color (\w+|#[A-Fa-f0-9]{6}|#[A-Fa-f0-9]{3})\](.*?)\[/color\]@ims' => '<span style="color: $1">$2</span>'
  );

  /**
   * apply animations
   * @param $string
   * @return mixed
   */
  public static function animations($string)
  {
    return self::replaceAll($string,self::$animations);
  }

  /**
   * apply tags
   * @param $string
   * @return mixed
   */
  public static function tags($string)
  {
    return self::replaceAll($string,self::$tags);
  }

  /**
   * apply tags before html transformation
   * @param $string
   * @return mixed
   */
  public static function beforeTags($string)
  {
    return self::replaceAll($string,self::$beforeTags);
  }

  /**
   * apply page templates
   * @param $string
   * @return mixed
   */
  public static function page($string) {
    foreach (self::$page as $tag => $snippet) {
      if (strstr($string, $tag)) {
        return str_replace('{content}', str_replace($tag, '', $string), self::$page[$tag]);
      }
    }
    return $string;
  }

  /**
   * apply all replacements
   * @param $string
   * @param $what
   * @return mixed
   */
  private static function replaceAll($string,$what)
  {
    foreach ($what as $tag => $snippet) {
      $string = preg_replace($tag, $snippet, $string);
    }
    return $string;
  }
  
}