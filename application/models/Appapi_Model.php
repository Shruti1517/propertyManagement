<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appapi_Model extends CI_Model 
{
	public function addServiceRequest($data)
	{
		return $this->db->insert('service_request',$data);
	}
	
	public function getallservicerequest($id)
	{
		$this->db->select('*');
		$this->db->from('service_request');
		return $this->db->get()->result();
	}
	public function getServiceRequestLandlord($data)
	{
		$this->db->select('*');
		$this->db->from('service_request');
		$this->db->where('tenant_id',$data['tenant_id']);
		if($data['priority']!='all')
		{
			$this->db->where('priority',$data['priority']);
		}
		if($data['status']!='all')
		{
			$this->db->where('status',$data['status']);
		}
		$this->db->limit($data['limit'],$data['skip']);
		return $this->db->get()->result_array();
		 $this->db->last_query();
	}
	
	public function getSelectedServiceRequest($data)
	{
		$this->db->select('*');
		$this->db->from('service_request');
		$this->db->where('tenant_id',$data['tenant_id']);
		if($data['priority']!='all')
		{
			$this->db->where('priority',$data['priority']);
		}
		if($data['status']!='all')
		{
			$this->db->where('status',$data['status']);
		}
		$this->db->limit($data['limit'],$data['skip']);
		return $this->db->get()->result_array();
		 $this->db->last_query();
	}
	
	
	
	public function addtransaction($data)
	{
		 $this->db->insert('transaction',$data);
		return $this->db->insert_id();
	}
	
	public function additional_transaction($add)
	{
		return $this->db->insert_batch('additional_transaction',$add);
	}
	
	public function deduction_transaction($add)
	{
		return $this->db->insert_batch('deduction_transaction',$add);
	}
	
	public function allTransaction()
	{
		$this->db->select('*');
		$this->db->from('transaction');
		return $this->db->get()->result_array();
	}
	
	public function checkExistingUser($email)
	{
		$this->db->select('userName,role,id');
		$this->db->from('users');
		$this->db->where('email',$email);
		return $this->db->get()->result_array();
	}
	
	public function googleSignup($data)
	{
		 $this->db->insert('users',$data);
		return $this->db->insert_id();
	}
	
	public function getIncomeAndExpense($id)
	{
		$this->db->select('*');
		$this->db->from('transactions_type');
		$this->db->where('landlord_id',$id);
		return $this->db->get()->result_array();
		 $this->db->last_query();
	}	
	
	public function getAllVendors($id)
	{
		$this->db->select('*');
		$this->db->from('suppliers');
		$this->db->where('landlord_id',$id);
		return $this->db->get()->result_array();
	}
	
	public function getAllUnits($id)
	{
		$this->db->select('*');
		$this->db->from('units');
		$this->db->where('propertyId',$id);
		$this->db->where('lease_status',1);
		return $this->db->get()->result_array();
	}
	
	public function getAllUnitswithoutstatus($id)
	{
		$this->db->select('*');
		$this->db->from('units');
		$this->db->where('propertyId',$id);
		//$this->db->where('lease_status',1);
		return $this->db->get()->result_array();
	}
	
	public function getAllProperties($id)
	{
		$this->db->select('*');
		$this->db->from('properties');
		$this->db->where('landlord_id',$id);
		return $this->db->get()->result_array();
	}

	public function getTenant($id)
	{
		$this->db->select('userName,tenant_id');
		$this->db->from('lease_tenant');
		$this->db->join('users','lease_tenant.tenant_id=users.id');
		$this->db->where('unit_id',$id);
		return $this->db->get()->result_array();
	}
	
	public function viewtransaction($landlord_id)
	{
		$this->db->select('transaction.id,users.userName,vender_name,payment_date,transactions_type.type,properties.propertyName,totalAmount,payment_status');
		$this->db->from('transaction');
		$this->db->join('properties','transaction.property_id = properties.id','left');
		$this->db->join('users','transaction.tenant_id = users.id','left');
		$this->db->join('suppliers','transaction.vender_name = suppliers.id','left');
		$this->db->join('transactions_type','transaction.transaction_type = transactions_type.id','left');
		$this->db->where('transaction.landlord_id',$landlord_id);
		$this->db->order_by("transaction.id", "desc");
		return $this->db->get()->result_array();
	}
	
	public function viewTransactionDetails($id)
	{
		$this->db->select('userName,start_period,end_period,flatHoseNo,totalAmount,payment_type');
		$this->db->from('transaction');
		$this->db->join('users','transaction.tenant_id = users.id','left');
		$this->db->join('units','transaction.unit_id = units.id','left');
		//$this->db->join('deduction_transaction','transaction.id = deduction_transaction.id','left');
		//$this->db->join('additional_transaction','transaction.id = additional_transaction.id','left');
		$this->db->where('transaction.id',$id);
		return $this->db->get()->result_array();
	}
	
	public function getadditional_transaction($id)
	{
		$this->db->select('add_text,add_digit');
		$this->db->from('additional_transaction');
		$this->db->join('transaction','additional_transaction.transaction_id = transaction.id','left');
		$this->db->where('transaction.id',$id);
		return $this->db->get()->result_array();
	}

	public function getdeduction_transaction($id)
	{
		$this->db->select('remove_text,remove_digit');
		$this->db->from('deduction_transaction');
		$this->db->join('transaction','deduction_transaction.transaction_id = transaction.id','left');
		$this->db->where('transaction.id',$id);
		return $this->db->get()->result_array();
	}
	
	public function addTranctionType($data)
	{
		return $this->db->insert('transactions_type',$data);
	}
	
	public function getAllUnitdetails($id)
	{
		$sql="SELECT properties.propertyName,properties.country,properties.state,properties.city,units.flatHoseNo,units.id,users.userName,users.id as Tenant_id
		FROM properties  JOIN units ON units.propertyId = properties.id JOIN lease_tenant ON lease_tenant.unit_id
		= units.id JOIN users ON users.id = lease_tenant.tenant_id  where properties.id= $id  and lease_tenant.status=0 ";
		$query=$this->db->query($sql);
      return $query->result_array();
	}
	
	public function getupperrenge()
	{
		$this->db->select("max(rentAmount) as upperrenge");
		$this->db->from("add_lease");
		return $this->db->get()->result_array()[0];
	}
	
	public function searchProperty($data)
	{
		/* $sql='';
		$sql.= "select units.id,propertyName,streetName,city,state,pincode,property_img from properties join units on properties.id=units.propertyId join units on add_lease.unit_id=units.id where";
		
		if(isset($data['rentAmount']))
		{
			$sql.= "rentAmount<=$data['rentAmount'] AND";
		}
		
		if(isset($data['propertyType']) && $data['propertyType']!='both')
		{
			$sql.= "propertyType= $data['propertyType'] AND";
		}
		if(isset($data['unitType']))
		{
			$sql.= "unitType= $data['unitType'] AND";
		}
		if(isset($data['furnishing']))
		{
			$sql.= "furnishing= $data['furnishing']";
		}
			return $this->db->query($sql)->result_array(); */
		
		$this->db->select('flatHoseNo,onRent,units.id,propertyName,streetName,city,state,pincode,property_imges');
		$this->db->from('properties');
		$this->db->join('property_img','properties.id=property_img.property_id');
		$this->db->join('units','properties.id=units.propertyId');
		if(isset($data['rentAmount']))
		{
			$this->db->join('add_lease','add_lease.unit_id=units.id');
			$this->db->where('rentAmount<=',$data['rentAmount']);
			
		}
		if(isset($data['propertyType']) && $data['propertyType']!='both')
		{
			$this->db->where('propertyType',$data['propertyType']);
		}
		if(isset($data['unitType']) && $data['unitType']!='all')
		{
			$this->db->where('unitType',$data['unitType']);	
		}
		if(isset($data['furnishing']) && $data['furnishing']!='all' )
		{
			$this->db->where('furnishing',$data['furnishing']);	
		} 
		return $this->db->get()->result_array();
		
	}
	
	public function unitDetails($id)
	{
		$this->db->select('properties.id as propertyid,properties.landlord_id,units.id as unitid,flatHoseNo,furnishing,unitType,rentAmount,depositAmount,userName,streetName,city,state,pincode,landmark');
		$this->db->from('properties');
		$this->db->join('units','properties.id=units.propertyId','left');
		$this->db->join('users','users.id=properties.landlord_id','left');
		//$this->db->join('unit_img','unit_img.unit_id=units.id');
		$this->db->join('add_lease','units.id=add_lease.unit_id','left');
		$this->db->where('units.id',$id);
		$this->db->group_by('units.id');
		return $this->db->get()->result_array();
		 $this->db->last_query();
	}
	
	public function unitimg($id)
	{
		$this->db->select('home_img,unit_id');
		$this->db->from('unit_img');
		$this->db->where('unit_id',$id);
		return $this->db->get()->result_array();
	}
	
	public function requestFromLandlord($data)
	{
		return $this->db->insert('request',$data);
	}
	
	public function myhouse($id)
	{
		$this->db->select('landlord_id,units.id as unit_id,properties.id as propertyIdflatHoseNo,userName,onRent');
		$this->db->from('lease_tenant');
		$this->db->join('units','units.id=lease_tenant.unit_id');
		$this->db->join('properties','properties.id=units.propertyId');
		$this->db->join('users','users.id=properties.landlord_id');
		$this->db->where('tenant_id',$id);
		$this->db->where('lease_tenant.status',0);
		//$this->db->group_by('landlord_id');
		return $this->db->get()->result_array();
	}
	
	public function getCompletedRequest($id)
	{
		$this->db->select('service_request.id as servicereqID,start_date,due_date,userName,propertyName,flatHoseNo,priority');
		$this->db->from('service_request');
		$this->db->join('units','units.id=service_request.unit_id');
		$this->db->join('properties','properties.id=units.propertyId');
		$this->db->join('users','users.id=service_request.tenant_id');
		$this->db->where('service_request.tenant_id',$id);
		$this->db->where('service_request.status',1);
		return $this->db->get()->result_array();
	}	
	
	public function getPendingRequest($id)
	{
		$this->db->select('service_request.id as servicereqID,start_date,due_date,userName,propertyName,flatHoseNo,priority');
		$this->db->from('service_request');
		$this->db->join('units','units.id=service_request.unit_id');
		$this->db->join('properties','properties.id=units.propertyId');
		$this->db->join('users','users.id=service_request.tenant_id');
		$this->db->where('service_request.tenant_id',$id);
		$this->db->where('service_request.status',0);
		return $this->db->get()->result_array();
	}
	
	public function getDeclineRequest($id)
	{
		$this->db->select('service_request.id as servicereqID,start_date,due_date,userName,propertyName,flatHoseNo,priority');
		$this->db->from('service_request');
		$this->db->join('units','units.id=service_request.unit_id');
		$this->db->join('properties','properties.id=units.propertyId');
		$this->db->join('users','users.id=service_request.tenant_id');
		$this->db->where('service_request.tenant_id',$id);
		$this->db->where('service_request.status',2);
		return $this->db->get()->result_array();
	}
	
	public function showServiceRequest($id)
	{
		$this->db->select('title,userName,propertyName,flatHoseNo,start_date,due_date,priority,alert_status,service_request.status,notes');
		$this->db->from('service_request');
		$this->db->join('units','units.id=service_request.unit_id','left');
		$this->db->join('properties','properties.id=units.propertyId','left');
		$this->db->join('users','users.id=service_request.tenant_id','left');
		$this->db->join('suppliers','suppliers.id=service_request.vendor_id','left');
		$this->db->where('service_request.id',$id);
		return $this->db->get()->result_array();
	}
	
	public function ListLandlord()
	{
		$this->db->select('id,userName');
		$this->db->from('users');
		$this->db->where('role','landlord');
		return $this->db->get()->result_array();
	}
	
	public function propertyUnderLandlord($id)
	{
		$this->db->select('id,propertyName as value');
		$this->db->from('properties');
		$this->db->where('landlord_id',$id);
		return $this->db->get()->result_array();
	}
	
	public function requestDetails($id)
	{
		$this->db->select('request.id,propertyName,unitType,userName,request.status');
		$this->db->from('request');
		$this->db->join('properties','request.property_id=properties.id');
		$this->db->join('units','request.unit_id=units.id');
		$this->db->join('users','request.tenant_id=users.id');
		$this->db->where('request.landlord_id',$id);
		$this->db->where('request.status!=',3);
		$this->db->order_by('request.id','desc');
		return $this->db->get()->result_array();
	}
	
	public function updateRequestDetails($id,$status)
	{
		$this->db->where('id',$id);
		return $this->db->update('request',array('status'=>$status));
	}
  
}
?>