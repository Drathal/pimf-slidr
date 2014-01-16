<?php
namespace main\Controller;

use Pimf\Controller\Base, Pimf\View\Twig as View, \Michelf\Markdown, AppUtil\replaceTags;

class Compile extends Base
{
  /**
   * A index action - this is a framework restriction!
   */
  public function indexAction()
  {

    $slidename = $this->request->fromGet()->get('slidename', 'inger_intro');

    $sample = '# test [test]'."\n".'[anim bounceInLeft]this is a random text'."\n";
    
    $test = $this->markdown2html($sample);

    echo new View(
      'compile.phtml',
      array(
        'title' => 'Slidr:compile',
        'content' => 'compile ' . join(' ', (array)$slidename) . '.'.$test
      )
    );
  }

  /**
   * convert custom md format to html
   * @param $md
   * @return mixed
   */
  private function markdown2html($md) {
    $md = replaceTags::beforeTags($md);
    $md = Markdown::defaultTransform($md);
    $md = replaceTags::animations($md);
    $md = replaceTags::page($md);
    $md = replaceTags::tags($md);
    return $md;
  }
}