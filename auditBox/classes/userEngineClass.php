<?php

class userEngineClass{
  // sql config //
  protected $sqlEngine;
  protected $loginPortal = 'login.php';
  protected $forcedLogoutSeconds = 1800;

  function __construct(){
    @session_start();

    // import passed properties //
    $args = func_get_args();

    if(!empty($args))
      foreach($args[0] as $propName => $propData)
        if(property_exists($this, $propName))
          $this->{$propName} = $propData;
  }

  public function addUser($user, $pass, $firstName, $lastName, $bizName, $email){
    $authHash = $this->genAuthHash($user, $pass);
    $newKey = $this->genApiKey();
    $maxDevices = 5;
    $deviceLabels = '{"0":"main"}';

    return $this->sqlEngine->storeNewUser($user, $authHash, $firstName, $lastName, $bizName, $email, $newKey, $maxDevices, $deviceLabels);
  }

  public function checkLogin($forceLogin = true){
    // if session not setup or no action for $forcedLogoutSeconds seconds //
    if(
        empty($_SESSION['id']) ||
        ($_SESSION['lastAction'] - $this->forcedLogoutSeconds) < 0
      ){
        if($forceLogin)
          $this->redirectToLogin();

        return false;
    }

    $_SESSION['lastAction'] = time();

    return true;
  }

  protected function redirectToLogin(){
    $this->clearLogin();
    die(header('Location: ' . $this->loginPortal));
  }

  public function clearLogin(){
    // likely will be updated to disclude username //
    session_unset();
  }

  //  //
  public function attemptLogin($user, $pass){
    $userData = $this->sqlEngine->getUserByHash($this->genAuthHash($user, $pass));

    if(!$userData) return false;

    foreach($userData as $dataName => $data){
      $_SESSION[$dataName] = $data;
    }

    $_SESSION['lastAction'] = time();

    return true;
  }

  // creates an api key for use in account creation //
  protected function genApiKey(){
    $st1 = md5(openssl_random_pseudo_bytes(64));
    return $st1 . md5(openssl_random_pseudo_bytes(64));
  }

  // creates the authentication hash for supplied user login //
  protected function genAuthHash($user, $pass){
    // in-house hashing functions are more secure //
    // this one could be improved //
    $st1 = md5($user . 'simple_salt_is_simple' . $pass);
    return $st1 . md5($user . $st1 . $pass);
  }

  // gets specific session data of currently logged in user //
  public function getUser($info){
    if(empty($_SESSION[$info])) return NULL;

    $sData = @json_decode($_SESSION[$info]);
    if($sData !== NULL)
      return $sData;

    return $_SESSION[$info];
  }

  // gets the clients device list & info //
  public function getClientDevices(){
    $deviceData = $this->sqlEngine->getClientDevices($this->getUser('id'));

    if(!$deviceData) return false;

    return $deviceData;
  }


  // API FUNCTIONS //

  // resolve appKey to clientID //
  public function getUserIdByAppKey($appKey){
    $clientId = $this->sqlEngine->getUserIdByAppKey($appKey);

    if(!$clientId) return false;

    return $clientId['id'];
  }

  // get upcoming jobs //
  public function getNearestJobs($cid){
    $jobs = $this->sqlEngine->getNearestJobs($cid);

    if(!$jobs) return false;

    return $jobs;
  }

  public function incNearestJobs($cid){
    $clientId = $this->sqlEngine->getUserIdByAppKey($appKey);

    if(!$clientId) return false;

    return $clientId[0]['id'];
  }
}

?>
