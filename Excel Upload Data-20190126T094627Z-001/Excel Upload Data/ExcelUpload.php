EXCEL FILE UPLODING CODE FORMAT


//print_r($_FILES);exit;
		$xls=$_FILES['file']['name'];
		
	
		if( move_uploaded_file($_FILES['file']['tmp_name'], 'assets/files/' .$xls))
		{
			require("assets/reader.php");
			$file='assets/files/'.$xls;
			$connection=new Spreadsheet_Excel_Reader(); // our main object
			$connection->read($file);
			$arr=array();
			$q=$connection->sheets[0];
			$q1=$q['cells'];
			unset($q1[1]);
			$newarr=array_values($q1);
			$this->Manage_Model->empty_table();
			for($i=0;$i<count($newarr);$i++)
			{
				 $newDate = date("Y-m-d", strtotime($newarr[$i][7]));
				 $e=$newarr[$i][4];
				 $a=$newarr[$i][5];
				 $b=$newarr[$i][6];
				 $c_m=str_replace("#",",","$a");
				 $c_o=str_replace("#",",","$b");
				 $email=str_replace("#",",","$e");
				 if($newarr[$i][21]==1)
				{
					$yes='yes';
				}else{
					$yes='no';
				}
					
				$array=array('fname'=>$newarr[$i][1],'lname'=>$newarr[$i][2],'org'=>$newarr[$i][3],'email'=> $email,'contact_m'=>$c_m,'contact_o'=>$c_o,'b_date'=>$newDate,'status'=>$newarr[$i][8],'designation'=>$newarr[$i][9],'filed'=>$newarr[$i][10],'tag'=>$newarr[$i][11],'bnotes'=>$newarr[$i][12],'add_by'=>$newarr[$i][13],'country'=>$newarr[$i][14],'city'=>$newarr[$i][15],'street_add'=>$newarr[$i][16],'bname'=>$newarr[$i][17],'uno'=>$newarr[$i][18],'pin'=>$newarr[$i][19],'notes'=>$newarr[$i][20],'d_add'=>$newarr[$i][21],'dcounrty'=>$newarr[$i][22],'dcity'=>$newarr[$i][23],'dstreet_add'=>$newarr[$i][24],'db_name'=>$newarr[$i][25],'duno'=>$newarr[$i][26],'dpin'=>$newarr[$i][27],'dnotes'=>$newarr[$i][28],'d2_add'=>$newarr[$i][29],'d2_country'=>$newarr[$i][30],'d2_city'=>$newarr[$i][31],'d2street_add'=>$newarr[$i][32],'d2b_name'=>$newarr[$i][33],'d2uno'=>$newarr[$i][34],'d2pin'=>$newarr[$i][35],'d2_notes'=>$newarr[$i][36],'selected'=>true,'is_copy'=>$yes) ;
				//print_r($array);exit;
				
				$this->Manage_Model->csv_upload($array);
			}