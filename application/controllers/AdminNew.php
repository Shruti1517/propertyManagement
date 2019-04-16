<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400'); 
	  }
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
				exit(0);
		}
class AdminNew extends CI_Controller {

	
	
	 function __construct() {
        parent::__construct();
        $this->load->library(array('JwtAuth','form_validation'));
	    $this->load->model('ApiModel');
		$this->load->helper(array('form', 'url'));
		$this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
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
	 public function send_email_recipt()
	 {
	 	$transactionId=file_get_contents("php://input");
	$transactionDetails=$this->ApiModel->get_transaction($transactionId);
		$additonal=$this->ApiModel->get_Treansaction_data('additional_transaction','transaction_id',$transactionId);
		$deduction=$this->ApiModel->get_Treansaction_data('deduction_transaction','transaction_id',$transactionId);
		// echo json_encode($transactionId);
		$pay_method=$transactionDetails[0]['payment_type'] == 1?'Cash' : $transactionDetails[0]['payment_type'] == 2 ?'Check' : 'Other';
		$payment_status=$transactionDetails[0]['payment_status'] == 0 ?'Unpaid' : $transactionDetails[0]['payment_status'] == 1 ?'Paid' : 'Others';
	 	$html='<!DOCTYPE html>
  <html>
	<head>
		<title>Receipt</title>
		
	</head>
	<body>
		<div class="receiptDetail" style="padding:30px 0px 0px; width: 100%;float: left;">
			<div class="receiptDetailInner" style="border: 1px solid #0275fe;width: 100%;float: none;margin: 0px auto;position:relative;max-width: 700px;">
				<h4 class="recHead" style=" font-size: 20px;margin: 0 !important;background-color: #0275fe;color: #fff;padding: 5px 15px;">Rent Receipt</h4>
				<div class="listReceipt" style="padding: 25px 40px 0;width: 100%;float: left;">
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">Date</p>
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">	: '.$transactionDetails[0]['payment_date'].'</p>
				</div>
				<div class="listReceipt" style="padding: 25px 40px 0;width: 100%;float: left;">
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">Received From	</p>
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">:  '.$transactionDetails[0]['tenant_name'].'	</p>
				</div>
				<div class="listReceipt" style="padding: 25px 40px 0;width: 100%;float: left;">
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">Rental Property	</p>
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">: '.$transactionDetails[0]['flatHoseNo'].'	</p>
				</div>
				<div class="listReceipt" style="padding: 25px 40px 0;width: 100%;float: left;">
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">Payment Received As</p>
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">: Rent	</p>
				</div>
				<div class="listReceipt" style="padding: 25px 40px 0;width: 100%;float: left;">
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">Payment Method</p>
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">	:
					'.$pay_method.'	</p>				
				</div>
				<div class="listReceipt" style="padding: 25px 40px 0;width: 100%;float: left;">
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">For The Period	</p>
					<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">: '.$transactionDetails[0]['start_period'].' To '.$transactionDetails[0]['end_period'].'	</p>
				</div>
				<div class="fullwidth DFlex" style="display: inline-block;width: 100%;border-bottom: 1px solid #666;margin: 0px 0;">
					<div class="text-left" style="text-align:left;width:38%;padding: 0 40px 0;float:left">
						<h3 style="font-size: 20px;">Charge Description</h3>
						<p style="font-size: 16px;color: #000;">Sub Total</p>
					</div>
					<div class="text-right" style="text-align:right;width:38%;padding: 0 40px 0;float:right">
						<h3 style="font-size: 20px;">Amount</h3>
						<p style="font-size: 16px;color: #000;">KSh '.$transactionDetails[0]['amount'].'</p>
					</div>';
					foreach ($additonal as $add) 
					{
						 $add_type=$add['add_type'] == 0? 'Ksh':'%';
						$html.='<div class="listReceipt" style="padding: 0px 40px 25px;width: 89%;float: left;">
							<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">'.$add['add_text'].'</p>
							<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;text-align:right">(+) '. $add_type .' '.$add['add_digit'].'</p>
						</div>';
				    }

				    foreach ($deduction as $deduct) 
					{
						 $remove_type=$deduct['remove_type'] == 0 ? 'Ksh':'%';
					$html.='<div class="listReceipt" style="padding: 0px 40px 25px;width: 89%;float: left;">
						<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;">'.$deduct['remove_text'].'</p>
						<p style="margin: 0;width: 50%;float: left;font-size: 16px;color: #000;text-align:right">(-)'. $remove_type .' ' .$deduct['remove_digit'].'	</p>
					</div>';
				   }
					
				$html.='</div>
				<div class="fullwidth DFlex" style="display: inline-block;width: 100%;margin: 0px 0;padding: 15px 0 0;">
					<div class="text-left" style="text-align:left;width:38%; padding: 0 40px 0;float:left;">
						<p style="font-size: 16px;color: #000;">Total Charges</p>
						<p style="font-size: 16px;color: #000;">Landlord:</p>
					</div>
					<div class="text-right" style="text-align:right;width:38%; padding: 0px 40px 0;float:right;">
						<p style="font-size: 16px;color: #000;">KSh '.$transactionDetails[0]['totalAmount'].'</p>
						<p style="font-size: 16px;color: #000;">'.$transactionDetails[0]['userName'].'</p>
					</div>
				</div>
				<div class="paidDiv" style="position: absolute;right: 10px;height: 60px;width: 60px;border: 2px dashed red;border-radius: 50% !important;display: table;justify-content: center; align-items: center;top: 48px;transform: rotate(40deg);">
					<h4 style="color: red; margin: 0;font-size: 18px;display: table-cell;vertical-align: middle;text-align: center;">'.$payment_status.'</h4>
				</div>
			</div>
		</div>
	</body>
</html>';

$to=$transactionDetails[0]['email'];
$subject='Payment Details';
	$this->sendMail($to,$html,$subject);
 }


public function sendMail($to,$html,$subject){
		
	$message= $html;
	 	
	 	$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'husenshikalgar007@gmail.com', 
            'smtp_pass' => '9561840980',//my valid email password
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
                  );

		$this->email->initialize($config);
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");  
		$this->email->from('husenshikalgar007@gmail.com'); 
              //$this->email->to($emial_id[0]['email']); // user Emial to who perches the Sticker
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message); 
	    $this->email->send();


	}

	public function delete_transaction()
	{
		$transactionId=file_get_contents("php://input");
		$query=$this->ApiModel->delete_data('transaction','id',$transactionId);
		$query=$this->ApiModel->delete_data('additional_transaction','transaction_id',$transactionId);
		$query=$this->ApiModel->delete_data('deduction_transaction','transaction_id',$transactionId);
		echo json_encode($query);
	}

	public function get_prop_transaction()
	{
		$property_id=file_get_contents("php://input");
		$query=$this->ApiModel->get_prop_transaction($property_id);
		echo json_encode($query);
	}

	public function get_transaction_data()
	{
		$userId=$this->checklogin();
		 $query['transaction_data']=$this->ApiModel->get_transaction_data($userId);
		 $query['unit_data']=$this->ApiModel->get_tenant_data($userId);
		echo json_encode($query);
	}

	public function get_lease_details()
	{
		$userId=$this->checklogin();
		$query=$this->ApiModel->get_lease_data_tenant($userId);
		echo json_encode($query);
	}

	public function get_files_details()
	{
		$userId=$this->checklogin();
		$query=$this->ApiModel->get_files_details_tenant($userId);
		echo json_encode($query);

	}

	public function save_tenant_document()
	{
		$data=(array)json_decode(file_get_contents("php://input"));
		$userId=$this->checklogin();
		 $img_name1 = explode('.', $_FILES['selectFile']['name']);
		
				$extension1 = end($img_name1);
				$pic_name1=mt_rand().'.'.$extension1;
				$b1=$_FILES['selectFile']['tmp_name'];
				 $b2=$img_name1[0].'_'.$pic_name1;
				 $b3='uploads/Home_Images/'.$b2;
			
				move_uploaded_file($b1,$b3);
	   		// $data=$_POST;
	   		$data['file']=$b3;
	   		$data['file_format']=$extension1;
	   		$data['date']=date('Y-m-d');
	   		$data['unit_id']=$_POST['unit_id'];
	   		$data['type']=$_POST['document_type'];
	   		$data['document_name']=$_POST['document_name'];
	   		$data['tenant_id']=$userId;

	   		 $query=$this->ApiModel->insertData('document',$data);
	   		echo json_encode($query);
	}

	public function get_serach_landlord()
	{
		$query=$this->ApiModel->get_serach_landlord();
		echo json_encode($query);
	}

	public function get_land_properties()
	{
		$land_id=file_get_contents("php://input");
		$query=$this->ApiModel->get_land_properties($land_id);
		echo json_encode($query);

	}

	public function get_unit_details_tenent()
	{
		$property_id=file_get_contents("php://input");
		$query=$this->ApiModel->get_unit_details_tenent($property_id);
		echo json_encode($query);
	}

	public function invite_landlord()
	{
		$data=(array)json_decode(file_get_contents("php://input"));
		 $html='join my website '.ANGULAR_URL;
		 $subject="Invitation";
		 $this->sendMail($data['email'],$html,$subject);
		echo json_encode($data['email']);
	}

	public function active_deactive_unit()
	{
		$data=(array)json_decode(file_get_contents("php://input"));
		$id=$data['unit_id'];
		$alldata=array('linkMultiTenant'=>$data['value']);
		$query=$this->ApiModel->update_unit_status($id,$alldata);
		echo json_encode($query);
	}
}