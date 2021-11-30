<?php
session_start();
include("../common_functions.php");
include_once("../config.php");
$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
if($userid == -1)
{
header('location:../index.php');
exit;
}
?>
<?php include "header.php"; ?>
<?php
if( isset($_POST['para_id']) && $_POST['para_id'] != ''){
$_SESSION['paraid']=$_POST['para_id'];
}
if (isset($_SESSION['mngplan_id'])){
$manageplan_id=$_SESSION['mngplan_id'];
$manageplansql= mysqli_query($mysqli,"select plan_name,org_id,team_id,audit_start_date,audit_end_date from cca_manageplan m,cca_plan p where m.plan_id=p.id and m.id='".$manageplan_id."'");
$res_row=mysqli_fetch_array($manageplansql);
$orgname= find_institutionname($res_row['org_id'],$mysqli);
$team_name=find_teamname($res_row['team_id'],$mysqli);

}
$edit_id = isset($_POST['edit_id'])?$_POST['edit_id']:0;
?>
<style>
div.main{
height:100%;
}
.subdiv{
background-color: #f8f8f8;
margin: 10px;
padding: 10px;
box-shadow: 0px 0px 3px #aaa;
border-radius: 10px;
border: 2px solid #fff;
}
#alert_msg{
/* position:absolute;*/
z-index:1400;
top:2%;
right:4%;
margin:10px auto;
text-align:center;
display:none;
}
.error{
color: red;
}
.lbl{
text-align: left;
}
.class1{
color: orange;
}
</style>
<div id="wrapper">
  <?php include "leftpanel.php";?>
  <div id="page-wrapper">
    <div class="container-fluid text-center">
      <div id="alert_msg" ></div>
      <div class="row content">
        <div class="col-sm-12 text-center">
          <div class="bckbtn" onclick="location.href='manage_auditreport'"><img src="../images/backb.png" /><b>Back</b></div>
          <h1>Manage Audit Report</h1>
          <hr>
          <div style="    width: 100%;
            background-color: #42c19f2e;
            padding: 5px;
            border: 3px solid #2daab0;
            
            ">
            <div style="width:33%;float:left;">
              Plan Name: <?php echo $res_row['plan_name']; ?></br>
              Party Name: <?php echo $team_name; ?> </br>
              <?php
              $teamsql= mysqli_query($mysqli,"select u.id,em.leader_id,u.Name from cca_users u,cca_team_emp_role em where u.id=em.emp_id and em.team_id='".$res_row['team_id']."'");
              while($team_row=mysqli_fetch_array($teamsql)){
              echo $team_row['Name'];
              if($team_row['id']==$team_row['leader_id']){
              echo "<b> (Lead Auditor)</b>";
              }else{
              echo "<b> (Auditor)</b>";
              }
              echo "<br>";
              }
              ?>
            </div>
            <div style="width:33%;float:left;">
              <b><?php echo $orgname; ?></b> -  General Audit
            </div>
            
            <div style="clear:both;background-color: #ffb75b;">
              <h3><?php echo findpara_head($_SESSION['paraid'],$mysqli);?></h3>
            </div>
          </div>
          <div class="calheader" style="padding-bottom: 50px; *background: url(../images/auditimg.jpg);background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-size: cover;">
            <fieldset>
              <div class=" main-para"
                style="
                width: 85%;
                height: 300px;
                margin: 10px auto;
                padding-top: 20px;
                ">
                <form  method="POST" id="frm_audit_result" >
                  
                  <br clear="all"/>
                  <div class="cmp_div" >
                    <?php
                    $result = '';
                    if($edit_id == 1 ){
                    
                    
                    $sql_para  = " SELECT * FROM cca_para_5 WHERE para_id = '".$_POST['para_id']."' AND
                    mngplan_id = '".$manageplan_id."' AND version = 0 ";
                    
                    $para_res  = mysqli_query($mysqli,$sql_para);
                    $para_row  = mysqli_fetch_assoc($para_res);
                    if($para_row){
                    $result = $para_row['audit_result'];
                    }
                    
                    }
                    ?>
                    <div class="after-add-more subdiv">
                      <div class="col-md-6">
                        <label class="control-label" style="float: left;">Result of Audit <br> <span style="font-size: 13px;font-weight: 200;">( Improvement and Recommendations for compliance )</span></label>
                      </div>
                      
                      <div class="row" style="margin: 5px;">
                        <div class="col-md-12">
                          
                          <div class="form-group  ">
                            <textarea  class="form-control audit_result" name="audit_result" id="audit_result"><?php echo $result ?></textarea>
                          </div>
                        </div>
                        
                        <input type="hidden" name="save_result" value= <?php echo $edit_id ? 'edit':'save' ?> >
                        <input type="hidden" name="edit_id" value= "<?php echo isset($para_row)?$para_row['id']:'' ?>" <?php echo $edit_id ? '' : 'disabled' ?>  >
                      </div>
                      
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      
                    </div>
                    <div class="col-md-6">
                      <?php
                      if($edit_id == 1 ){
                      ?>
                      <input type="submit" class="btn btn-primary" name="Update_irreg" value="Update" />
                      <?php
                      }else{
                      ?>
                      <input type="submit" class="btn btn-primary" name="save_irreg" value="Save" />
                      <?php
                      }
                      
                      ?>
                      
                      
                    </div>
                  </div>
                </form>
              </div>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!--  <div class="clear:both;"></div> -->
</div>
<?php include "footer.php"; ?>
<!-- Bootstrap Date-Picker Plugin -->
<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>
<script src="../tinymce/tinymce.min.js"></script>
<script>
$(document).ready(function() {
showMessage();
closeAlertBox();
tinymce.init({
selector: '.audit_result',
});
});
//form validte
function frmvalidate(){
var audit_result = tinyMCE.get('audit_result').getContent();
if (audit_result == '') {
$('.audit_result').after('<span class="error"> * This field can not be blank</span>');
return false;
}
return true;
}
//save form
$('#frm_audit_result').submit(function(e){
e.preventDefault();
if(frmvalidate()){
tinyMCE.triggerSave();
$.ajax({
type: 'POST',
url: 'ajax_audit_result',
data: $('#frm_audit_result').serialize(),
success: function(res){
sessionStorage.setItem('message', 'Audit Result ' +res+ ' successfully') ;
sessionStorage.setItem('type', 'success');
window.location = 'manage_auditreport.php'
}
})
}
})
</script>