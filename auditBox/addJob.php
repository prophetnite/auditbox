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

// To do: add better error handling, but we are rushing I guess //
if(!empty($_POST)){
  if(empty($_POST) || empty($_POST['label'])) die('no_label');
  if(empty($_POST['task_id'])) die('no_task_id');
  if(empty($_POST['run_interval'])) die('no_run_interval');
  $jobControl->addNewJob($_POST['label'], $_POST['task_id'], $userEngine->getUser('id'), $_POST['run_interval']);
}

$deviceArray = $userEngine->getClientDevices();

$theme = new themeEngineClass(array('sqlEngine' => &$sqlEngine));
$theme->header();
$theme->nav();

if(!empty($_GET['save'])){ ?>
  <script>
    var alertQueue = [];
    alertQueue.push({type: 'success', msg: '<strong>Saved!</strong> <br/><small>Your job as been added.</small>'});
  </script>
<?php } ?>

          <div class="row">
              <div class="col-lg-12">
                  <div class="view-header">
                      <div class="pull-right text-right" style="line-height: 14px">
                          <small>When<br>Where<br>How</small>
                      </div>
                      <div class="header-icon">
                          <i class="pe page-header-icon pe-7s-timer"></i>
                      </div>
                      <div class="header-title">
                          <h3>Create New Job</h3>
                          <small>
                              Setup a new scheduled job
                          </small>
                      </div>
                  </div>
                  <hr>
              </div>
          </div>
          <form action="addJob.php?save=true" id="addJob" method="post">
            <div class="col-md-6">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                            <a class="panel-close"><i class="fa fa-times"></i></a>
                        </div>
                        Job Label
                    </div>
                    <div class="panel-body">

                        <p>Choose the label that will be given to the job</p>

                        <div class=form-group>
                            <label>Label:</label>
                        </div>
                        <input type="text" name="label" class="form-control" placeholder="Office Daily Scan">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                            <a class="panel-close"><i class="fa fa-times"></i></a>
                        </div>
                        Target Device
                    </div>
                    <div class="panel-body">

                        <p>Choose the device that will preform the job (inactive option)</p>

                        <div class=form-group>
                            <label>Target:</label>
                        </div>
                        <select class="form-control" name="device_label">
                          <?php

                            foreach($deviceArray as &$device){
                          ?>
                            <option value="<?php echo $device['id']; ?>"><?php echo $device['device_label']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                            <a class="panel-close"><i class="fa fa-times"></i></a>
                        </div>
                        Available Jobs
                    </div>
                    <div class="panel-body">

                        <p>The following are all the jobs your device currently handles</p>

                        <div class=form-group>
                            <label>Task:</label>
                        </div>
                        <select class="form-control" name="task_id">
                          <?php
                            $tasks = json_decode($deviceArray[0]['avail_configs'], true);
                            foreach($tasks as &$task){
                          ?>
                            <option value="<?php echo $task['id']; ?>"><?php echo $task['label']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                            <a class="panel-close"><i class="fa fa-times"></i></a>
                        </div>
                        How often to run
                    </div>
                    <div class="panel-body">

                        <p>Choose how many days in between scans</p>

                        <div class=form-group>
                            <label>Frequency:</label>
                        </div>
                        <select class="form-control" name="run_interval">
                          <option value="1 00:00">1 Day</option>
                          <option value="3 00:00">3 Days</option>
                          <option value="7 00:00">7 Days</option>
                          <option value="14 00:00">14 Days</option>
                          <option value="30 00:00">30 Days</option>
                        </select>
                    </div>
                </div>
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                            <a class="panel-close"><i class="fa fa-times"></i></a>
                        </div>
                        Save or Cancel
                    </div>
                    <div class="panel-body">
                      <a href="#" onclick="document.getElementById('addJob').submit();" class="btn btn-w-md btn-success">
                        <i class="pe page-header-icon pe-7s-upload"></i>
                        Save
                      </a>

                      <a href="control.php" class="btn btn-w-md btn-danger">
                        <i class="pe page-header-icon pe-7s-junk"></i>
                        Cancel
                      </a>
                    </div>
                </div>
            </div>
          </form>

<?php
$theme->footer();
?>
