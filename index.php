<?php 
include "header.php";
include("common_functions.php");

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
<script type="text/javascript" src="js/sha512.js"></script>
  <div class="container">
      <center>
          <div class="middle">
            <div id="login">
              <h2>USER LOGIN</h2>
              <form action="checklogin.php" onsubmit="encrypt_password()" method="post">
                <div id="div_error" class="error_div" style="display:none;"></div>
                <fieldset class="clearfix">
                  <p><span class="fa fa-user"></span><input type="text" name="username"  id="username" Placeholder="Username" required></p> 
                  <p><span class="fa fa-lock"></span><input type="password"  name="password" id="password" Placeholder="Password" required></p> 
                   <div>
                      <span style="width:48%; text-align:left;  display: inline-block;"><a class="small-text" href="#">Forgot
                      password?</a></span>
                      <span style="width:50%; text-align:right;  display: inline-block;"><input type="button" class="btncca" id="loginbtn" value="Sign In"></span>
                  </div>
                </fieldset>
                <div class="clearfix"></div>
              </form>
              <div class="clearfix"></div>
            </div> <!-- end login -->
            <div class="logo">
                <img src="images/audit1.jpg" width="90%" />
                <div class="clearfix"></div>
            </div>
            </div>
      </center>
    </div>
   
</div>

<?php include "footer.php"; ?>
<script>
  function encrypt_password(username,password){
    var salt = "<?php echo $_SESSION['slt'] ; ?>";
    // var username=document.getElementById("username").value;
    // var password=document.getElementById("password").value;
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
  
  $( "#loginbtn").click(function() {
    var username=document.getElementById("username").value;
    var password=document.getElementById("password").value;
    

    if(username==""){
      $('#div_error').show();
      $('#div_error').html("Please Enter Username!");
    }else if(password==""){
      $('#div_error').show();
      $('#div_error').html("Please Enter Password!");
    }else{
      encrypt_password(username,password);
      var passwordnew=document.getElementById("password").value;

      $.post("checklogin",{username: username, password:passwordnew},function(res){
        console.log(res);
      var element = res.split('#'); 
         if(element[0]=='success')
         {
          window.location.href = element[1];
         }
         else
         {
            $('#div_error').show();
            $('#div_error').html(element[1]);
            $('input: #password').val("");
          }
     });
    }
  });
</script>
