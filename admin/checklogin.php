<?php 
session_start();
include_once("../config.php");
include("../common_functions.php");
if(isset($_POST) && !empty($_POST) ){
		 $username=mysqli_real_escape_string($mysqli, $_POST['username']);
		 $password=mysqli_real_escape_string($mysqli, $_POST['password']);
		$checkuser = mysqli_query($mysqli, "select * from `cca_login` where username='".$username."'");
		if(mysqli_num_rows($checkuser)>0){
			$checkuser_result=mysqli_fetch_array($checkuser);
			 $passdb=$checkuser_result['password'];
		}
		//echo "</br>";
	//echo openssl_digest('Ccaadmin@20','sha512');
		 $usernamesha=openssl_digest($username,'sha512');
				 $saltkeysha=openssl_digest($_SESSION['slt'],'sha512');
				 $encryptedpass = openssl_digest($usernamesha.$saltkeysha.$passdb, 'sha512');
				 if($encryptedpass==$password){
				 	session_start();
				 	$_SESSION['userid']=$username;
				 	header('location: manage_department.php');
				 }else{
				 	echo "Login Failed!!";
				 }

		exit();
	}
?>