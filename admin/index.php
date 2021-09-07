<?php 
include_once("../config.php");
include("../common_functions.php");
session_start();
if (isset($_SESSION))
{
  session_destroy();
  unset($_SESSION);
}


if(isset($_SESSION['slt']) && $_SESSION['slt']!='')
	{
	}else{
		session_start();
		$slt=crypto_rand(1,9999999);
		if($slt !='')
		{
			$_SESSION['slt']=$slt;
		}
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Login</title>
	<link rel="stylesheet" type="text/css" href="../css/cca-admin.css">
	<script type="text/javascript" src="../js/sha512.js"></script>
</head>
<body>
	
	<div class="login">
		<h1>Admin Login</h1>
        <p style="color:red; font-size:18px;" align="center"></p>
        <form action="checklogin.php" onsubmit="encrypt_password()" method="post">
	    	<input type="text" name="username" id="username" class="form-control" placeholder="Username" autofocus="autofocus" required="required" />
             <div style='color:red; padding-bottom: 7px;'></div>
	         <input type="password" name="password" id="password" class="form-control" placeholder="Password" autofocus="autofocus" required="required" />
	         <div style='color:red;padding-bottom:7px;'></div>
	         <input type="submit" name="login" value="Let me in." class="btn btn-primary btn-block btn-large">	
	    </form>
	</div>
</body>
<script>
	function encrypt_password(){
		var salt = "<?php echo $_SESSION['slt'] ; ?>";
		var username=document.getElementById("username").value;
		var password=document.getElementById("password").value;
		var valSha=encryptShaPwd(salt,username,password);
		document.getElementById('password').value=valSha;
	}

	function encryptShaPwd(key,struser,strpwd)
	{
		var username=struser;
		var password=strpwd;
		//var enc =key;
		var enc =calcHash("SHA-512",calcHash("SHA-512",username)+calcHash("SHA-512",key)+calcHash("SHA-512",password));
		return enc;
	}
//-------Code to encrypt the password in sha512--------------------	
	function calcHash(variant,value)
	{ 
		var shaObj = new jsSHA(value, "ASCII");
		var hexval = shaObj.getHash(variant, "HEX");
		return hexval;
	}
</script>
</html>