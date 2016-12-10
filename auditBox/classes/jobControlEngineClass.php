<?php

class jobControlEngineClass{
  // sql config //
  protected $sqlEngine;
  //protected $userEngine;

  function __construct(){
    @session_start();

    // import passed properties //
    $args = func_get_args();

    if(!empty($args))
      foreach($args[0] as $propName => $propData)
        if(property_exists($this, $propName))
          $this->{$propName} = $propData;
  }

  public function addNewJob($jobLabel, $taskId, $clientId, $runInterval){
    return $this->sqlEngine->saveClientJob($jobLabel, $taskId, $clientId, $runInterval);
  }

  public function removeJob($clientId, $jobId){
    return $this->sqlEngine->removeJob($clientId, $jobId);
  }

  public function getJobsByClient($clientId){
    $jobs = $this->sqlEngine->getClientJobs($clientId);
    return $jobs;
  }

  public function getJobDetails($clientId, $jobId){

  }


}

?>
