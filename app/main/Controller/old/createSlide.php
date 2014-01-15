<?php

// includes
include_once('../config.php');
include_once('../class/markdown.class.php');
include_once('../class/convert.class.php');
include_once('../class/copy.php');

$slidename = isset($_REQUEST['slide']) ? $_REQUEST['slide'] : 'javascript_coding_1';
$json = array('status' => FALSE);

$slideInputPath = APPINPUT . S . $slidename . S;
$slideOutputPath = APPOUTPUT . S . $slidename . S;

$pages = scandir($slideInputPath);
if ($pages) {
  
  $c = 0;
  $pagecontent = "";
  $pagetemplate = file_get_contents(APPADMIN . '/templates/page.html');

  // create output dirs
  rrmdir($slideOutputPath);
  recurse_copy(APPADMIN . '/presenter', $slideOutputPath);
  
  if (file_exists($slideInputPath .'/image')) {
    recurse_copy($slideInputPath .'/image', $slideOutputPath . '/image');
  }

  // convert all .md files
  foreach ($pages as $filename) {

    if (!strstr($filename, '.md')) {
      continue;
    }

    // path
    $fullreadpath = $slideInputPath . $filename;
    $fullwritepath = $slideOutputPath . '/pages/' . str_replace('.md', '.html', $filename);

    // convert
    $html = html2slide::convert(Markdown(html2slide::beforeConvert(file_get_contents($fullreadpath))));
    
    // collect output for later use
    $html = str_replace('#content#',$html,$pagetemplate);
    $html = str_replace('#slidenum#',$c++,$html);
    $pagecontent .= $html;

    // write html file
    file_put_contents($fullwritepath, $html);
    chmod($fullwritepath, 0755);

  }
  
  // create index / presentation file
  $template = file_get_contents(APPADMIN . '/templates/template.html');
  $ini = parse_ini_file($slideInputPath . '/config.ini');
  
  foreach ($ini as $name => $value ) {
    $template = str_replace('#'.$name.'#',$value,$template);
  }
  
  $template = str_replace('#slides#',$pagecontent,$template);
  
  file_put_contents($slideOutputPath . '/index.html', $template);
  chmod($slideOutputPath . '/index.html', 0755);

  $json = array('status' => TRUE);

}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
print json_encode($json);