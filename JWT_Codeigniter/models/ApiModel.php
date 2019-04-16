<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiModel extends CI_Model 
{
	
	
	
	
	
	public function loginCleaner($mob,$pass){
		$pass=md5($pass);
		$this->db->select('cleaner_id,first_name,last_name,gender,mobile_number,profile_pic');
		$this->db->from('cleaner');
		$this->db->where('mobile_number',$mob);
		$this->db->where('pass',$pass);
		$res=$this->db->get()->result_array();
		return $res;
				
	}
	public function getCleanerDuty($cleaner_id,$date){
		//$this->db->select('*');
		$this->db->select('b.*,bk.general_notes,bk.specific_notes,bk.cleaning_material,bk.required_eqp,c.email as cust_email,c.first_name,c.last_name,cust.firstName as cust_fname,cust.lastname as cust_lname,cust.address1,cust.address2,cust.address3,cust.landmark,cust.villaAptNo,cust.villaOrFlat,cust.area,cust.city,cust.pin_code,cust.mobileNo,homePhone,cust.key');
			$this->db->from('booking_cleaner as b');
			$this->db->where('b.all_date', $date);
			$this->db->where('b.cleaner_id',$cleaner_id);
			$this->db->join('cleaner as c','c.cleaner_id= b.cleaner_id');
			$this->db->join('booking as bk','bk.booking_id= b.cleaner_booking_id');
			$this->db->join('customer as cust','cust.id= bk.customer_id');
			$res=$this->db->get()->result_array();
		return $res;
				
	}
   
   
   public function startCleanerDuty($cleaner_id,$date,$booking_cleaner_id){
		$arr=array('start_duty'=>date("h:i:s"),'app_status'=>'Start');
		$this->db->set($arr); //value that used to update column  
		$this->db->where('all_date', $date); //which row want to upgrade  
		$this->db->where('cleaner_id',$cleaner_id);
		$this->db->where('id',$booking_cleaner_id);
		//$this->db->where('publish','1');
		$this->db->update('booking_cleaner');
		return $res=$this->db->affected_rows();
	}

	public function stopCleanerDuty($cleaner_id,$date,$booking_cleaner_id){
		$arr=array('end_duty'=>date("h:i:s"),'app_status'=>'End');
		$this->db->set($arr); //value that used to update column  
		$this->db->where('all_date', $date); //which row want to upgrade  
		$this->db->where('cleaner_id',$cleaner_id);
		$this->db->where('id',$booking_cleaner_id);
		//$this->db->where('publish','1');
		$this->db->update('booking_cleaner');
		return $res=$this->db->affected_rows();
				
	}
	
	public function receiptUpdateStatus($cleaner_id,$date,$booking_cleaner_id){
		$arr=array('app_status'=>'Receipt');
		$this->db->set($arr); //value that used to update column  
		$this->db->where('all_date', $date); //which row want to upgrade  
		$this->db->where('cleaner_id',$cleaner_id);
		$this->db->where('id',$booking_cleaner_id);
		$this->db->update('booking_cleaner');
		return $res=$this->db->affected_rows();
				
	}

	public function receiptForm($cleaner_id,$date,$booking_cleaner_id){
		
		$this->db->select('b.*,bk.general_notes,bk.specific_notes,bk.cleaning_material,bk.required_eqp,c.email as cust_email,c.first_name,c.last_name,cust.firstName as cust_fname,cust.lastname as cust_lname,cust.address1,cust.address2,cust.address3,cust.landmark,cust.villaAptNo,cust.villaOrFlat,cust.area,cust.city,cust.pin_code,cust.mobileNo,homePhone,cust.key');
			$this->db->from('booking_cleaner as b');
			$this->db->where('b.all_date', $date);
			$this->db->where('b.cleaner_id',$cleaner_id);
			$this->db->where('b.id',$booking_cleaner_id);
			$this->db->join('cleaner as c','c.cleaner_id= b.cleaner_id');
			$this->db->join('booking as bk','bk.booking_id= b.cleaner_booking_id');
			$this->db->join('customer as cust','cust.id= bk.customer_id');
			$res=$this->db->get()->result_array();
		return $res;
		
				
	}public function collectBill($cleaner_id,$date,$booking_cleaner_id){
		
		$this->db->set('end_duty',date("h:i:s")); //value that used to update column  
		$this->db->where('all_date', $date); //which row want to upgrade  
		$this->db->where('cleaner_id',$cleaner_id);
		$this->db->where('id',$booking_cleaner_id);
		$this->db->update('booking_cleaner');
		return $res=$this->db->affected_rows();
		
				
	}
	
	public function publishBooking($date)
		{
			$this->db->set('publish','1'); //value that used to update column  
			$this->db->where('all_date', $date); //which row want to upgrade  
			$this->db->update('booking_cleaner'); 
						
		}
		
		
		
		public function publishedBookings($date){
			
			$this->db->select('b.*,bk.cleaning_material,bk.required_eqp,c.email as cust_email,c.first_name,c.last_name,cust.firstName as cust_fname,cust.lastname as cust_lname,cust.address1,cust.address2,cust.address3,cust.landmark,cust.villaAptNo,cust.villaOrFlat,cust.area,cust.city,cust.pin_code,cust.mobileNo,homePhone,cust.key');
			$this->db->from('booking_cleaner as b');
			$this->db->where('all_date', $date);
			$this->db->where('publish', '1');
			$this->db->join('cleaner as c','c.cleaner_id= b.cleaner_id');
			$this->db->join('booking as bk','bk.booking_id= b.cleaner_booking_id');
			$this->db->join('customer as cust','cust.id= bk.customer_id');
			$res=$this->db->get()->result_array();
			return $res;
					
			
		}
		
		

}
?>