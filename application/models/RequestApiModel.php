<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RequestApiModel extends CI_Model{
	public function get_request_dataModel(){
		$q="SELECT properties.propertyName,users.userName,request.status,units.unitType,
		users.email,users.phone,request.id FROM   `request` 
		inner join units on  request.unit_id=units.id	
		inner join properties on properties.id=request.property_id
		inner join users on users.id=request.tenant_id where request.status != 3 ";		
		if (!$data=$this->db->query($q)) {
			$error = $this->db->error();
			throw new Exception('model_name->record: ' . $error['code'] . ' ' . $error['message']);
		}
		return $data->result_array();
	}
	public function accept_decline_request_statusModel($id,$status){
		if($status == 'accept'){
			$acc_status=1;
			$res="update request inner join users on request.tenant_id=users.id set request.status=$acc_status, users.addedBy='Landlord', users.landlordId=request.landlord_id where request.id=$id";
			if (!$query=$this->db->query($res)) {
				$error = $this->db->error();
				throw new Exception('model_name->record: ' . $error['code'] . ' ' . $error['message']);
			}
			$response=array('status'=>$query);
			return $response;
		}
		else if($status == 'decline'){
			$dec_status=array('status'=>2);
			$this->db->where('id',$id);
			$result=$this->db->update('request',$dec_status);
			if (!$result) {
				$error = $this->db->error();
				throw new Exception('model_name->record: ' . $error['code'] . ' ' . $error['message']);
			}
			$response=array('status'=>$result);
			return $response;		
		}
	}
	public function searchRequestDataModel($searchNames,$status){
		if($status ==='Accepted'){
			$statusValue=1;
		}else if($status ==='Declined'){
			$statusValue=2;
		}else if($status ==='Pending'){
			$statusValue=0;
		}

		$query="SELECT properties.propertyName,users.userName,request.status,units.unitType,
		users.email,users.phone,request.id FROM   `request` 
		inner join units on  request.unit_id=units.id	
		inner join properties on properties.id=request.property_id
		inner join users on users.id=request.tenant_id where   ";
		
		
		if(isset($statusValue) && !isset($searchNames)){
			$query .= "request.status=$statusValue";
		}
		else if(isset($searchNames) && !isset($statusValue)){
			$query .="properties.propertyName like '%$searchNames%'or users.userName like 
			'%$searchNames%'";
		}
		else if(isset($searchNames) && isset($statusValue)){
			$query .="(properties.propertyName like '%$searchNames%' or users.userName like 
			'%$searchNames%') AND request.status=$statusValue";
		}
		if (!$query_res = $this->db->query($query)) {
			$error = $this->db->error();
			throw new Exception('model_name->record: ' . $error['code'] . ' ' . $error['message']);
		}
		if(!!$query_res)
			return $query_res->result_array();
		
	}
	public function deleteRequestStatusConfiramtionModel($id,$Changestatus){
		if($Changestatus == 2){
			$update="update request set request.status=3 where request.id=$id";
        //return $update;
		}
		else if($Changestatus == 1){
			$update="update request inner join users on request.tenant_id=users.id set  request.status=3, users.addedBy=NULL,users.landlordId=NULL where request.id=$id";
         //return $update;
		}
		if (!$query=$this->db->query($update)) {
			$error = $this->db->error();
			throw new Exception('model_name->record: ' . $error['code'] . ' ' . $error['message']);
		}
		if(!!$query)
			return $query;
	}
}
?>
