<?php

class sqlEngineClass
{
    // sql config //
    protected $serverPath = 'localhost';
    protected $username = 'root';
    protected $password = '2Open4Me269$';
    protected $mainDb = 'auditbox';
    protected $sql;
    protected $connected = false;

    protected function connect()
    {
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

    public function disconnect()
    {
        if (!$this->connected) return;

        $this->connected = false;
        $this->sql->close();
    }

    public function getUserByHash($hash)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();
        $isPrepared = $state->prepare(
            "SELECT `clients`.`id`, `app_key`, `first_name`, `last_name`, `business_name`, `email`, `avatar`, `max_devices`, `device_labels`
      FROM `clients` LEFT JOIN `client_meta` ON `clients`.`id` = `client_meta`.`id`
      WHERE `clients`.`auth_hash` = ? LIMIT 1"
        );
        if (!$isPrepared) die('Could not prepare sql');
        if (!$state) die('Could not prepare sql');

        $state->bind_param('s', $authHash);
        $authHash = $hash;

        if (!$state->execute()) die('Could not execute sql');
        $data = ['id' => '', 'app_key' => '', 'first_name' => '', 'last_name' => '', 'business_name' => '', 'email' => '', 'avatar' => '', 'max_devices' => '', 'device_labels' => ''];
        $state->bind_result($data['id'], $data['app_key'], $data['first_name'], $data['last_name'], $data['business_name'], $data['email'], $data['avatar'], $data['max_devices'], $data['device_labels']);
        $state->fetch();
        $state->close();

        if (empty($data['id'])) return false;

        return $data;
    }

    public function getClientDevices($cid)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();
        $state = $this->sql->prepare(
            "SELECT `id`, `target_id`, `device_label`, `avail_configs`
      FROM `client_devices`
      WHERE `owner_id` = ?"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('i', $id);
        $id = $cid;

        if (!$state->execute()) die('Could not execute sql');
        $array = [];
        $hasNext = NULL;
        do {
            $data = ['id' => '', 'target_id' => '', 'device_label' => '', 'avail_configs' => ''];
            $state->bind_result($data['id'], $data['target_id'], $data['device_label'], $data['avail_configs']);
            array_push($array, $data);
        } while ($hasNext = $state->fetch());
        $state->close();

        if ($hasNext == false) return false;

        return $array;
    }

    public function getClientJobs($cid)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "SELECT `id`, `label`, `task_id`, `last_run`, `next_run`, `run_interval`, `run_once`
      FROM `jobs`
      WHERE `job_owner` = ?"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('i', $id);
        $id = $cid;

        if (!$state->execute()) die('Could not execute sql');

        $array = [];
        $hasNext = NULL;
        do {
            $data = ['id' => '', 'label' => '', 'task_id' => '', 'last_run' => '', 'next_run' => '', 'run_interval' => '', 'run_once' => ''];
            $state->bind_result($data['id'], $data['label'], $data['task_id'], $data['last_run'], $data['next_run'], $data['run_interval'], $data['run_once']);
            array_push($array, $data);
        } while ($hasNext = $state->fetch());
        $state->close();

        if ($hasNext == false) return false;

