<?php

class html2slide
{

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
}