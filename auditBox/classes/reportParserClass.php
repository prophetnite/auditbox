<?php

class reportParserClass{
  // class properties //


  function __construct(){
    @session_start();

    // import passed properties //
    $args = func_get_args();

    if(!empty($args))
      foreach($args[0] as $propName => $propData)
        if(property_exists($this, $propName))
          $this->{$propName} = $propData;
  }

  public function loadReportData($logPath){
    $reportData = file_get_contents($logPath);

    return json_decode($reportData, TRUE);
  }

}

?>
