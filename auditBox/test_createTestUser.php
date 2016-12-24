<?php

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

$newId = $userEngine->addUser('test1', 'secretsauce', 'firstname_john', 'lastname_smith', 'biz llc', 'email@test.com');

if($newId === false) die('Failed to add user.');

echo $sqlEngine->getUserById($newId)['app_key'];

$sqlEngine->disconnect();
?>
