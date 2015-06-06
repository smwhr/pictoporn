<?php
namespace Pictoporn;

use Utils\RestConnector;

class HubtrafficConnector extends RestConnector{

  public function searchVideos($parameters){

    $parameters['thumbsize']='small';

    $rawres = $this->query('GET', '/search', $parameters);
    $res = json_decode($rawres->body, true);

    if(
        !isset($res['videos'])
      ){
      return [];
    }

    return $res['videos'];

  }
  
}