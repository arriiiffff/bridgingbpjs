<?php
namespace Users;
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses {
 
  public function user ($user = FALSE)
  {
    if (method_exists($this, $user))
      return $this->$user();
    else
      return false;
  }
  
  private function ayu () {
    $v['cons_id']   = "16744";
    $v['secretKey'] = "3vBD1B7C4C";
    $v['userkey']   = "061325e090343e8b0c7a75f3a563773b";
    //$v['username']  = "0137b156";
    //$v['password']  = "16Apr2023*";
    $v['username']  = "0137b156.tester";
    $v['password']  = "Qwerty1!";
    $v['kdApp']     = "095"; //pcare
    return $v;
  }

}
/* EOC */