<?php
namespace main\Controller;

use Pimf\Controller\Base, Pimf\View\Twig as View;

class Compile extends Base
{
  /**
   * A index action - this is a framework restriction!
   */
  public function indexAction()
  {
    $slidename = $this->request->fromGet()->get('slidename', 'inger_intro');

    echo new View(
      'compile.phtml',
      array(
        'title' => 'Slidr:compile',
        'content' => 'compile ' . join(' ', (array)$slidename) . '.'
      )
    );
  }
}