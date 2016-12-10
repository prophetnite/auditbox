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

$jobControl = new jobControlEngineClass(array(
                                                'sqlEngine' => &$sqlEngine,
                                                'userEngine' => &$userEngine,
                                              ));

$currentJobs = $jobControl->getJobsByClient($userEngine->getUser('id'));

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
                          <h3>Job Control</h3>
                          <small>
                              Manage your job commands and schedule
                          </small>
                      </div>
                  </div>
                  <hr>
              </div>
          </div>

          <a href="addJob.php" class="btn btn-w-md btn-success">Setup New Job</a><br><br>

          <div class="row">

              <div class="col-md-12">

                  <div class="panel panel-filled">
                      <div class="panel-heading">
                          <div class="panel-tools">
                              <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                              <a class="panel-close"><i class="fa fa-times"></i></a>
                          </div>
                          Active Jobs
                      </div>
                      <div class="panel-body">
                          Below is the active job schedules, each device will activate the jobs according to it's 'Next Run' date.
                          <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                  <thead>
                                  <tr>
                                      <th>
                                          Device Name
                                      </th>
                                      <th>
                                          Job Label
                                      </th>
                                      <th>
                                          Next Run
                                      </th>
                                      <th></th>
                                      <th></th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?php
                                    foreach($currentJobs as &$job){
                                  ?>
                                  <tr>
                                      <th class="text-nowrap" scope="row">main_office</th>
                                      <td><?php echo $job['label']; ?></td>
                                      <td><?php echo $job['next_run']; ?></td>
                                      <td>
                                        <a href="editJob.php?id=<?php echo $job['id']; ?>" class="btn btn-w-md btn-warning">
                                          <i class="pe page-header-icon pe-7s-config"></i>
                                          Edit
                                        </a>
                                      </td>
                                      <td>
                                        <a href="deleteJob.php?id=<?php echo $job['id']; ?>" class="btn btn-w-md btn-danger"  onclick="return confirm('Are you sure you want to delete this job?');">
                                          <i class="pe page-header-icon pe-7s-junk"></i>
                                          Delete
                                        </a>
                                      </td>
                                  </tr>
                                  <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

<?php
$theme->footer();
?>
