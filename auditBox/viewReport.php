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
$userEngine->checkLogin();

$reportMeta = $sqlEngine->getReportById($userEngine->getUser('id'), $_GET['id']);

$reportParser = new reportParserClass(array());
$reportLoaded = $reportParser->loadReport('../' . $reportMeta['path']);

$theme = new themeEngineClass(array('sqlEngine' => &$sqlEngine));
$theme->header();
$theme->nav();
?>

          <div class="row">
              <div class="col-lg-12">
                  <div class="view-header">
                      <div class="pull-right text-right" style="line-height: 14px">
                          <small>Viewing Report</small>
                      </div>
                      <div class="header-icon">
                          <i class="pe page-header-icon pe-7s-browser"></i>
                      </div>
                      <div class="header-title">
                          <h3>Job Report</h3>
                          <small>
                              Showing non-comparative report<br>
                              Report saved on: <?php if($reportLoaded) echo $reportMeta['report_date']; ?>
                          </small>
                      </div>
                  </div>
                  <hr>
              </div>
          </div>
          <?php if(!$reportLoaded){ ?>
            <div class="row">

                <div class="col-md-12">

                    <div class="panel panel-filled">
                        <div class="panel-heading">
                            <div class="panel-tools">
                                <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                                <a class="panel-close"><i class="fa fa-times"></i></a>
                            </div>
                            Report Details
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        Error loading report.
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
          <?php
          }else{
          ?>
          <div class="row">

              <div class="col-md-12">

                  <div class="panel panel-filled">
                      <div class="panel-heading">
                          <div class="panel-tools">
                              <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                              <a class="panel-close"><i class="fa fa-times"></i></a>
                          </div>
                          Port Information
                      </div>
                      <div class="panel-body">
                          <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                  <thead>
                                  <tr>
                                      <th>
                                          Open Port:
                                      </th>
                                      <th>Type:</th>
                                      <th></th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $openPorts = $reportParser->report['ports']['port'];
                                      for($i = 0, $j = count($openPorts) * .5; $i < $j; $i++){ ?>
                                      <tr>
                                          <td>
                                            <?php echo $openPorts[$i]; ?>
                                          </td>
                                          <td>
                                            <?php echo $openPorts[$i + (count($openPorts) * .5)]; ?>
                                          </td>
                                          <td>
                                            <a href="http://www.speedguide.net/port.php?port=<?php echo trim(explode('/', $openPorts[$i])[0]); ?>" target="_blank" class="btn btn-w-md btn-success">
                                              <i class="pe page-header-icon pe-7s-config"></i>
                                              Information
                                            </a>
                                          </td>
                                      </tr>
                                    <?php
                                      }

                                      unset($openPorts);
                                    ?>
                                  </tbody>
                                </table>
                              </div>
                          </div>
                      </div>

                  </div>

              </div>
              <?php
              $results = $reportParser->report['results']['result'];
              for($i = 0, $j = count($results); $i < $j; $i++){
              ?>
              <div class="row">

                  <div class="col-md-12">

                      <div class="panel panel-filled">
                          <div class="panel-heading panel-toggle" style="cursor: pointer;" onmouseover="this.style.backgroundColor='#484c5a';" onmouseout="this.style.backgroundColor='';">
                              <div class="panel-tools ">
                                  <a class="panel-toggle"><i class="fa fa-chevron-down"></i></a>
                                  <a class="panel-close"><i class="fa fa-times"></i></a>
                              </div>
                              <?php echo $results[$i]['name']; ?>
                          </div>
                          <div class="panel-body panel-collapse collapse">
                              <div class="table-responsive">
                                  <table class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Port:</th>
                                          <th>Severity:</th>
                                          <th colspan="2">Threat Level:</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                              <td>
                                                <?php echo $results[$i]['port']; ?>
                                              </td>
                                              <td>
                                                <?php echo $results[$i]['severity']; ?>
                                              </td>
                                              <td colspan="2">
                                                <?php echo $results[$i]['threat']; ?>
                                              </td>
                                          </tr>
                                          <tr>
                                            <th colspan="2">Description:</th>
                                            <th colspan="2">Details:</th>
                                          </tr>
                                          <tr>
                                            <td colspan="2">
                                              <?php echo nl2br($results[$i]['description']); ?>
                                            </td>
                                            <td colspan="2">
                                              <b>CVE:</b>
                                              <?php
                                              $cves = explode(',', $results[$i]['nvt']['cve']);
                                              if(count($cves) == 1 && $cves[0] == 'NOCVE'){
                                                echo 'None';
                                              }else{
                                                foreach($cves as $cve){
                                              ?>
                                              <a href="https://www.cvedetails.com/cve/<?php echo trim($cve); ?>/" target="_blank">
                                                <?php echo trim($cve); ?>
                                              </a>&nbsp;
                                              <?php
                                                }
                                              }
                                              echo '<br>';
                                              echo nl2br($results[$i]['nvt']['tags']); ?>
                                            </td>
                                          </tr>
                                      </tbody>
                                    </table>
                                  </div>
                              </div>
                          </div>

                      </div>

                  </div>
          <?php
            }

            unset($results);
          }

$theme->footer();
?>
