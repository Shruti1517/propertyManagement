<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
require 'jwt_helper.php';
class JwtAuth {
	
  public function ValidateToken(){
	  
	 
  $secret_key = 'Y2xlYW5pbmcgbWFuYWdlbWVudCAxMjM0NTY3ODk=';
        $headers = getallheaders();
        if (array_key_exists('authorization', $headers)) {
            $jwt = $headers['authorization'];
            $token = JWT::decode($jwt, $secret_key);
            if ($token->exp >= time()) {
                //loggedin
                return $token->id;
            } else {
                http_response_code(401);
                return false;
            }
        } else {
            http_response_code(401);
            return false;
        }
  } 

  
     public function CreateToken($db_usr_id){
	  
	 
   
    $secret_key = 'Y2xlYW5pbmcgbWFuYWdlbWVudCAxMjM0NTY3ODk=';
    $valid_for = '86400';

       if($db_usr_id){
            $token = array();
            $token['id'] = $db_usr_id;
            $token['exp'] = time() + $valid_for;
            return array('token' => JWT::encode($token, $secret_key));
            return false;
        } else {
            http_response_code(401);
            return false;
        } 
  
  }  
}

 ?>