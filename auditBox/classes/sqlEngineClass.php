<?php

class sqlEngineClass{
    // sql config //
    protected $serverPath = 'localhost';
    //protected $username = 'root';
    //protected $password = '';
    protected $username = 'auditbox';
    protected $password = 'KnAHb5NU8tqzKJ4z';
    protected $mainDb = 'auditbox';
    protected $sql;
    protected $connected = false;

    // query data //
    protected $getReportMetaQuery = [
      'sql' => "SELECT `id`, `task_id`, `report_date`
                FROM `reports`
                WHERE `job_owner` = ?
                ORDER BY `report_date` DESC",
      'paramTypes' => 'i'
    ];

    protected $getUserMetaQuery = [
      'sql' => "SELECT `first_name`, `last_name`, `business_name`, `email`, `avatar`, `max_devices`, `device_labels`
                FROM `client_meta`
                WHERE `client_meta`.`client_id` = ? LIMIT 1",
      'paramTypes' => 'i'
    ];

    protected $getUserByIdQuery = [
      'sql' => "SELECT `clients`.`id`, `app_key`, `first_name`, `last_name`, `business_name`, `email`, `avatar`, `max_devices`, `device_labels`
                FROM `clients` LEFT JOIN `client_meta` ON `clients`.`id` = `client_meta`.`id`
                WHERE `clients`.`id` = ? LIMIT 1",
      'paramTypes' => 'i'
    ];

    protected $getUserByHashQuery = [
      'sql' => "SELECT `clients`.`id`, `app_key`, `first_name`, `last_name`, `business_name`, `email`, `avatar`, `max_devices`, `device_labels`
                FROM `clients` LEFT JOIN `client_meta` ON `clients`.`id` = `client_meta`.`id`
                WHERE `clients`.`auth_hash` = ? LIMIT 1",
      'paramTypes' => 's'
    ];

    protected $getClientDevicesQuery = [
      'sql' => "SELECT `id`, `target_id`, `device_label`, `avail_configs`, `last_checkin`
                FROM `client_devices`
                WHERE `owner_id` = ?",
      'paramTypes' => 'i'
    ];

    protected $getClientJobsQuery = [
      'sql' => "SELECT `id`, `label`, `task_id`, `last_run`, `next_run`, `run_interval`, `run_once`
                FROM `jobs`
                WHERE `job_owner` = ?",
      'paramTypes' => 'i'
    ];

    protected $saveClientJobQuery = [
      'sql' => "INSERT INTO `jobs` (`id`, `label`, `task_id`, `job_owner`, `last_run`, `next_run`, `run_interval`, `run_once`)
                VALUES (NULL, ?, ?, ?, NOW(), ADDTIME(NOW(), '00:10:00'), ?, '0')",
      'paramTypes' => 'ssis'
    ];

    protected $removeJobQuery = [
      'sql' => "DELETE FROM `jobs` WHERE `job_owner` = ? AND `id` = ?",
      'paramTypes' => 'ii'
    ];

    protected $storeJobReportQuery = [
      'sql' => "INSERT INTO `reports` (`id`, `job_owner`, `task_id`, `report_date`, `gzipped`, `path`)
                VALUES (NULL, ?, ?, NOW(), '0', ?)",
      'paramTypes' => 'iss'
    ];

    protected $storeNewDeviceQuery = [
      'sql' => "INSERT INTO `client_devices` (`id`, `owner_id`, `target_id`, `device_label`, `avail_configs`)
                VALUES (NULL, ?, ?, ?, ?)",
      'paramTypes' => 'isss'
    ];

    protected $getUserIdByAppKeyQuery = [
      'sql' => "SELECT `id` FROM `clients` WHERE `app_key` = ?",
      'paramTypes' => 's'
    ];

    protected $getNearestJobsQuery = [
      'sql' => "SELECT `id`, `task_id`
                FROM `jobs`
                WHERE `job_owner` = ? AND `next_run` > NOW() AND `next_run` < ADDTIME(NOW(), '00:10:00')
                ORDER BY `next_run`",
      'paramTypes' => 'i'
    ];

    protected $getReportByIdQuery = [
      'sql' => "SELECT `id`, `seen`, `task_id`, `report_date`, `path`
                FROM `reports`
                WHERE `job_owner` = ?
                AND `id` = ?
                LIMIT 1",
      'paramTypes' => 'ii'
    ];

