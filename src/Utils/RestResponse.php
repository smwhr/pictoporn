<?php
namespace Utils;

class RestResponse{
  public $code;
  public $header;
  public $body;
  public $requested_uri;

  public function __construct($code, $header, $body){
    $this->code   = $code;
    $this->header = $header;
    $this->body   = $body;
  }
}