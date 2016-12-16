<?php

class reportParserClass{
  // class properties //
  public $report;

  function __construct(){
    @session_start();

    // import passed properties //
    $args = func_get_args();

    if(!empty($args))
      foreach($args[0] as $propName => $propData)
        if(property_exists($this, $propName))
          $this->{$propName} = $propData;
  }

  public function loadReport($reportPath){
    if(!file_exists($reportPath)) return false;

    $this->report = json_decode(file_get_contents($reportPath), TRUE);

    if(!$this->report) return false;

    $this->report = $this->report['report'];

    return true;
  }

  public function outputRaw(){
    echo json_encode($this->currentReport);
  }

  public function outputPretty(){

  }

}

?>
