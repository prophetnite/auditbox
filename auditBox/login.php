<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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

$loginErr = false;
if(!empty($_POST)){
  if(!empty($_POST['username']) && !empty($_POST['password'])){
    if($userEngine->attemptLogin($_POST['username'], $_POST['password'])){
      //die('dying for no reason');
      die(header('Location: index.php'));
    }
  }

  $loginErr = true;
}

$theme = new themeEngineClass(array('sqlEngine' => &$sqlEngine));
$theme->login($loginErr);
?>
