<?php
namespace Credential;
defined('BASEPATH') OR exit('No direct script access allowed');

class CredentialRequired {
  
  public function cekVariabel ($method, $data) {
    $hasil = [];
    
    if (method_exists($this, $method)) {
      foreach ($this->$method() as $key => $val) {
        if (!isset($data[$key])) {
          $hasil['code'] = 400;
          $hasil['error'][] = $key . ' is required (' . $val . ')';
        }
      }
    } else {
      $hasil['error'][] = 'Method not found';
      $hasil['code']    = 405;
    }

    return $hasil;
  }

  private function userList() {
    return [
      'key'   => 'string (max 256 char)',
    ];
  }

  private function user() {
    return [
      'key'   => 'string (max 256 char)',
      'user' => 'string (max 256 char)',
      'username' => 'string (max 50 char)',
      'password' => 'string (max 50 char)',
      'userKey' => 'string (max 50 char)',
      'secretKey' => 'string (max 50 char)',
      'consID' => 'string (max 50 char)',
      'production' => '1/0 true/false)'
    ];
  }

  private function delete() {
    return [
      'key'  => 'string',
      'user' => 'string (max 256 char)'
    ];
  }

  
  private function changeKey() {
    return [
      'key' => 'string (max 256 char)',
      'newKey' => 'string (max 256 char)'
    ];
  }


}
/* EOC */
