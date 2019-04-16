<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	
	
	 function __construct() {
        parent::__construct();
        $this->load->library('jwtauth');
			$this->load->model('apimodel');
	 }
	 
    
	
	
	public function checklogin(){
	
		$token=$this->jwtauth->ValidateToken();
		
		if(!empty($token)){
			return $token;
		}else{
			$res=array('msg'=>'error','errorInfo'=>'Token Not Valid ');
		    echo json_encode($res);
			http_response_code(401);
			return false;
		}
	 }   
	 
	 public function requiredParameter(){
		 
		 $res=array('msg'=>'error','errorInfo'=>'Please add required parameter to this web api  ');
		 echo json_encode($res);
		 http_response_code(400); 	
		 return false;			
		 
	 }
	
	/**
	  *
	  *--- Login Api and create token ----
	  *
	  **/
	
	public function login(){
    	 
	     $mobile_number = $this->input->post('mobile_number');
         $pass =$this->input->post('pass');
			//$mobile_number='8885737203';
		  // $pass='123456';	
				  
			if(!empty($mobile_number) && !empty($pass)){
				
				$res=$this->apimodel->loginCleaner($mobile_number,$pass);

					 if (!empty($res)) {
							$token=$this->jwtauth->CreateToken($res[0]['cleaner_id']);
							$token['userinfo']=$res[0];
							$token['msg']='success';
							echo json_encode($token);
							
							
						}else{
							$token=array('msg'=>'error','errorInfo'=>'invalid credential');
							echo json_encode($token);
							http_response_code(401);
							return false;
							
						} 
				  }else{
					 $token=array('msg'=>'error','errorInfo'=>'Enter mobile number and pass');
					 echo json_encode($token);
					 http_response_code(400); 
					  
				  }    
		
	}	
    	
	/**
	  *
	  *---  Get today cleaner duty's  ----
	  *
	  **/ 
    Public function getCleanerDuty(){
	/* 	$res=$this->apimodel->getCleanerDuty(2,date('Y-m-d'));
		echo $this->db->last_query();
		echo "<pre>";print_r($res);	die(); */
		
		
		 $userId=$this->checklogin();
		
		if($userId){
			//echo$token;
		    $res=$this->apimodel->getCleanerDuty($userId,date('Y-m-d'));
			
		    $data=array('userData'=>$res,'msg'=>'success');
		    echo json_encode($data);
			http_response_code(200);
		}
		
	}  
	
	
	
 public function banklist(){
	 
	return $arr=array(
	          "First Abu Dhabi Bank (FAB)",
	          "Emirates NBD","Abu Dhabi Commercial Bank","Dubai Islamic Bank","MashreqBank","Abu Dhabi Islamic Bank (ADIB)","HSBC Bank Middle East - UAE Operations","Union National Bank","Commercial Bank of Dubai (CBD)","Emirates Islamic Bank","National Bank of Ras Al Khaimah (RAKBANK)","Al Hilal Bank","Noor Bank","Sharjah Islamic Bank","National Bank of Fujairah"
	 
	      );
	 
	 
 }

}
