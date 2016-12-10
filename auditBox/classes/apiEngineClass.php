<?php

class apiEngineClass{
  // sql config //
  protected $sqlEngine;

  function __construct(){
    @session_start();

    // import passed properties //
    $args = func_get_args();

    if(!empty($args))
      foreach($args[0] as $propName => $propData)
        if(property_exists($this, $propName))
          $this->{$propName} = $propData;
  }

  public function buildError($errId, $errText){
    return "<error id=\"$errId\">$errText</error>";
  }

  public function success(){
    return "<success />";
  }

  private function startTask($taskId){
    return "<start_task task_id=\"$taskId\" />\n";
  }

  public function outputJobs($jobList){
    foreach($jobList as $job)
      echo $this->startTask($job['task_id']);
  }

  public function incJobs($jobList){
    foreach($jobList as $job)
      $this->sqlEngine->incJobNextRun($job['id']);
  }

  public function storeReport($clientId, $taskId, $data){
    // pre-parsing, if needed, goes here //
    return $this->sqlEngine->storeJobReport($clientId, $taskId, $data);
  }

  public function storeNewDevice($ownerId, $targetId, $deviceLabel, $availConfigs){
    // pre-parsing, if needed, goes here //
    return $this->sqlEngine->storeNewDevice($ownerId, $targetId, $deviceLabel, $availConfigs);
  }

}

?>