    protected $incJobNextRunQuery = [
      'sql' => "UPDATE `jobs`
                SET `next_run` = ADDTIME(NOW(), `run_interval`),
                `last_run` = NOW()
                WHERE `id` = ?",
      'paramTypes' => 'i'
    ];

    protected $storeNewUser = [
      'sql' => "INSERT INTO `clients` (`id`, `auth_hash`, `app_key`)
                VALUES (NULL, ?, ?)",
      'paramTypes' => 'ss'
    ];

    protected $storeNewUserMeta = [
      'sql' => "INSERT INTO `client_meta` (`id`, `client_id`, `first_name`, `last_name`, `business_name`, `email`, `avatar`, `max_devices`, `device_labels`)
                VALUES (NULL, ?, ?, ?, ?, ?, '', '5', '{\"0\":\"main\"}');",
      'paramTypes' => 'issss'
    ];

    protected $storeUserMeta = [
      'sql' => "UPDATE `client_meta`
                SET `first_name` = ?,
                    `last_name` = ?,
                    `business_name` = ?,
                    `email` = ?
                WHERE `client_meta`.`client_id` = ?;",
      'paramTypes' => 'ssssi'
    ];

    protected $storeDeviceCheckin = [
      'sql' => "UPDATE `client_devices`
                SET `last_checkin` = NOW()
                WHERE `owner_id` = ?",
      'paramTypes' => 'i'
    ];

    protected function connect(){
        $this->sql = new mysqli(
            $this->serverPath,
            $this->username,
            $this->password,
            $this->mainDb
        );

        if ($this->sql->connect_errno)
            die('Failed to connect to MySQL');

        $this->connected = true;
    }

    public function disconnect(){
        if (!$this->connected) return;

        $this->connected = false;
        $this->sql->close();
    }

    protected function runSql($query, $input, $getLID = false){
      if (!$this->connected) $this->connect();

      $state = $this->sql->stmt_init();

      $state = $this->sql->prepare($query['sql']);
      if (!$state) die('Could not prepare sql');

      if($input){
        $bind = array();
        foreach($input as $key => $val){
          $bind[$key] = &$input[$key];
        }

        array_unshift($bind, $query['paramTypes']);
        call_user_func_array(array($state, 'bind_param'), $bind);
      }

      if(!$state->execute()) die('Could not execute sql');

      if($getLID){
        return $state->insert_id;
      }

      $state->store_result();

      $variables = array();
      $data = array();
      $meta = $state->result_metadata();

      if(!$meta) return true;

      while($field = $meta->fetch_field())
        $variables[] = &$data[$field->name];

      call_user_func_array(array($state, 'bind_result'), $variables);

      $returnArray = array();
      $i = 0;
      while($state->fetch()){
        $returnArray[$i] = array();

        foreach($data as $k=>$v)
          $returnArray[$i][$k] = $v;

        $i++;
      }

      return $returnArray;
    }

    public function storeNewUser($user, $authHash, $firstName, $lastName, $bizName, $email, $appKey, $maxDevices, $deviceLabels){
      $newId = $this->runSql(
        $this->storeNewUser,
        [
          'authHash' => $authHash,
          'appKey' => $appKey,
        ],
        true
      );

      $this->updateUserMeta($newId, $firstName, $lastName, $bizName, $email, $maxDevices, $deviceLabels);

      return $newId;
    }

    public function updateUserMeta($cId, $fName, $lName, $bName, $email, $maxDevs, $devLbls){
      $userExists = (empty($this->getUserMeta($cId)['email']) ? false : true);

      if($userExists){
        $results = $this->runSql(
          $this->storeUserMeta,
          [
            'firstName' => $fName,
            'lastName' => $lName,
            'bizName' => $bName,
            'email' => $email,
            'clientId' => $cId,
          ]
        );
      }else{
        $results = $this->runSql(
          $this->storeNewUserMeta,
          [
            'clientId' => $cId,
            'firstName' => $fName,
            'lastName' => $lName,
            'bizName' => $bName,
            'email' => $email,
          ]
        );
      }

      return true;
    }

