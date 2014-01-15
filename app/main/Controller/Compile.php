<?php
namespace main\Controller;

use Pimf\Controller\Base, Pimf\View\Twig as View, \Michelf\Markdown;

class Compile extends Base
{
  /**
   * A index action - this is a framework restriction!
   */
  public function indexAction()
  {

    $slidename = $this->request->fromGet()->get('slidename', 'inger_intro');

    $test = $this->markdown2html("# test");
    
    echo new View(
      'compile.phtml',
      array(
        'title' => 'Slidr:compile',
        'content' => 'compile ' . join(' ', (array)$slidename) . '.'.$test
      )
    );
  }

  /**
   * @param $md
   * @return mixed
   */
  private function markdown2html($md) {
    return Markdown::defaultTransform($md);
  }
}