        return $array;
    }

    public function saveClientJob($jlbl, $tskid, $cid, $runint)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "INSERT INTO `jobs` (`id`, `label`, `task_id`, `job_owner`, `last_run`, `next_run`, `run_interval`, `run_once`)
      VALUES (NULL, ?, ?, ?, NOW(), ADDTIME(NOW(), '00:10:00'), ?, '0')"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('ssis', $jobLabel, $taskId, $clientId, $runInterval);
        $jobLabel = $jlbl;
        $clientId = $cid;
        $taskId = $this->sql->escape_string($tskid);
        $runInterval = $this->sql->escape_string($runint);

        if (!$state->execute()) die('Could not execute sql');

        $state->close();

        return true;
    }

    public function removeJob($cid, $jid)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "DELETE FROM `jobs` WHERE `job_owner` = ? AND `id` = ?"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('ii', $clientId, $jobId);
        $clientId = $cid;
        $jobId = $this->sql->escape_string($jid);

        if (!$state->execute()) die('Could not execute sql');

        $state->close();

        return true;
    }

    // api save report function //
    public function storeJobReport($cid, $tid, $data)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "INSERT INTO `reports` (`id`, `job_owner`, `task_id`, `report_date`, `gzipped`, `data`)
      VALUES (NULL, ?, ?, NOW(), '0', ?)"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('iss', $id, $taskId, $reportData);
        $id = $cid;
        $taskId = $this->sql->escape_string($tid);
        $reportData = $this->sql->escape_string($data);

        if (!$state->execute()) die('Could not execute sql');

        $state->close();

        return true;
    }

    public function storeNewDevice($oid, $tid, $delabel, $aconfs)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "INSERT INTO `client_devices` (`id`, `owner_id`, `target_id`, `device_label`, `avail_configs`)
      VALUES (NULL, ?, ?, ?, ?)"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('isss', $id, $targetId, $deviceLabel, $availConfigs);
        $id = $oid;
        $targetId = $this->sql->escape_string($tid);
        $deviceLabel = $this->sql->escape_string($delabel);
        $availConfigs = $this->sql->escape_string($aconfs);

        if (!$state->execute()) die('Could not execute sql');

        $state->close();

        return true;
    }

    public function getUserIdByAppKey($key)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "SELECT `id` FROM `clients` WHERE `app_key` = ?"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('s', $appKey);
        $appKey = $this->sql->escape_string($key);

        if (!$state->execute()) die('Could not execute sql');

        $state->bind_result();
        $res->fetch();
        $state->close();

        if (empty($data['id'])) return false;

        return $data;
    }

    public function getReportMeta($cid)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "SELECT `id`, `task_id`, `report_date`
      FROM `reports`
      WHERE `job_owner` = ?
      ORDER BY `report_date` DESC"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('i', $clientId);
        $clientId = $this->sql->escape_string($cid);

        if (!$state->execute()) die('Could not execute sql');

        $array = [];
        $hasNext = NULL;
        do {
            $data = ['id' => '', 'report_date' => '', 'task_id' => ''];
            $state->bind_result($data['id'], $data['task_id'], $data['report_date']);
            array_push($array, $data);
        } while ($hasNext = $state->fetch());
        $state->close();

        if ($hasNext == false) return false;

        return $array;
    }

    public function getReportById($cid, $rid)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "SELECT *
      FROM `reports`
      WHERE `job_owner` = ?
      AND `id` = ?
      LIMIT 1"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('ii', $clientId, $reportId);
        $clientId = $this->sql->escape_string($cid);
        $reportId = $this->sql->escape_string($rid);

        if (!$state->execute()) die('Could not execute sql');
        $data = '';
        $state->bind_result($data);
        $state->fetch();
        $state->close();

        if (empty($data)) return false;

        return $data;
    }

    // gets impending jobs //
    public function getNearestJobs($cid)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "SELECT `id`, `task_id`
      FROM `jobs`
      WHERE `job_owner` = ? AND `next_run` > NOW() AND `next_run` < ADDTIME(NOW(), '00:10:00')
      ORDER BY `next_run`
      LIMIT 1"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('i', $jobOwner);
        $jobOwner = $cid;

        if (!$state->execute()) die('Could not execute sql');

        $array = [];
        $hasNext = NULL;
        do {
            $data = ['id' => '', 'report_date' => '', 'task_id' => ''];
            $state->bind_result($data['id'], $data['task_id'], $data['report_date']);
            array_push($array, $data);
        } while ($hasNext = $state->fetch());
        $state->close();

        if ($hasNext == false) return false;

        return $array;
    }

    public function incJobNextRun($jid)
    {
        if (!$this->connected) $this->connect();
        $state = $this->sql->stmt_init();

        $state = $this->sql->prepare(
            "UPDATE `jobs`
      SET `next_run` = ADDTIME(NOW(), `run_interval`),
      `last_run` = NOW()
      WHERE `id` = ?"
        );
        if (!$state) die('Could not prepare sql');

        $state->bind_param('i', $id);
        $id = $jid;

        if (!$state->execute()) die('Could not execute sql');

        return true;
    }
}

?>
