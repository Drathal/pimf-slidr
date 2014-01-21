<?php
namespace main\Controller;

use Pimf\Controller\Base, Pimf\View\Twig as View, \Michelf\Markdown, AppUtil\replaceTags, \Pimf\Registry, AppUtil\file;

/**
 * todo : refactor get{path} methods
 * 
 * Class Compile
 * @package main\Controller
 */
class Compile extends Base
{

  /**
   * index action - at the moment only a dummy page
   */
  public function indexAction()
  {
    
    $slidename = $this->request->fromGet()->get('slidename', 'sample');
    $slideHtml = $this->getOutput($slidename);
    
    $slideView = new View(
      'article.phtml',
      array(
        'slides'   => $slideHtml,
      )
    );
    
    $fullSlide = $slideView->render();

    $view = new View(
      'compile.phtml',
      array(
        'title'    => 'Slidr:compile',
        'subtitle' => 'compile ' . join(' ', (array)$slidename) . '.',
        'content'  => $fullSlide
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
    $md = replaceTags::tags($md);
    $md = Markdown::defaultTransform($md);
    $md = replaceTags::animations($md);
    $md = replaceTags::page($md);
    $md = trim(preg_replace('/  +/i', ' ', $md));
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
   * @return string
   */
  private function getOutput($slidename)
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
    
    // convert markdown files
    $pages = array_filter(scandir($this->getInputPath() . DIRECTORY_SEPARATOR . $slidename), function ($i) {
      return strstr($i,'.md');
    });
    
    $html = array();
    foreach ($pages as $page) {
      $html[] = $this->markdown2html(file_get_contents($this->getInputPath() . DIRECTORY_SEPARATOR . $slidename . DIRECTORY_SEPARATOR . $page));
    }
    
    return $html;
  }
}