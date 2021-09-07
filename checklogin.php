<?php 
session_start();
include_once("config.php");
include("common_functions.php");
if(isset($_POST) && !empty($_POST) ){
		 $username=mysqli_real_escape_string($mysqli, $_POST['username']);
		  $password=mysqli_real_escape_string($mysqli, $_POST['password']);
		 //echo "</br>";

		
		$checkuser = "select * from `cca_users` where `username`='".$username."'";


		if ($result=mysqli_query($mysqli,$checkuser))
		  {
		 
		  		$rowcount=mysqli_num_rows($result);
			  	if($rowcount>0){
					$checkuser_result=mysqli_fetch_array($result);
					$passdb=$checkuser_result['password'];
				}
		  }
	
 		 $usernamesha=openssl_digest($username,'sha512');
		 $saltkeysha=openssl_digest($_SESSION['slt'],'sha512');
		   $encryptedpass = openssl_digest($usernamesha.$saltkeysha.$passdb, 'sha512');
		 if($encryptedpass==$password){

		 	// =======find user designation========
		 	$userdesign = "select * from `cca_designations` where `id`='".$checkuser_result['Desig_ID']."'";
		 	$result_userdesg=mysqli_query($mysqli,$userdesign);
		 	$userdesig_result=mysqli_fetch_array($result_userdesg);
			$designation=$userdesig_result['S_descr'];

			$_SESSION['designation']=$designation;

		 	$_SESSION['userid']=$checkuser_result['ID'];

		 	$_SESSION['dept_id']=$checkuser_result['Dept_ID'];

		 	$redrct_url="";

		 	if($checkuser_result['Role_ID']==1){
		 		//header('location: monitor/dashboard.php');
		 		$redrct_url='monitor/dashboard.php';
		 	}
		 	if($checkuser_result['Role_ID']==2){
		 		$redrct_url='head/dashboard.php';
		 		//header('location: head/dashboard.php');
		 	}
		 	if($checkuser_result['Role_ID']==3){
		 		//header('location: fa/dashboard.php');
		 		$redrct_url='fa/dashboard.php';
		 	}
		 	if($checkuser_result['Role_ID']==4){
		 		//header('location: rev/dashboard.php');
		 		$redrct_url='rev/dashboard.php';
		 	}
		 	if($checkuser_result['Role_ID']==5){
		 		//header('location: aao/dashboard.php');
		 		$redrct_url='aao/dashboard.php';
		 	}
		 	if($checkuser_result['Role_ID']==6 || $checkuser_result['Role_ID']==8){
		 		$redrct_url='auditor/dashboard.php';
		 	}
		 	if($checkuser_result['Role_ID']==7){
		 		//header('location: afa/dashboard.php');
		 		$redrct_url='afa/dashboard.php';
		 	}
			
		 	echo "success#".$redrct_url;
		 	exit();
		 	
		 }else{
		 	echo "Failure#Login Failed!!";

		 }

		exit();
	}
?>