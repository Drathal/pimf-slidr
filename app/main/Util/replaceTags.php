<?php

namespace AppUtil;

class replaceTags
{
  
  public static $animations = array(
    '#<(\w+)>\[anim (bounceInLeft|bounceInRight|bounceInUp|bounceInDown|fadeIn|flipInX|flipInY|rollIn|rotateIn)\](.*?)</(?1)>#ims' => '<$1 class="Off-$2 animate">$3</$1>',    
  );
  
  public static $page = array(
    '<p>[page:title]</p>'   => '<figure class="bg title"><div>{page}</div></figure>',
    '<p>[page:divider]</p>' => '<figure class="bg divider"><div>{page}</div></figure>'
  );
  
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
    '|\[circle\]|i' => '<span class="circle"></span>'
  );
  
  public static $beforeTags = array( 
    // syntax highlight
    '|\[script (\w+)\](.*?)\[/script\]|ims' => '<script type="syntaxhighlighter" class="brush: $1"><![CDATA[ $2 ]]></script>',
    
    // colorise
    '@\[color (\w+|#[A-Fa-f0-9]{6}|#[A-Fa-f0-9]{3})\](.*?)\[/color\]@ims' => '<span style="color: $1">$2</span>'
  );
  
  public static function animations($string)
  {
    return self::replaceAll($string,self::$animations);
  }
  
  public static function tags($string)
  {
    return self::replaceAll($string,self::$tags);
  }
  
  public static function beforeTags($string)
  {
    return self::replaceAll($string,self::$beforeTags);
  }
  
  public static function page($string) {
    foreach (self::$page as $tag => $snippet) {
      if (strstr($string, $tag)) {
        return str_replace('{content}', str_replace($tag, '', $string), self::$page[$tag]);
      }
    }
    return $string;
  }
  
  private static function replaceAll($string,$what)
  {
    foreach ($what as $tag => $snippet) {
      $string = preg_replace($tag, $snippet, $string);
    }
    return $string;
  }
  


  /*
  // setup page templates
  public static $pageTemplates = array('<p>[page:title]</p>' => '<figure class="bg title"><div>{content}</div></figure>',
    '<p>[page:divider]</p>' => '<figure class="bg divider"><div>{content}</div></figure>');

  // setup Tags
  public static $tags = array( // background class
    '|<p>\[bgclass (.*)\]</p>|i' => '<figure class="bg $1"></figure>',

    // background image
    '|<p>\[bgimage (.*)\]</p>|i' => '<figure class="bg image"><img alt="background" class="bgimage" src="image/$1" /></figure>',

    // background iframe
    '|<p>\[iframe src="(.*)" height="([0-9*])" width="([0-9*])"\]</p>|i' => '<iframe class="bg iframe" src="$1" height="$2" width="$3"></iframe>',
    '|<p>\[iframe src="(.*)" width="([0-9*])" height="([0-9*])"\]</p>|i' => '<iframe class="bg iframe" src="$1" width="$2" height="$3"></iframe>',
    '|<p>\[iframe src="(.*)"\]</p>|i' => '<iframe class="bg iframe" src="$1" ></iframe>',
    '|<p>\[iframe (.*)\]</p>|i' => '<iframe class="bg iframe" src="$1" ></iframe>',
    
    '#<(\w+)>\[anim (bounceInLeft|bounceInRight|bounceInUp|bounceInDown|fadeIn|flipInX|flipInY|rollIn|rotateIn)\](.*?)</(?1)>#ims' => '<$1 class="Off-$2 animate">$3</$1>',

    // circle
    '|\[circle\]|i' => '<span class="questbackCircle"></span>'
  );
  
   // before Tags
  public static $beforeTags = array( 
    // syntax highlight
    '|\[script (\w+)\](.*?)\[/script\]|ims' => '<script type="syntaxhighlighter" class="brush: $1"><![CDATA[ $2 ]]></script>',
    '@\[color (\w+|#[A-Fa-f0-9]{6}|#[A-Fa-f0-9]{3})\](.*?)\[/color\]@ims' => '<span style="color: $1">$2</span>'
  );
  
  // simple - but works ;D
  public static $animations = array("bounceInLeft", "bounceInRight", "bounceInUp", "bounceInDown", "fadeIn", "flipInX", "flipInY", "rollIn", "rotateIn");
  public static $animationTags = array(
    '<h1>[a #animation#]' => '<h1 class="Off-#animation# animate">',
    '<h2>[a #animation#]' => '<h1 class="Off-#animation# animate">',
    '<h3>[a #animation#]' => '<h1 class="Off-#animation# animate">',
    '<p>[a #animation#]' => '<p class="Off-#animation# animate">',
    '<li>[a #animation#]' => '<li class="Off-#animation# animate">',
    '<div>[a #animation#]' => '<div class="Off-#animation# animate">',
    '<pre>[a #animation#]' => '<pre class="Off-#animation# animate">'
  );

  public static function beforeConvert($html)
  {
    // apply before tags
    foreach (self::$beforeTags as $tag => $snippet) {
      $html = preg_replace($tag, $snippet, $html);
    }
    return $html;
  }

  public static function convert($html)
  {

    // apply page templates
    foreach (self::$pageTemplates as $tag => $snippet) {
      if (strstr($html, $tag)) {
        $html = str_replace($tag, '', $html);
        $html = str_replace('{content}', $html, self::$pageTemplates[$tag]);
      }
    }

    // apply special tags
    foreach (self::$tags as $tag => $snippet) {
      $html = preg_replace($tag, $snippet, $html);
    }

    // apply animation classes
    foreach (self::$animations as $animation) {
      foreach (self::$animationTags as $old => $new) {
        $o = str_replace('#animation#', $animation, $old);
        $n = str_replace('#animation#', $animation, $new);
        $html = str_replace($o, $n, $html);
      }
    }

    return $html;
  }
  */
}