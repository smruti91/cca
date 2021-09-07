<?php
function loginStatus(){
	  $salt       = $_SESSION['slt'];
	  $msg        = "Failure#Incorrect details provided.";
	  
	  $errarray            = array();
	  $username   = $_POST['username'];
	 
	  $currentpwd =trim($_POST['currentpwd']);

	  $confirmpwd = $_POST['confirmpwd'];

     echo  $user_id    = $_POST['userid'];
      $user_id_err= $user_id;
      array_push($errarray, $user_id_err);

	  $userid     = $db->real_escape_string($user_id);
	 
	  $username   = $db->real_escape_string($username);
	  
	  $currentpwd    = $db->real_escape_string($currentpwd);
	  $currentpwdsha = $currentpwd;
	  
	  $newpsw     = filter_variable($_POST['newpwd']);
	 
	  $newpwd	  = $db->real_escape_string($newpsw);

	  // $newpwd     = openssl_digest($newpwd1, 'sha512');
	  $confirmpwd = $db->real_escape_string($confirmpwd);
	  // $name 	  = $db->real_escape_string($name);
	  // $mobile 	  = $db->real_escape_string($mobile);
	  // $email 	  = $db->real_escape_string($email);
	  $found      = false;
				
	   $errarray=array_filter($errarray);
 		if(count($errarray)!=0){
          ?>
          <div id="divnorecord" style="margin-left:10px;">Sorry, No Data Found.</div>
          <?php
            exit();
        }
       
	  	$result   = mysqli_query($mysqli, "SELECT * from cca_users u   where u.ID='".$userid."'");

	   $result_oldpwd   = generateSQL("select * from (
	            	 select * from old_pwds_table
					     where user_id=?
					     order by timestamp desc
					     limit 3
					) a 
					where 
					 old_password=?",array($userid,$newpwd),false,$db);

		if(count($result_oldpwd)>0){

			$msg	=	"Failure#Please use a new password that is not taken for last 3 password history.";
			echo htmlentities($msg);
 			exit();
		}	

	    if($result->num_rows>0){
			    $row         = $result->fetch_array();
				$passwordmd5 = md5(md5($salt).$row['password'].md5($username));
				$passwordsha = hash('sha512',hash('sha512', $salt).$row['password'].hash('sha512', $username));

				if($row['password']==$newpwd){
					$msg	=	"Failure#Please use a new password that is not taken for last 3 password history.";
					echo htmlentities($msg);
		 			exit();
				}

			 if($currentpwdsha==$passwordsha)
			 {			 
			  $loginn_type = $row['type'];
			  
				if ($loginn_type == 'user')
				{
					$old_password=$row['password'];
					
					$db->query("UPDATE user_master SET password='$newpwd', email='$email',phone='$mobile',name='$name',duppassword='$confirmpwd', user_status='1' where username='$username'");
				
					generateSQL("INSERT  INTO `old_pwds_table` (`user_id`, `old_password`) VALUES (?,?)",array($userid,$old_password),true,$db);
					$msg	="Success#".BASE_URL."user/";
				}
			 }
		}

 echo $msg;
 exit();
 
}


 if($_POST['currentpwd']!="" && $_POST['newpwd']!=""){
 	 // print_r($_POST);exit();
	include_once("../config.php");
	 session_start();
	 loginStatus();

  }
  else
  {
    print "Failure#Details not provided.";
  }
?>
