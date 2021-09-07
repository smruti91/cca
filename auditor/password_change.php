
<?php 
include "header.php";
include_once("../config.php");
session_start();
 $userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
$result_user = mysqli_query($mysqli, "SELECT username from `cca_users` u  where u.ID='".$userid."'"); 
$resuser = mysqli_fetch_array($result_user);
$username= $resuser ['username'];
 ?>
<style>
/*div.main{
height:100%;
}*/

.pass_show{position: relative} 

.pass_show .ptxt { 

position: absolute; 

top: 50%; 

right: 10px; 

z-index: 1; 

color: #f36c01; 

margin-top: -10px; 

cursor: pointer; 

transition: .3s ease all; 

} 

.pass_show .ptxt:hover{color: #333333;} s
</style>
<script>
  $(document).ready(function(){
$('.pass_show').append('<span class="ptxt">Show</span>');  
});
  

$(document).on('click','.pass_show .ptxt', function(){ 

$(this).text($(this).text() == "Show" ? "Hide" : "Show"); 

$(this).prev().attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; }); 

});  
</script>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
            <div class="container-fluid text-center">    
              <div class="row content">
                <div class="col-sm-12 text-center"> 
                   <h1>Change Password</h1>
                  <hr>
                       <form id="form-changepassword" class="form-horizontal" action="" method="post" onsubmit="return changepassword();">
                        <input type="hidden" name="username" id="username" value="<?php echo htmlentities($username);?>" />
                        <div class="form-group">
                          <div id="div_error" class="" style="*display:none;text-align:left;color:red;margin-left:18%;margin-bottom:10px"></div>
                          <label class="control-label col-sm-2">current Password:</label>
                          <div class="col-sm-2 pass_show">
                             <input class="form-control" id="currentpwd" name="currentpwd" placeholder="Current Password" type="password" autocomplete="off"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2">New Password:</label>
                          <div class="col-sm-2 pass_show">
                            <input class="form-control" id="newpwd" name="newpwd" placeholder="New Password" type="password" autocomplete="off"/>
                          </div>
                        </div>
                        <div id="div_errorintd" class="form-group error_div" style="display:none;text-align:left;">Plan Initiation Date can not be blank!</div>
                        <div class="form-group">
                          <label class="control-label col-sm-2">Confirm Password:</label>
                          <div class="col-sm-2 pass_show">
                            <input class="form-control" id="confirmpwd" name="confirmpwd" placeholder="Confirm Password" type="password" autocomplete="off"/>
                          </div>
                        </div>
                         <div id="div_errorcomnce" class="form-group error_div" style="display:none;text-align:left;">Date of Commencement can not be blank!</div>
                        
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-4">
                            <button type="submit" class="btn btn-default">Submit</button>
                          </div>
                        </div>
                      </form>
                  </div>
            </div>
        </div>

        <!-- /.container-fluid -->
    </div>
    <div class="clear:both;"></div>
</div>
<?php include "footer.php"; ?>

<script type="text/javascript" src='../js/sha512.js'></script>

<script>
function changepassword()
   {
    def_pass = $('#default_pass').val();
     $('#div_error').hide(); 
     //var passwordsize=$.trim($('#newpwd').val()).length;
     //alert(passwordsize);
    var redigit = /[0-9]/;
    var resmallet = /[a-z]/;
    var reuppercase= /[A-Z]/;
    var respecial= /[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/@]/;
  
     if($.trim($('#currentpwd').val())=="")
      {
        $('#div_error').show();
        $('#div_error').html('Please Enter Current Password.');
        return false;
      }
     else if($.trim($('#newpwd').val())=="" || ($.trim($('#newpwd').val()).length < 8 || $.trim($('#newpwd').val()).length>15))
      {
        $('#div_error').show();
        $('#div_error').html('New Password must contain at least 8-15 characters.');
        
        return false;
      }
     
       else if(!redigit.test($.trim($('#newpwd').val()))) {
       // alert("Error: password must contain at least one number (0-9)!");
       $('#div_error').show();
      $("#div_error").html("password must contain at least one number (0-9)!");
      return false;
      }
    
     else if(!resmallet.test($.trim($('#newpwd').val()))) {
        $('#div_error').show();
      $("#div_error").html("password must contain at least one lowercase letter (a-z)!");
      return false;
      }
      
      else if(!reuppercase.test($.trim($('#newpwd').val()))) {
      $('#div_error').show();
      $("#div_error").html("password must contain at least one uppercasecase letter (A-Z)!");
      return false;
      }
       else if(!respecial.test($.trim($('#newpwd').val()))) {
      $('#div_error').show();
      $("#div_error").html("password must contain at least one special character!");
      //document.getElementById("npwd").focus();
      return false;
      }
    
    else if($.trim($('#confirmpwd').val())=="")
      {
        $('#div_error').show();
        $('#div_error').html('Please Confirm New Password.');
        return false;
      }
      else if($.trim($('#confirmpwd').val())!=$.trim($('#newpwd').val()))
      {
        $('#div_error').show();
        $('#div_error').html('New password and confirm password do not match.');
        return false;
      }
      else if(def_pass == $.trim($('#newpwd').val()))
      {
        $('#div_error').show();
        $('#div_error').html('Default password and New password cannot be same.');
        return false;
      }
      else
      {
        encryptpassword();
          $.ajax({
            type  : "POST",
            url   : "ajaxpassword_change.php",
            data  : $('#form-changepassword').serialize(),
            success : function(msg)
              { 
              console.log(msg);  
                   //$('#dialog-overlay').css('display','none');
                   var element = msg.split('#'); 
                                   var element0 = element[0].trim();
                   if(element0=="Success")
                   {
                    
                    window.location.href = element[1];
                    
                   }
                   else
                   {
                      $('#div_error').show();
                      $('#div_error').html(element[1]);
                      $('input:#currentpwd').val("");
                      $('input:#newpwd').val("");
                      $('input:#confirmpwd').val("");
                     }
              }
            });
        return false;
      }
   }

    function encryptpassword()
     {
       
      var salt='<?php echo htmlentities($_SESSION['slt']); ?>';
       var username=$("#username").val();
       var password=$("#currentpwd").val();
       var password_new=$("#newpwd").val();
       var password_conf=$("#confirmpwd").val();
       var pwdold = encryptShaPwd(salt,username,password);
       var pwdnew = encryptShaPwd("","",password_new);
       var pwdconf= encryptShaPwd("","",password_conf);
      
       $('#currentpwd').val(pwdold);
      
       $('#newpwd').val(pwdnew);
       $('#confirmpwd').val(pwdconf);
      //alert(pwdold);
     }
  function encryptShaPwd(key,struser,strpwd)
    {
      var enc;
      var username=struser;
      var password=strpwd;

      if(struser==''){
      var enc =calcHash("SHA-512",password);
      
      }else{
        
        //var enc =sha256_digest(sha256_digest(password).toString()+"#"+username+"#"+key).toString();
        var enc =calcHash("SHA-512",calcHash("SHA-512",key)+calcHash("SHA-512",password)+calcHash("SHA-512",username));
      }
      
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