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

// init main api engine //
$apiEngine = new apiEngineClass(array('sqlEngine' => &$sqlEngine));

if(empty($_GET['k'])) die($apiEngine->buildError('1', 'missing_key'));
if(empty($_GET['r'])) die($apiEngine->buildError('2', 'missing_request'));

// init main user engine //
$userEngine = new userEngineClass(array('sqlEngine' => &$sqlEngine));

$ownerId = $userEngine->getUserIdByAppKey($_GET['k']);

if(!$ownerId) die($apiEngine->buildError('3', 'unknown_client'));

switch($_GET['r']){
  // NORMAL OPS //
  case '1': // checking if jobs need to be done //
    $jobList = $userEngine->getNearestJobs($ownerId);
    if(!$jobList){
      echo '0';
    }else{
      echo '1';
    }

  break;
  case '2': // pulling job info //
    $jobList = $userEngine->getNearestJobs($ownerId);
    $apiEngine->outputJobs($jobList);
    $apiEngine->incJobs($jobList);
  break;
  case '3': // client is submitting a long fucking report >.>" //
    if(empty($_POST) || empty($_POST['report'])) die($apiEngine->buildError('5', 'no_report'));
    if(empty($_POST['task_id'])) die($apiEngine->buildError('5', 'no_task_id'));
    $res = $apiEngine->storeReport($ownerId, $_POST['task_id'], $_POST['report']);
    if(!$res) die($apiEngine->buildError('666', 'unknown_error'));

    $apiEngine->success();
  break;

  // SETUP OPS //
  case '10': // initial setup //
    if(empty($_POST) || empty($_POST['target_id'])) die($apiEngine->buildError('5', 'no_target_id'));
    if(empty($_POST['device_label'])) die($apiEngine->buildError('5', 'no_device_label'));
    if(empty($_POST['avail_configs'])) die($apiEngine->buildError('5', 'no_configs'));
    $res = $apiEngine->storeNewDevice($ownerId, $_POST['target_id'], $_POST['device_label'], $_POST['avail_configs']);
    if(!$res) die($apiEngine->buildError('666', 'unknown_error'));

    $apiEngine->success();
  break;
  default:
    die($apiEngine->buildError('4', 'bad_request'));
}

$sqlEngine->disconnect();

?>
