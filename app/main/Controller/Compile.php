<?php
namespace main\Controller;

use Pimf\Controller\Base, Pimf\View\Twig as View, \Michelf\Markdown, AppUtil\replaceTags, \Pimf\Registry;

class Compile extends Base
{
 
  /**
   * A index action - this is a framework restriction!
   */
  public function indexAction()
  {
    $slidename = $this->request->fromGet()->get('slidename', 'inger_intro');
   
    $this->setupOutput($slidename,'presenter');

    $view = new View(
      'compile.phtml',
      array(
        'title' => 'Slidr:compile',
        'content' => 'compile ' . join(' ', (array)$slidename) . '.'
      )
    );
    
    $this->response->asHTML()->send($view);
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

  /**
   * @return string
   */
  private function getBasePath() {
    return realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
  }

  /**
   * @return string
   */
  private function getInputPath() {
    $conf = Registry::get('conf');
    return $this->getBasePath() . DIRECTORY_SEPARATOR . $conf['slidr']['inputFolder'];
  }

  /**
   * @return string
   */
  private function getOutputPath() {
    $conf = Registry::get('conf');
    return $this->getBasePath() . DIRECTORY_SEPARATOR . $conf['slidr']['outputFolder'];
  }

  /**
   * @param $slidename
   * @param $layoutname
   */
  private function setupOutput($slidename,$layoutname) {
    
    //$sample = '# test [test]'."\n".'[anim bounceInLeft]this is a random text'."\n";
    //$test = $this->markdown2html($sample);
    
    echo $this->getInputPath().$this->getOutputPath();
  }
}