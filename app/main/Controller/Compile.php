<?php
namespace main\Controller;

use Pimf\Controller\Base, Pimf\View\Twig as View, \Michelf\Markdown, AppUtil\replaceTags, \Pimf\Registry, AppUtil\file;

class Compile extends Base
{

  /**
   * index action - at the moment only a dummy page
   */
  public function indexAction()
  {
    $slidename = $this->request->fromGet()->get('slidename', 'inger_intro');

    $this->setupOutput($slidename);

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
  private function markdown2html($md)
  {
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
  private function getBasePath()
  {
    return realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
  }

  /**
   * @return string
   */
  private function getInputPath()
  {
    $conf = Registry::get('conf');
    return realpath($this->getBasePath() . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . $conf['slidr']['inputFolder'];
  }

  /**
   * @return string
   */
  private function getOutputPath()
  {
    $conf = Registry::get('conf');
    return realpath($this->getBasePath() . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . $conf['slidr']['outputFolder'];
  }

  /**
   * @return string
   */
  private function getLayoutBasePath()
  {
    $conf = Registry::get('conf');
    return realpath($this->getBasePath() . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . $conf['slidr']['layoutBase'];
  }

  /**
   * @return string
   */
  private function getLayoutPath()
  {
    $conf = Registry::get('conf');
    return realpath($this->getBasePath() . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . $conf['slidr']['layout'];
  }

  /**
   * @param $slidename
   */
  private function setupOutput($slidename)
  {

    $filters = array(
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '*~',
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '*.scss',
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '*.bak',
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '*.orig',
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '*.swp',
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . 'config.ini',
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '.DS_Store',
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '.hidden',
      DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . 'partials',
    );
    
    
    $destinationDirectory = $this->getOutputPath() . DIRECTORY_SEPARATOR . $slidename;

    // delete output dir
    if (file_exists($destinationDirectory)) {
      file::delTree($destinationDirectory);
    }

    // copy base files to output dir
    file::copyRecursive(
      $this->getLayoutBasePath(),
      $destinationDirectory,
      $filters
    );
    
    // copy layout files to output dir
    file::copyRecursive(
      $this->getLayoutPath(),
      $destinationDirectory,
      $filters
    );
    
    $out  = $this->getLayoutBasePath().' -> '.$destinationDirectory ."<br>";
    $out .= $this->getLayoutPath().' -> '.$destinationDirectory ."<br>";

    echo $out;

    //$sample = '# test [test]'."\n".'[anim bounceInLeft]this is a random text'."\n";
    //$test = $this->markdown2html($sample);


  }
}