<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/CredentialRequired.php");
include_once (dirname(__FILE__) . "/Api.php");

use Credential\CredentialRequired;

class Credential extends Api {
      
  private $data   = FALSE;

  public function __construct() {
    parent::__construct();
    $this->load->helper('fungsi');
    $this->load->database();
    $method  = $this->input->server('REQUEST_METHOD');
    
    if ($method == 'POST' or $method == 'DELETE' or $method == 'PUT')  {
      $fp = fopen('php://input', 'r');
      parse_str(stream_get_contents($fp), $this->data);
      if ($this->data['key']) {
        $key = $this->db
                    ->get_where('credential', ['user'=> 'WD', 'username'=> $this->data['key']])
                    ->result_array();
      }
      if (!isset($key) OR count($key) != 1) {
        $hasil['error'][] = 'Unauthorized';
        $hasil['code']    = 401;
        $this->tampil($hasil);
        exit;
      }
    } else {
      $hasil['code']    = 405;
      $hasil['error'][] = 'METHOD NOT ALLOWED - USE POST METHOD';
      $this->tampil($hasil);
      exit;
    }
  }

  public function index ($method = FALSE)
  {
    if ($method == FALSE) {
      $this->tampil([]);
      exit;
    }

    $hasil       = false;
    $required    = new CredentialRequired;
    $cekVariabel = $required->cekVariabel($method, $this->data);
    if (isset($cekVariabel['error'][0])) {
      $this->tampil($cekVariabel);
      exit;
    }

    if (count($cekVariabel) == 0) {
      $hasil  = $this->$method();
    }

    $this->tampil($hasil);
  }

	private function userList() {
    $r = false;
    $this->db->select('user,username,password,userkey as userKey,secretKey,cons_id as consID, production, last_change as lastChange');
    $query = $this->db->get_where('credential', ['user !=' => 'WD']);
    if ($query->num_rows() >= 1) {
      $r['count'] = $query->num_rows();
      $r['list'] = $query->result_array();
      $r['code'] = 200;
    } elseif ($query->num_rows() >= 0) {
      $r = ['code' => 200, 'empty result'];
    } else {
      $r = ['code' => 400, 'Bad request'];      
    }
    return $r;
	}

	private function user() {
   $r = false;
   $query = "INSERT INTO credential (user, username, password, userkey, secretKey, cons_id, production, kdApp) VALUES (?, ?, ?, ?, ?, ?, ?, '095')
     ON DUPLICATE KEY UPDATE user = ?, username = ?, password = ?, userkey = ?, secretKey = ?, cons_id = ?, production = ?, kdApp = '095'";
    $this->db->query($query, [
      $this->data['user'],
      $this->data['username'],
      $this->data['password'],
      $this->data['userKey'],
      $this->data['secretKey'],
      $this->data['consID'],
      $this->data['production'],
      $this->data['user'],
      $this->data['username'],
      $this->data['password'],
      $this->data['userKey'],
      $this->data['secretKey'],
      $this->data['consID'],
      $this->data['production']
    ]);
    
    if ($this->db->affected_rows()) {
      $r = [
        'code' => 200,
        'Insert/update success for user ' . $this->data['user']
      ];
    } else {
      $r = [
        'code' => 200,
        'No rows affected for user ' . $this->data['user']
      ];
    }
    return $r;
	}

	private function delete() {
    $r = false;
    $query = $this->db->delete('credential', ['user' => $this->data['user']]);
    if ($this->db->affected_rows()) {
      $r = [
        'code' => 200,
        'Delete success for user ' . $this->data['user']
      ];
    } else {
      $r = [
        'code' => 200,
        'No rows affected for user ' . $this->data['user']
      ];
    }
    return $r;
	}

	private function changeKey() {
    $r = false;

    $this->db->where('user', 'WD');
    $this->db->update('credential', ['username' => $this->data['newKey']]);
    if ($this->db->affected_rows()) {
      $r = [
        'code' => 200,
        'Success, the key has been changed'
      ];
    } else {
      $r = [
        'code' => 200,
        'No rows affected'
      ];
    }
    return $r;
	}



}
/* EOC */