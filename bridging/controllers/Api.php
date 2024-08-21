<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

  private $_APP = 'Bridging-BPJS 0.9.5 Beta';
  private $verbose = FALSE;
  private $header  = 200;

  public function __construct() {
    parent::__construct();
    //$this->load->model('api_model');
    error_reporting(0);

    $logs = TRUE;
    if ($logs == TRUE) {
      $this->load->model('logs_model');
      $this->logs_model->logs();
    }
  }

  protected function isJson($json) {
    json_decode($json);
    return json_last_error() === JSON_ERROR_NONE;
  }

  protected function debug($teks = FALSE, $var1 = FALSE, $var2 = FALSE) {
    if ($this->verbose) {
      if ($teks)
        echo PHP_EOL . $teks . PHP_EOL;
      if ($var1)
        print_r($var1);
      if ($var2)
        print_r($var2);
    } else 
      return false;
  }

  protected function tampil($hasil) {
    $code = isset($hasil['code']) ? $hasil['code'] : $this->header;
    unset($hasil['code']);
    
    if (isset($hasil['error'])) {
      $output = [
        'code' => $code,
        'message' => $hasil['error'],
      ];
    } else {
      //$code = (isset($code) AND (substr($code,0,1) == '2')) ? $code : 200;
      if ($hasil) {
        $message = array('Ok');
        if (isset($hasil['message'])) {
          if (!is_array($hasil['message'])) {
            $message = array($hasil['message']);
          } else {
            $message = $hasil['message'];
          }
          unset($hasil['message']);
        };
        $output = [
          'code' => $code,
          'message' => $message
        ];
        if (count($hasil)) {
          $output['metadata'] = $hasil;
        }
      } else {
        $output = [
          'code' => $code,
          'message' => '',
          'metadata' => [
            'appName' => $this->_APP
          ]
        ];
      }
    }
    
    $this->output
    ->set_status_header($code)
    ->set_header('Access-Control-Allow-Origin: *')
    ->set_header('Access-Control-Allow-Methods: POST, GET')
    ->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With')
    ->set_content_type('application/json', 'utf-8')
    //->set_content_type('text/plain')
    ->set_output(json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
    ->_display();
    exit;
  }

  // Method: POST, PUT, GET etc
  public function CallAPI($method, $url, $head = false, $data = false, $debug = false) {
    $curl = curl_init();

    switch ($method) {
      case "POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        
        if ($data)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
      case "PUT":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POST, 1);
        //curl_setopt($curl, CURLOPT_PUT, 1);
        if ($data) {
          //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($data)));
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        break;
      case "DELETE":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if ($data) {
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        break;
      default:
        if ($data)
          $url = sprintf("%s?%s", $url, $data);
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    // Header tambahan
    if ($head) {
      if (!is_array($head)) $header = array($head);
      else $header = $head;
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);      
    }
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, $this->_APP);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 15); //timeout in seconds

    // Debuging
    if ($debug) {
      curl_setopt($curl, CURLOPT_HEADER, true);
      curl_setopt($curl, CURLOPT_VERBOSE, true);
      $verbose = fopen('php://temp', 'w+');
      curl_setopt($curl, CURLOPT_STDERR, $verbose);
    }
    $result = curl_exec($curl);
    $this->header = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if ($debug) {
      if ($result === FALSE) {
        printf("cUrl error (#%d): %s<br>\n", curl_errno($curl),
           htmlspecialchars(curl_error($curl)));
      }
      rewind($verbose);
      $verboseLog = stream_get_contents($verbose);
      echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
    }

    curl_close($curl);

    return $result;
  }
  
  public function token() {
    $this->load->helper('fungsi');
    $fp = fopen('php://input', 'r');
    parse_str(stream_get_contents($fp), $data);
    if (isset($data['user']) and ($data['user'] != '')) {
      $token = array('code'=> 201, 'token' => encdec($data['user'], TRUE));
    } else {
      $token['error'][] = 'user is required';
    }
    $this->tampil($token);
  }  
}   
/* END of controller */