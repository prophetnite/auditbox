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

$reportMeta = $sqlEngine->getReportMeta($userEngine->getUser('id'));

$theme = new themeEngineClass(array('sqlEngine' => &$sqlEngine));
$theme->header();
$theme->nav();
?>

          <div class="row">
              <div class="col-lg-12">
                  <div class="view-header">
                      <div class="pull-right text-right" style="line-height: 14px">
                          <small>Add<br>Modify<br>Delete</small>
                      </div>
                      <div class="header-icon">
                          <i class="pe page-header-icon pe-7s-config"></i>
                      </div>
                      <div class="header-title">
                          <h3>All Job Reports</h3>
                          <small>
                              Review and compare reports
                          </small>
                      </div>
                  </div>
                  <hr>
              </div>
          </div>

          <div class="row">

              <div class="col-md-12">

                  <div class="panel panel-filled">
                      <div class="panel-heading">
                          <div class="panel-tools">
                              <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                              <a class="panel-close"><i class="fa fa-times"></i></a>
                          </div>
                          Available Reports
                      </div>
                      <div class="panel-body">
                          Below are all the reports that are ready
                          <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                  <thead>
                                  <tr>
                                      <th></th>
                                      <th>
                                          Task Label
                                      </th>
                                      <th>
                                          Report Date
                                      </th>
                                      <th></th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    if(!$reportMeta){?>
                                  <tr>
                                      <th class="text-nowrap" scope="row">No Reports</th>
                                  </tr>
                                    <?php }else{
                                      foreach($reportMeta as $report){
                                    ?>
                                  <tr>
                                      <td style="text-align:center;"><input type="checkbox" onclick="" /></td>
                                      <td class="text-nowrap" scope="row"><?php echo $report['task_id']; ?></th>
                                      <td><?php echo $report['report_date']; ?></td>
                                      <td>
                                        <a href="viewReport.php?id=<?php echo $report['id']; ?>" class="btn btn-w-md btn-success">
                                          <i class="pe page-header-icon pe-7s-config"></i>
                                          Review
                                        </a>
                                      </td>
                                  </tr>
                                  <?php
                                    }
                                  }
                                  ?>
                                  </tbody>
                                </table>
                                <br>
                                <a href="compareReports.php?id=" class="btn btn-w-md btn-success">
                                  <i class="pe page-header-icon pe-7s-photo-gallery"></i>
                                  Compare Selected
                                </a>
                              </div>
                          </div>
                      </div>

                  </div>

              </div>

<?php
$theme->footer();
?>