    public function getUserMeta($cid){
      $results = $this->runSql(
        $this->getUserMetaQuery,
        ['clientId' => $cid]
      );

      if(!count($results)) return false;

      return $results[0];
    }

    public function getUserById($cid){
      $results = $this->runSql(
        $this->getUserByIdQuery,
        ['clientId' => $cid]
      );

      if(!count($results)) return false;

      return $results[0];
    }

    public function getUserByHash($hash){
      $results = $this->runSql(
        $this->getUserByHashQuery,
        ['authHash' => $hash]
      );

      if(!count($results)) return false;

      return $results[0];
    }

    public function getClientDevices($cid){
      $results = $this->runSql(
        $this->getClientDevicesQuery,
        ['clientId' => $cid]
      );

      if(!count($results)) return false;

      return $results;
    }

    public function storeDeviceCheckin($cid){
      $results = $this->runSql(
        $this->storeDeviceCheckin,
        ['ownerId' => $cid]
      );

      return true;
    }

    public function getClientJobs($cid){
      $results = $this->runSql(
        $this->getClientJobsQuery,
        ['clientId' => $cid]
      );

      if(!count($results)) return false;

      return $results;
    }

    public function saveClientJob($jlbl, $tid, $cid, $runint){
      if(!$this->connected) $this->connect();
      $tid = $this->sql->escape_string($tid);
      $runint = $this->sql->escape_string($runint);

      $results = $this->runSql(
        $this->saveClientJobQuery,
        [
          'jobLabel' => $jlbl,
          'targetId' => $tid,
          'clientId' => $cid,
          'runInterval' => $runint,
        ]
      );

      return $results;
    }

    public function removeJob($cid, $jid){
      if(!$this->connected) $this->connect();
      $jid = $this->sql->escape_string($jid);

      $results = $this->runSql(
        $this->removeJobQuery,
        [
          'clientId' => $cid,
          'taskId' => $jid
        ]
      );

      return $results;
    }

    // api save report function //
    public function storeJobReport($cid, $tskid, $loc){
      if(!$this->connected) $this->connect();
      $tskid = $this->sql->escape_string($tskid);
      $loc = $this->sql->escape_string($loc);

      $results = $this->runSql(
        $this->storeJobReportQuery,
        [
          'clientId' => $cid,
          'taskId' => $tskid,
          'path' => $loc,
        ]
      );

      return $results;
    }

    public function storeNewDevice($cid, $tid, $delabel, $aconfs){
      if(!$this->connected) $this->connect();
      $tid = $this->sql->escape_string($tid);
      $delabel = $this->sql->escape_string($delabel);
      $aconfs = $this->sql->escape_string($aconfs);

      $results = $this->runSql(
        $this->storeNewDeviceQuery,
        [
          'clientId' => $cid,
          'targetId' => $tid,
          'deviceLabel' => $delabel,
          'availConfigs' => $aconfs,
        ]
      );

      return $results;
    }

    public function getUserIdByAppKey($key){
      if(!$this->connected) $this->connect();
      $key = $this->sql->escape_string($key);

      $results = $this->runSql(
        $this->getUserIdByAppKeyQuery,
        ['appKey' => $key]
      );

      if(!count($results)) return false;

      return $results[0];
    }

    public function getReportMeta($cid){
      $results = $this->runSql(
        $this->getReportMetaQuery,
        ['clientId' => $cid]
      );

      if(!count($results)) return false;

      return $results;
    }

    public function getReportById($cid, $rid){
      if(!$this->connected) $this->connect();
      $cid = $this->sql->escape_string($cid);
      $rid = $this->sql->escape_string($rid);

      $results = $this->runSql(
        $this->getReportByIdQuery,
        [
          'clientId' => $cid,
          'reportId' => $rid
        ]
      );

      if(!count($results)) return false;

      return $results[0];
    }

    // gets impending jobs //
    public function getNearestJobs($cid){
      $results = $this->runSql(
        $this->getNearestJobsQuery,
        ['clientId' => $cid]
      );

      if(!count($results)) return false;

      return $results;
    }

    public function incJobNextRun($jid){
      $results = $this->runSql(
        $this->incJobNextRunQuery,
        ['jobId' => $jid]
      );

      return $results;
    }
}

?>
