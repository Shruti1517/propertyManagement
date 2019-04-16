<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiModel extends CI_Model 
{
	
	public function deleteData($tblName,$cond){
		
		 $this->db->where($cond);
        $this->db->delete($tblName);
	}
  
  
   public function updateTableData($arr,$con,$tbl){
	  
	    $this->db->set($arr); //value that used to update column  
		$this->db->where($con);
		$this->db->update($tbl);
		return $res=$this->db->affected_rows();
	  
  } 

  public function insert_multi_img($tbl_name,$data)
  {
      return $this->db->insert_batch($tbl_name,$data);
  }

  public function updateData($arr,$id){
	  
	    $this->db->set($arr); //value that used to update column  
		$this->db->where('id',$id);
		$this->db->update('users');
		return $res=$this->db->affected_rows();
	  
  }  
  public function getDateFormTable($tblName,$cond ){
	  
	  $this->db->select('*');
	  $this->db->from($tblName);
	  $this->db->where($cond);
	  $res=$this->db->get()->result_array();
	  return $res;
	       
  }
  public function get_unit_img($id)
  {
    $this->db->select('*');
    $this->db->from('unit_img');
    $this->db->where('unit_id',$id);
    return $this->db->get()->result_array();
  }

  public function getDateFormProperties($userId)
  {
    $this->db->select('properties.*,property_img.property_imges');
    $this->db->from('properties');
    $this->db->join('property_img','properties.id = property_img.property_id');
    $this->db->where('properties.landlord_id',$userId);
    $res=$this->db->get()->result_array();
    return $res;
    
  }

  public function check_google_signup($email)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('email',$email);
    return $this->db->get()->result_array();
  }
  public function  insertData($tblName,$data){
	  $this->db->insert($tblName, $data);
	  
	 	return $this->db->affected_rows();
	  
  }	
  public function insert_new_Data($data)
  {
     $this->db->insert('properties', $data); 
     return $this->db->insert_id();
  }
	public function insert_Data($table_name,$data)
  {
     $this->db->insert($table_name, $data); 
     return $this->db->insert_id();
  }
	public function login($phoneOrEmail,$pass){
		$pass=md5($pass);
		$this->db->select('userName,email,phone,role,id,country,address,profilephoto,onLease');
		$this->db->from('users');
		$this->db->where('email',$phoneOrEmail);
		$this->db->where('pass',$pass);
		//$this->db->or_where('phone',$phoneOrEmail);
	
		$res=$this->db->get()->result_array();
		return $res;
				
	}

  public function userActive($email,$pass)
  {  $pass=md5($pass);
    $this->db->where('email',$email);
    $this->db->where('pass',$pass);
   return  $this->db->update('users',array('status'=>'active'));
 

  }
   public function checkLogin($phoneOrEmail,$pass)
  {
    $pass=md5($pass);
    $this->db->select('userName,email,phone,role,id,country,address,profilephoto,status');
    $this->db->from('users');
    $this->db->where('email',$phoneOrEmail);
    $this->db->where('pass',$pass);
    $this->db->where('status','active');
  
    $res=$this->db->get()->result_array();
    return $res;
        
  }
  public function getLimitedData($tbl,$select,$con){
	  
	   $this->db->select($select);
	   $this->db->from($tbl);
	   $this->db->where($con);
	   $res=$this->db->get()->result_array();
		return $res;
	  
  }

  public function getProfilePicture($userId)
  {
  	$this->db->select('profilephoto');
	   $this->db->from('users');
	   $this->db->where('id',$userId);
	   $res=$this->db->get()->result_array();
		return $res;
  	
  }


  public function insert_unitData($alldata)
  {
  	 $this->db->insert('units',$alldata);
  	 return $this->db->insert_id();
  }

  public function get_document($id)
  {
  	$this->db->select('*');
  	$this->db->from('unit_data');
  	$this->db->where('unit_id',$id);
  	return $this->db->get()->result_array();
  }

  public function get_unit_data($property_id)
  {
  	$this->db->select('units.*,properties.propertyName,lease_tenant.tenant_id');
  	$this->db->from('units');
  	$this->db->join('properties','properties.id = units.propertyId', 'left');
    $this->db->join('lease_tenant','units.id = lease_tenant.unit_id', 'left');
     $this->db->group_by('units.id');
  	$this->db->where('units.propertyId',$property_id);

  	return $this->db->get()->result_array();

  }

  public function get_all_Images($unit_id)
  {
  	$this->db->select('*');
  	$this->db->from('unit_img');
  	$this->db->where('unit_id',$unit_id);
  	return $this->db->get()->result_array();

  }

  public function delete_home_image($id)
  {
  	$this->db->where('id',$id);
  	return $this->db->delete('unit_img');
  }
  public function save_unit_img($data)
  {
  	    return  $this->db->insert('unit_img',$data);
  	
  }

  public function delete_Dcoument_data($id)
  {
  	$this->db->where('id',$id);
  	return $this->db->delete('document');
  }

  public function insert_All_data($alldata)
  {
  	return $this->db->insert('add_lease',$alldata);
  }
  
  public function update_unit_status($unit_id,$data)
  {
  	$this->db->where('id',$unit_id);
  	$this->db->update('units',$data);
  }
  
  public function get_edit_leaves_data($id)
  {
  	$this->db->select('*');
  	$this->db->from('add_lease');
  	$this->db->where('unit_id',$id);
  	return $this->db->get()->result_array();
  }

  public function update_All_data($id,$alldata)
  {
  	$this->db->where('unit_id',$id);
  	return $this->db->update('add_lease',$alldata);
  }

  public function link_unit_tenants($unit_id,$tenant_id)
  {
  	$this->db->where('unit_id',$unit_id);
  	$this->db->update('add_lease',array('onLease'=>'Yes'));
  	$this->db->where('id',$unit_id);
  	$this->db->update('units',array('onRent'=>'Yes'));
  	$this->db->where('id',$tenant_id);
  	$this->db->update('users',array('onLease'=>'Yes'));
  	return $this->db->insert('lease_tenant',array('unit_id'=>$unit_id,'tenant_id'=>$tenant_id,'start_date'=>Date('Y-m-d')));
  }

  public function checkTenantAdd($tenant_id)
  {
    $this->db->select('*');
    $this->db->from('lease_tenant');
    $this->db->where('tenant_id',$tenant_id);
    $this->db->where('status',0);
    return $this->db->get()->num_rows();
  }
  public function checkLease($unit_id)
  {
    $this->db->select('*');
    $this->db->from('units');
    $this->db->where('id',$unit_id);
    $this->db->where('lease_status',1);
    return $this->db->get()->num_rows();
  }
  public function remove_link_tenant($id)
  {
  	$this->db->where('tenant_id',$id);
  	$this->db->where('end_date','0000-00-00');
  	$this->db->update('lease_tenant',array('status'=>'1','end_date'=>Date('Y-m-d')));
  	$this->db->where('id',$id);
  	return $this->db->update('users',array('onLease'=>'No'));
  }

  public function get_property_data($id)
  {
  	$this->db->select('properties.*,countries.name');
  	$this->db->from('properties');
  	$this->db->join('countries','properties.country = countries.id');
  	$this->db->where('properties.id',$id);
  	return $this->db->get()->result_array();
  }

  public function editPropertyData($id,$res)
  {
  	$this->db->where('id',$id);
  	return $this->db->update('properties',$res);
  }

  public function get_search_data($userId,$search_value)
  {
  	  $sql="SELECT * FROM properties WHERE landlord_id = $userId AND ( id LIKE '$search_value%' || propertyName LIKE '$search_value%' || propertyType LIKE '$search_value%' || country LIKE '$search_value%' || state LIKE '$search_value%' )";
  	$query=$this->db->query($sql);
  	return $query->result_array();
 //  	$this->db->select('*');
	// $this->db->from('properties');
	// $this->db->where('landlord_id',$userId);
	// $this->db->like('id',$search_value);
	// $this->db->or_like('propertyName',$search_value);
	// $this->db->or_like('propertyType',$search_value);
	// $this->db->or_like('country',$search_value);
	// $this->db->or_like('state',$search_value);
	// return $this->db->get()->result_array();
  }

  public function get_unit_data_id($id)
  {
  	$this->db->select('*');
  	$this->db->from('units');
  	$this->db->where('id',$id);
  	return $this->db->get()->result_array();
  }

  public function get_tenant_data_id($id)
  {
  	$this->db->select('*');
  	$this->db->from('users');
  	$this->db->where('id',$id);
  	return $this->db->get()->result_array();
  }

  public function upadte_data($id,$email,$mobile)
  {
  	$sql="SELECT * FROM `users` WHERE id !=$id AND (email= '$email' OR phone = '$mobile')";
  	$query = $this->db->query($sql);
  
  	return $query->num_rows();

  }
	
  public function tenant_update_data($id,$alldata)
  {
  	$this->db->where('id',$id);
  	return $this->db->update('users',$alldata);
  }

  public function deleteTenant($id)
  {
  	$this->db->where('id',$id);
  	return $this->db->delete('users');
  }

  public function get_lease_data($landlord_id,$whereCondition)
   {
  // 	$this->db->select('properties.*,units.*,users.id as tenant_id,users.userName,users.phone,users.email,add_lease.rentAmount,add_lease.startDate,add_lease.endDate');
  // 	$this->db->from('properties');
  // 	$this->db->join('units','units.propertyId = properties.id');
  // 	$this->db->join('add_lease','add_lease.unit_id = units.id');
  // 	$this->db->join('lease_tenant','lease_tenant.unit_id =  units.id');
  // 	$this->db->join('users','users.id = lease_tenant.tenant_id');
  // 	$this->db->where('properties.landlord_id',$landlord_id);
  // 	$this->db->where('lease_tenant.status',0);
  // 	return $this->db->get()->result_array();
 $sql="select properties.*,units.*,users.id as tenant_id,users.userName,users.phone,users.email,add_lease.rentAmount,add_lease.startDate,add_lease.endDate from properties inner join units ON units.propertyId = properties.id inner join add_lease ON add_lease.unit_id = units.id inner join lease_tenant ON lease_tenant.unit_id =  units.id inner join users ON users.id = lease_tenant.tenant_id Where properties.landlord_id=$landlord_id AND lease_tenant.status = 0 $whereCondition";
 $query=$this->db->query($sql);
 return $query->result_array();

  }
	
  public function get_landlord_stafesData($id)
  {
    $this->db->select('*');
    $this->db->from('land_staff');
    $this->db->where('id',$id);
    return $this->db->get()->result_array();


  }

  public function save_landlord_staff($id,$alldata)
  {
    $this->db->where('id',$id);
    return $this->db->update('land_staff',$alldata);
  }

  public function delete_landlord_staff($id)
  {
    $this->db->where('id',$id);
    return $this->db->delete('land_staff');
  }

  public function getPropertiesData($userId)
  {
    $this->db->select('properties.propertyName,countries.name,properties.propertyType,properties.streetName,properties.city,properties.state,properties.landmark,properties.pincode,properties.suburbs,properties.notes');
    $this->db->from('properties');
    $this->db->join('countries','countries.id = properties.country');
    $this->db->where('landlord_id',$userId);
    return $this->db->get()->result_array();
  }

  public function add_new_designation($alldata)
  {
  return $this->db->insert('designation',$alldata);
  }

  public function get_all_designation($userId)
  {
    $this->db->select('*');
    $this->db->from('designation');
    $this->db->where('landlord_id',$userId);
    return $this->db->get()->result_array();
  }

  public function add_new_suppliers($data)
  {
    return $this->db->insert('suppliers',$data);
  }

  public function get_suppliers_list($userId)
  {
    $this->db->select('*');
    $this->db->from('suppliers');
    $this->db->where('property_id',$userId);
    return $this->db->get()->result_array();
  }

  public function get_edit_suppliers_data($id)
  {
    $this->db->select('*');
    $this->db->from('suppliers');
    $this->db->where('id',$id);
    return $this->db->get()->result_array();
  }

  public function save_edit_suppliers_data($id,$alldata)
  {
    $this->db->where('id',$id);
   return  $this->db->update('suppliers',$alldata);
  }

  public function delete_confirm_supplier($id)
  {
    $this->db->where('id',$id);
    return $this->db->delete('suppliers');
  }

  public function get_unit_properties_data($id)
  {
    $sql="SELECT u1.*, GROUP_CONCAT(u1.lease_count) as total_leasecount,GROUP_CONCAT(u1.notlease_count) as totalnotlease_count from (SELECT `propertyId`,count(*) as lease_count,null as notlease_count FROM `units` WHERE `lease_status`=0 AND propertyId=$id UNION SELECT `propertyId`,null as lease_count,count(*) as notlease_count FROM `units` WHERE `lease_status`=1 AND propertyId=$id group by `propertyId`) as u1";
    $query=$this->db->query($sql);
    return $query->result_array();
  }

  public function get_properties_data($id)
  {
    
    $this->db->select('properties.*,property_img.p_img_id,property_img.property_imges');
    $this->db->from('properties');
    $this->db->join('property_img','property_img.property_id = properties.id');
    $this->db->where('properties.id',$id);
    return $this->db->get()->result_array();
  }

  public function get_search_data_all($whereCondition)
  {
    $sql="SELECT properties.*,units.*,property_img.property_imges FROM `properties` JOIN units ON properties.id = units.propertyId Join property_img ON  properties.id = property_img.property_id where units.lease_status=1 AND units.linkMultiTenant=0 $whereCondition";
    $query=$this->db->query($sql);
    return $query->result_array();
  }

  public function get_all_countries()
  {
    $this->db->select('*');
    $this->db->from('countries');
    return $this->db->get()->result_array();
  }

  public function get_all_state($id)
  {
    $this->db->select('*');
    $this->db->from('states');
    $this->db->where('country_id',$id);
    return $this->db->get()->result_array();
  }

  public function get_all_city($id)
  {
    $this->db->select('*');
    $this->db->from('cities');
    $this->db->where('state_id',$id);
    return $this->db->get()->result_array();
  }

  public function add_unit_property($data)
  {
    return $this->db->insert('units',$data);
  }
	
  public function chcek_user_info($userId,$pass)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('id',$userId);
    $this->db->where('pass',md5($pass));
    return $this->db->get()->num_rows();
  }

  public function email_check($userId,$email)
  {
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where('id !=',$userId);
      $this->db->where('email',$email);
      return $this->db->get()->num_rows();
  }
  public function phone_check($userId,$phone)
  {
     $this->db->select('*');
      $this->db->from('users');
      $this->db->where('id !=',$userId);
      $this->db->where('phone',$phone);
      return $this->db->get()->num_rows();
  }

 public function get_occupied_Property_list($userId)
  {
   $sql="SELECT abc.*,count(abc.id) as occupied_unit from (SELECT properties.id,properties.propertyName,properties.country,properties.state,properties.city,property_img.property_imges,properties.propertyType 
FROM properties   JOIN units  ON units.propertyId = properties.id  JOIN lease_tenant ON lease_tenant.unit_id= units.id join property_img ON property_img.property_id = properties.id where properties.landlord_id= $userId AND lease_tenant.status=0 AND units.lease_status=1
GROUP BY units.propertyId,lease_tenant.unit_id) as abc GROUP By abc.id";
 $query=$this->db->query($sql);
    return $query->result_array();
  }

  public function get_vaccant_Property_list($userId)
  {
    $sql="SELECT abc.* from (SELECT properties.id,properties.propertyName,properties.country,properties.state,properties.city,property_img.property_imges,properties.propertyType,count(properties.id) as vaccant_unit FROM properties JOIN units ON units.propertyId = properties.id  JOIN property_img ON property_img.property_id = properties.id where properties.landlord_id= $userId AND units.id NOT IN (select lease_tenant.unit_id from lease_tenant where lease_tenant.status=0 ) GROUP By properties.id,property_img.p_img_id Union SELECT properties.id,properties.propertyName,properties.country,properties.state,properties.city,property_img.property_imges,properties.propertyType,'0' as vaccant_unit FROM properties join property_img ON property_img.property_id = properties.id where properties.landlord_id = $userId GROUP By properties.id,property_img.p_img_id) as abc GROUP By abc.id";

     $query=$this->db->query($sql);
    return $query->result_array();
  }

  public function get_all_unitData($id)
  {
      $sql="SELECT properties.propertyName,properties.country,properties.state,properties.city,units.flatHoseNo,units.id,users.userName,users.id as Tenant_id, GROUP_CONCAT(users.userName) as username
  FROM properties  JOIN units  ON units.propertyId = properties.id JOIN lease_tenant ON lease_tenant.unit_id= units.id JOIN users ON users.id = lease_tenant.tenant_id  where properties.id= $id  Group By properties.id
   ";
   $query=$this->db->query($sql);
      return $query->result_array();
    
  }

  public function get_all_unitDetails_vacant($id)
  {
      $sql="SELECT properties.propertyName,properties.country,properties.state,properties.city ,units.* FROM properties Left JOIN units ON units.propertyId = properties.id where properties.id= $id AND units.id NOT IN (select lease_tenant.unit_id from lease_tenant where lease_tenant.status=0 ) ";
    $query=$this->db->query($sql);
    return $query->result_array();

  }

  public function get_unitANDteanatDetails($id)
  {
       $sql="SELECT units.flatHoseNo,units.unitType,units.furnishing,units.lease_status,add_lease.rentAmount,add_lease.paymentFrequency,add_lease.paymentDay,add_lease.depositAmount,add_lease.onLease,users.userName,users.email,users.phone,users.id as tenant_id FROM `units`
        left join add_lease on units.id=add_lease.unit_id
        left join lease_tenant on add_lease.unit_id=lease_tenant.unit_id
        left join users on lease_tenant.tenant_id=users.id
        WHERE units.id=$id";
         $query=$this->db->query($sql);
        return $query->result();
      
  }

  public function remove_tenant_unit($unit_id,$tenant_id)
  {
    $this->db->where('unit_id',$unit_id);
    $this->db->where('tenant_id',$tenant_id);
   $query=$this->db->delete('lease_tenant');
   if($query==true)
   {
    $this->db->where('id',$tenant_id);
    return $this->db->update('users',array('onLease'=>'No'));
   }else{
    return $query;
   }
   

  }

  public function remove_lease($unit_id)
  {
    $this->db->select('*');
    $this->db->from('lease_tenant');
    $this->db->where('unit_id',$unit_id);
    $this->db->where('status',0);
    $query=$this->db->get()->num_rows();
    if($query == 0)
    {
      $this ->db->where('unit_id',$unit_id);
      $this ->db->Delete('add_lease');
      $this ->db->where('id',$unit_id);
     return $this->db->update('units',array('lease_status'=>0,'onRent'=>''));
    }else{
      return "Tenant Alrady Rent";
    }
  }

  public function update_lease_data($unit_id,$data)
  {
           $this->db->where('unit_id',$unit_id);
    return $this->db->update('add_lease',$data);

  }

  public function UpdatePropertyProfile($property_id,$data)
  {
    $this->db->where('property_id',$property_id);
    return $this->db->update('property_img',$data);
  }

   public function get_oneUnit_data($unit_id)
  {
    $sql="SELECT units.*,add_lease.rentAmount,unit_img.home_img FROM `units` LEFT join add_lease ON add_lease.unit_id=units.id left join unit_img ON unit_img.unit_id=units.id WHERE units.id= $unit_id Group by unit_img.unit_id";
    $query=$this->db->query($sql);
    return $query->result_array();
  }

  public function get_lease_data_unit($unit_id)
  {
    $this->db->select('*');
    $this->db->from('add_lease');
    $this->db->where('unit_id',$unit_id);
    return $this->db->get()->result_array();

  }

  public function get_unit_tenant($userId,$unit_id)
  {
   
    $sql="select users.* from lease_tenant left join users ON users.id = lease_tenant.tenant_id where lease_tenant.status = 0 AND lease_tenant.unit_id =$unit_id  Union select * from users where users.landlordId = $userId AND users.onLease = 'No'";
    $query=$this->db->query($sql);
    return $query->result_array();
  }

 public function get_unit_tenant_data($unit_id)
  {
   
    $sql="select users.* from lease_tenant left join users ON users.id = lease_tenant.tenant_id where lease_tenant.status = 0 AND lease_tenant.unit_id =$unit_id ";
    $query=$this->db->query($sql);
    return $query->result_array();
  }
  // public function get_all_unlink_tenant($userId)
  // {
  //   $this->db->select("users.*");
  //     $this->db->from('users');
  //     $this->db->where('users.landlordId',$userId);
  // return  $this->db->get()->result_array();
  // }
  public function get_document_type($unit_id)
  {
    $this->db->select('document_type.*');
    $this->db->from('units');
    $this->db->join('properties','units.propertyId = properties.id');
    $this->db->join('document_type','properties.landlord_id = document_type.landlord_id');
    $this->db->where('units.id',$unit_id);
    return $this->db->get()->result_array();
  }

  public function get_document_details($unit_id)
  {
    $this->db->select('document.*,document_type.type as document_type');
    $this->db->from('document');
    $this->db->join('document_type','document_type.id = document.type','left');
    // $this->db->join('lease_tenant','document.unit_id = lease_tenant.unit_id','left');
    // $this->db->join('users','users.id = lease_tenant.tenant_id','left');
    $this->db->where('document.unit_id',$unit_id);
    
   return $this->db->get()->result_array();  
     
  }

  public function get_unit_images($unit_id)
  {
    $this->db->select('*');
    $this->db->from('unit_img');
    $this->db->where('unit_id',$unit_id);
    return $this->db->get()->result_array();
  }

  public function remove_unit_img($id)
  {
    $this->db->where('id',$id);
    return $this->db->delete('unit_img');
  }

  public function get_PropDoc_type($userId)
  {
    $this->db->select("*");
    $this->db->from('prop_document_type');
    $this->db->where('landlord_id',$userId);
    return $this->db->get()->result_array();
  }

  public function get_PropDoc_document($id)
  {
    $this->db->select('document.*,prop_document_type.type as document_type');
    $this->db->from('document');
    $this->db->join('prop_document_type','document.type = prop_document_type.id');
    $this->db->where('document.property_id',$id);
    $this->db->where('document.unit_id',0);
    return $this->db->get()->result_array();
  }
  
  public function getalldocumentdetails()
  {
	  $this->db->select('document.id,document_name,file_format,propertyName,file,file_format,userName,users.id as user_id,file');
	  $this->db->from('document');
	  $this->db->join('users','document.tenant_id=users.id');
	  $this->db->join('properties','document.property_id=properties.id');
	 $this->db->join('document_type','document.type=document_type.id');
	// $this->db->group_by('document.id');
	 return  $this->db->get()->result_array();
	  $this->db->last_query();
  }
  
  public function getPropertyName($userid)
  {
	  $this->db->select('propertyName,id,landlord_id');
	  $this->db->from('properties');
	  $this->db->where('landlord_id',$userid);
	  return $this->db->get()->result_array();
  }
	 public function getTenant($id)
  {
	  $this->db->select('username,users.id');
	  $this->db->from('units');
	  $this->db->join('lease_tenant','lease_tenant.unit_id=units.id');
	  $this->db->join('users','users.id=lease_tenant.tenant_id');
	  $this->db->where('unit_id',$id);
	  $this->db->where('lease_tenant.status',0);
	  return $this->db->get()->result_array();
  }
  
  public function adddoctype($data)
  {
	  return $this->db->insert('document_type',$data);
  }

  public function get_unit_details($unit_id)
  {
    $this->db->select('properties.*,units.*,users.userName,users.phone,users.email,add_lease.rentAmount,add_lease.depositAmount');
    $this->db->from('units');
    $this->db->join('add_lease','add_lease.unit_id = units.id');
    $this->db->join('properties','units.propertyId = properties.id');
    $this->db->join('users','users.id = properties.landlord_id');
    $this->db->where('units.id',$unit_id);
     $this->db->group_by('units.id'); 
    return $this->db->get()->result_array();
  }
  
  public function getUnit($id)
  {
	 $this->db->select('flatHoseNo,propertyId,id'); 
	 $this->db->from('units'); 
	 $this->db->where('propertyId',$id); 
	 return $this->db->get()->result_array();
  }
	
  public function get_income_type($userId)
  {
    $this->db->select('*');
    $this->db->from('transactions_type');
    $this->db->where('type',0);
    $this->db->where('landlord_id',$userId);
    return $this->db->get()->result_array();
  }

  public function get_Expence_type($userId)
  {
    $this->db->select('*');
    $this->db->from('transactions_type');
    $this->db->where('type',1);
    $this->db->where('landlord_id',$userId);
    return $this->db->get()->result_array();
  }
   public function get_vanders($userId)
  {
    $this->db->select('*');
    $this->db->from('suppliers');
    $this->db->where('landlord_id',$userId);
    return $this->db->get()->result_array();
  }
  
  public function deleteDocRecord($id)
  {
	  $this->db->where('id',$id);
	return  $this->db->delete('document');
  }

  public function get_properties($userId)
  {
     $this->db->select('properties.id,properties.propertyName,properties.propertyType,properties.streetName,properties.city,properties.state,properties.landmark,properties.pincode,properties.suburbs,properties.notes');
    $this->db->from('properties');
    $this->db->where('landlord_id',$userId);
    return $this->db->get()->result_array();
  }
  public function getallTenantAndUnit($property_id)
  {
     $this->db->select('units.*');
    $this->db->from('units');
    $this->db->where('units.lease_status',1);
    $this->db->where('units.propertyId',$property_id);


     // $this->db->group_by('units.id'); 
    return $this->db->get()->result_array();
  }
  
  // public function getNotification()
  // {
	 //  $this->db->
  // }
  public function get_transaction_details($landlord_id)
  {
    $this->db->select('transaction.*,properties.propertyName,users.userName,suppliers.name');
    $this->db->from('transaction');
    $this->db->join('properties','transaction.property_id = properties.id');
    $this->db->join('users','transaction.tenant_id = users.id');
    $this->db->join('suppliers','transaction.vender_name = suppliers.id','left');
    $this->db->where('transaction.landlord_id',$landlord_id);
    return $this->db->get()->result_array();
  }

  public function get_unit_services($unit_id)
  {

  }

  public function get_tenant_details($tenant_id)
  {
    $this->db->select('units.*');
    $this->db->from('lease_tenant');
    $this->db->join('units','lease_tenant.unit_id = units.id');
    $this->db->where('lease_tenant.tenant_id',$tenant_id);
    $this->db->where('lease_tenant.status',0);
    return $this->db->get()->result_array();

  }

  public function get_tenant_data($tenant_id)
  {
    $this->db->select('units.*,users.userName,properties.propertyName');
    $this->db->from('users');
    $this->db->join('lease_tenant','users.id =lease_tenant.tenant_id','left');
    $this->db->join('units','lease_tenant.unit_id = units.id','left');
    $this->db->join('properties','units.propertyId = properties.id');
    $this->db->where('users.id',$tenant_id);
    $this->db->group_by('users.id');

    // $this->db->where('lease_tenant.status',0);
    return $this->db->get()->result_array();
  }

  public function get_lease_data_tenant($userId)
  {
     $this->db->select('add_lease.*');
    $this->db->from('lease_tenant');
    $this->db->join('units','lease_tenant.unit_id = units.id');
    $this->db->join('add_lease','units.id = add_lease.unit_id');
    $this->db->where('lease_tenant.tenant_id',$userId);
    $this->db->where('lease_tenant.status',0);
    return $this->db->get()->result_array();

  }

  public function get_service_requst($tenant_id)
  {
    $this->db->select('service_request.*,units.flatHoseNo');
    $this->db->from('service_request');
    $this->db->join('units','service_request.unit_id = units.id');
    $this->db->join('users','service_request.tenant_id = users.id');
    $this->db->where('service_request.tenant_id',$tenant_id);
    return $this->db->get()->result_array();
  }
  public function update_request_service($id,$data)
  {
    $this->db->where('id',$id);
   return $this->db->update('service_request',$data);
  }

  public function get_services_data($unit_id)
  {
    $this->db->select('service_request.*,units.flatHoseNo,properties.propertyName,users.userName');
    $this->db->from('service_request');
    $this->db->join('units','service_request.unit_id = units.id');
    $this->db->join('users','service_request.tenant_id = users.id');
    $this->db->join('properties','units.propertyId = properties.id');
    $this->db->where('service_request.unit_id',$unit_id);
     $this->db->where('service_request.status',0);
    return $this->db->get()->result_array();
  }
  public function get_services_data_complet($unit_id)
  {
    $this->db->select('service_request.*,units.flatHoseNo,properties.propertyName,users.userName');
    $this->db->from('service_request');
    $this->db->join('units','service_request.unit_id = units.id');
    $this->db->join('users','service_request.tenant_id = users.id');
    $this->db->join('properties','units.propertyId = properties.id');
    $this->db->where('service_request.unit_id',$unit_id);
     $this->db->where('service_request.status',1);
    return $this->db->get()->result_array();
  }
 
   public function get_services_data_decline($unit_id)
  {
    $this->db->select('service_request.*,units.flatHoseNo,properties.propertyName,users.userName');
    $this->db->from('service_request');
    $this->db->join('units','service_request.unit_id = units.id');
    $this->db->join('users','service_request.tenant_id = users.id');
    $this->db->join('properties','units.propertyId = properties.id');
    $this->db->where('service_request.unit_id',$unit_id);
     $this->db->where('service_request.status',2);
    return $this->db->get()->result_array();
  }
  public function get_serviceReq_data($request_id)
  {
    $this->db->select('service_request.*,units.flatHoseNo,properties.propertyName,users.userName');
    $this->db->from('service_request');
    $this->db->join('units','service_request.unit_id = units.id');
    $this->db->join('users','service_request.tenant_id = users.id');
    $this->db->join('properties','units.propertyId = properties.id');
    $this->db->where('service_request.id',$request_id);
    return $this->db->get()->result_array();
  }

  public function get_service_message($unit_id)
  {
    $this->db->select('*');
    $this->db->from('chat_request');
    $this->db->where('unit_id',$unit_id);
    return $this->db->get()->result_array();
  }

  public function edit_request_save($id,$data)
  {
    $this->db->where('id',$id);
    return $this->db->update('service_request',$data);
  }

  public function get_vendors($landlord_id)
  {
    $this->db->select('*');
    $this->db->from('suppliers');
    $this->db->where('landlord_id',$landlord_id);
    return $this->db->get()->result_array();
  }

  public function get_transaction($trans_id)
  {
    $this->db->select("transaction.*,units.flatHoseNo,users.userName,user.userName as tenant_name,user.email");
    $this->db->from('transaction');
    $this->db->join('units','transaction.unit_id = units.id');
    $this->db->join('users','transaction.landlord_id = users.id');
     $this->db->join('users As user','transaction.tenant_id = user.id');
    $this->db->where('transaction.id',$trans_id);
    return $this->db->get()->result_array();

  }
  public function get_Treansaction_data($table_name,$whereCondition,$trans_id)
  {
    $this->db->select("*");
    $this->db->from($table_name);
    $this->db->where($whereCondition,$trans_id);
    return $this->db->get()->result_array();
  }

  public function delete_data($tabl_name,$whereCondition,$id)
  {
    $this->db->where($whereCondition,$id);
    return $this->db->delete($tabl_name);
  }

  public function get_prop_transaction($property_id)
  {
    $this->db->select('transaction.*,units.flatHoseNo,users.userName,suppliers.name');
    $this->db->from('transaction');
    $this->db->join('units','transaction.unit_id = units.id');
    $this->db->join('users','transaction.tenant_id = users.id');
    $this->db->join('suppliers','transaction.vender_name = suppliers.id','left');
    $this->db->where('transaction.property_id',$property_id);
    return $this->db->get()->result_array();
  }

  public function get_transaction_data($userId)
  {
    $this->db->select('transaction.*,transactions_type.type_name');
    $this->db->from('transaction');
    $this->db->join('transactions_type','transaction.transaction_type = transactions_type.id');
    $this->db->where('transaction.tenant_id',$userId);
    return $this->db->get()->result_array();

  }

  public function get_files_details_tenant($userId)
  {
    $this->db->select('document.*,document_type.type');
    $this->db->from('document');
    $this->db->join('document_type','document.type = document_type.id');
    $this->db->where('document.tenant_id',$userId);
    return $this->db->get()->result_array();
     
  }

  public function get_serach_landlord()
  {
    $this->db->select('users.id,users.userName as name');
    $this->db->from('users');
    $this->db->where('role','landlord');
    //$this->db->limit(3,0);
    return $this->db->get()->result();
  }

  public function get_land_properties($land_id)
  {
    $this->db->select('properties.id,properties.propertyName');
    $this->db->from('properties');
    $this->db->where('landlord_id',$land_id);
    return $this->db->get()->result_array();
  }

  public function get_unit_details_tenent($property_id)
  {

    $this->db->select('units.*');
    $this->db->from('units');
    $this->db->where('units.propertyId',$property_id);
    return $this->db->get()->result_array();
  }
}
?>