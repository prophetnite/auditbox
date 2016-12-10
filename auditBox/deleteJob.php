<?php

if(empty($_GET['id'])) die(header('Location: control.php'));

spl_autoload_register(function($targetClass){
  // possible locations of class //
  $dirArray = array(
    'classes/',
    '/../classes/'
  );

  // start search //
  foreach($dirArray as $dir){
    if(file_exists($dir . $targetClass . '.php')){
      // found class, include & exit //
      require_once($dir . $targetClass . '.php');

      return;
    }
  }
});

// init main sql engine //
$sqlEngine = new sqlEngineClass();

// init main user engine //
$userEngine = new userEngineClass(array('sqlEngine' => &$sqlEngine));
$userEngine->checkLogin();

$jobControl = new jobControlEngineClass(array(
                                                'sqlEngine' => &$sqlEngine,
                                                'userEngine' => &$userEngine,
                                              ));

$jobControl->removeJob($userEngine->getUser('id'), $_GET['id']);

die(header('Location: control.php'));

?>
