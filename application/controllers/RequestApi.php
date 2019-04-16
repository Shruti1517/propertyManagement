<?php
defined('BASEPATH') OR exit('No direct script access allowed');		
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400'); 
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");         
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  exit(0);
}		
class RequestApi extends CI_Controller {
  function __construct() {
   parent::__construct();
   $this->load->library(array('JwtAuth','form_validation','MyFuncationLab'));
   $this->load->model('RequestApiModel');
   $this->load->helper(array('form', 'url'));
   $this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
 }
 /* --Get request data list-- */
 public function get_request_data(){
   try{
     $result=$this->RequestApiModel->get_request_dataModel();
     echo json_encode($result);
   } catch  (Exception $e) {
     log_message('error', $e->getMessage());
     echo 'ERROR : ',  'Something went wrong !!!!'.$e->getMessage(), "\n";
   } 	
 }
 /* --Update status according to the user input-- start*/
 public function accept_decline_request_status(){
  try{
   $info =(array) json_decode(file_get_contents("php://input"),TRUE);
   $id=$info['id'];
   $status=$info['status'];
   $result=$this->RequestApiModel->accept_decline_request_statusModel($id,$status);
   echo json_encode($result);  
 }catch(Exception $e) {
   return log_message('error', $e->getMessage());
   echo 'ERROR : ',  'Something went wrong !!!!'.$e->getMessage(), "\n";
 }       
}
 /* --Get filters applied request data list-- */
public function searchRequestData(){
  try{
   $info =(array) json_decode(file_get_contents("php://input"),TRUE);
   $result=$this->RequestApiModel->searchRequestDataModel($info['searchData'],$info['optionSelect']);
   echo json_encode($result);
 }catch(Exception $e) {
  log_message('error', $e->getMessage());
  echo 'ERROR : ',  'Something went wrong !!!!'.$e->getMessage(), "\n";
}       
}
/* --Update the status code to 3 for delete-- */
public function deleteRequestStatusConfiramtion(){
  try{
   $info =(array) json_decode(file_get_contents("php://input"),TRUE);
   $result=$this->RequestApiModel->deleteRequestStatusConfiramtionModel($info['id'],
    $info['status']);
   echo json_encode($result);
 }catch(Exception $e) {
  log_message('error', $e->getMessage());
  echo 'ERROR : ',  'Something went wrong !!!!'.$e->getMessage(), "\n";
}       
}
}
?>
