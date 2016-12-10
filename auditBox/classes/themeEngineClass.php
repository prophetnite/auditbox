<?php

class themeEngineClass{
  protected $sqlEngine;
  protected $themeName = 'default';
  protected $failedLogin = false;

  function __construct(){
    // import passed properties //
    $args = func_get_args();

    if(!empty($args))
      foreach($args[0] as $propName => $propData)
        if(property_exists($this, $propName))
          $this->{$propName} = $propData;
  }

  public function header(){
    require_once(__DIR__ . '/../theme/' . $this->themeName . '/header.html');
  }

  public function nav(){
    require_once(__DIR__ . '/../theme/' . $this->themeName . '/nav.html');
  }

  public function footer(){
    require_once(__DIR__ . '/../theme/' . $this->themeName . '/footer.html');
  }

  public function login($failedLogin){
    require_once(__DIR__ . '/../theme/' . $this->themeName . '/login.html');
  }

}

?>
