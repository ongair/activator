<?php

  require 'vendor/autoload.php';   
  class TokenGenerator extends Registration {

    private $phone;
    private $number;

    public function __construct($number) {
      parent::__construct($number, false);
      
      $this->number = $number;
      $this->phone = $this->dissectPhone();
    }

    public function getIdentity() {
      return $this->identity;
    }

    public function getToken() {
      $code = $this->phone['cc'];
      return generateRequestToken($code, $this->number, 'Android');
    }
  }