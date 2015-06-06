<?php
namespace Utils;

class RestConnector{

  public $endpoint;
  private $key = null;

  public function __construct($app){
    $this->endpoint = $app['config']['hubtraffic.options']['host'];
    $this->key = $app['config']['hubtraffic.options']['key'];
  }

  public function query($method, $path, $parameters = array(), $data = ""){

    $ch = curl_init();
    if(!is_null($this->key)){
      $parameters['key'] = $this->key;
    }
    
    $url = $this->endpoint.$path;

    if(!empty($parameters)){
      $query = http_build_query($parameters,'', '&', PHP_QUERY_RFC3986);
      $url .= "?".$query;
    }

    $url = $url;

    switch($method){
      case "PUT":
      case "POST":
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($data),
                                                   'Content-Type: application/json'
                                                   )
                    ); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
      case "GET":
        break;
      case "DELETE":
        break;
    }

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    $response = curl_exec($ch);


    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    curl_close($ch);

    $return = new RestResponse($httpcode, $this->parse_headers($header), $body);
    $return->requested_uri = $url;

    return $return;
  }

  private function parse_headers($header_text){
    $headers = array();

    foreach (explode("\r\n", $header_text) as $i => $line){
        if(empty($line)) continue;

        if ($i === 0){
            $headers['Response-Code'] = $line;
        }else
        {
            list ($key, $value) = explode(': ', $line, 2);
            if(empty($key)) continue;
            $headers[$key] = $value;
        }
    }

    return $headers;
  }
  
}