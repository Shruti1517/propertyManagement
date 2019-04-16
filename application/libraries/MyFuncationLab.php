<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class MyFuncationLab {
	
  
function randString(){
     $token = "";
     $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
     $codeAlphabet.= "0123456789";
     $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < 20; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
  }  
}

 ?>