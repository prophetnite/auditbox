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
    $xml = @simplexml_load_string($data);
    if(!$xml) die($this->buildError('100', 'expected_xml'));
    $data = @json_encode($xml);
    if(!$data) die($this->buildError('101', 'log_parse_error'));

    $year = date('Y');
    $month = date('m');
    $day = date('d');

    $logPath = "../scanlogs/$year/$month/$day/";
    $logFilename = $taskId;

    if(!file_exists("../scanlogs/$year/$month/$day"))
      mkdir("../scanlogs/$year/$month/$day", 0777, true)
        or die($this->buildError('102', 'log_directory_creation'));

    $logHandle = fopen($logPath . '/' . $logFilename, "w")
      or die($this->buildError('103', 'log_file_creation'));

    fwrite($logHandle, $data);
    fclose($logHandle);

    return $this->sqlEngine->storeJobReport($clientId, $taskId, "scanlogs/$year/$month/$day/$logFilename");
  }

  public function storeNewDevice($ownerId, $targetId, $deviceLabel, $availConfigs){
    // pre-parsing, if needed, goes here //
    return $this->sqlEngine->storeNewDevice($ownerId, $targetId, $deviceLabel, $availConfigs);
  }

  public function storeDeviceCheckin($ownerId){
    return $this->sqlEngine->storeDeviceCheckin($ownerId);
  }
}

?>
