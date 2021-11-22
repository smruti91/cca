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


if(isset($_POST['para_id'])){
   $para_id = $_POST['para_id'] ;
   $_SESSION['paraid']=$para_id;
}

if (isset($_SESSION['mngplan_id'])){
$manageplan_id=$_SESSION['mngplan_id'];
$manageplansql= mysqli_query($mysqli,"select plan_name,org_id,team_id,audit_start_date,audit_end_date from cca_manageplan m,cca_plan p where m.plan_id=p.id and m.id='".$manageplan_id."'");
$res_row=mysqli_fetch_array($manageplansql);
$orgname= find_institutionname($res_row['org_id'],$mysqli);
$team_name=find_teamname($res_row['team_id'],$mysqli);


}

if(isset($_POST['edit_id'])){
   $edit_id = $_POST['edit_id'] ;
}
else{
  $edit_id = 0 ;
}

 if( !isset($_SESSION['save_id'])){
       
       $save_id = 0;
 }
 else{
   $save_id = $_SESSION['save_id'];
 }



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
#dtl_advnc{
counter-reset: serial-number;  /* Set the serial number counter to 0 */
}
#dtl_advnc td:first-child:before {
counter-increment: serial-number;  /* Increment the serial number counter */
content: counter(serial-number);  /* Display the counter */
}
#heading p {
float: left;
margin-left: 75px;
margin-bottom: 30px;
font-weight: bold;
}
.frm2{
  height: 100%;
    width: 65%;
}
#frm_records_mtnc{
    clear: both;
    float: left;
    width: 85%;
}
.record_non{
    float: left;
    font-weight: bold;
    margin-left: 75px;
    margin-bottom: 30px;
}
}
</style>
<div id="wrapper">
  <?php include "leftpanel.php";?>
  <div id="page-wrapper">
    <div class="container-fluid text-center">
      <div id="alert_msg" ></div>
      <div class="row content">
        <div class="col-sm-12 text-center">
          <div class="bckbtn" onclick="history.back(-1)"><img src="../images/backb.png" /><b>Back</b></div>
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
                height: 300px;
                margin: 10px auto;
                padding-top: 20px;
                /* overflow: auto;*/
                ">
                
                <br clear="all"/>
                <div class="advnc_div" >
                  <form method="POST" action="ajax_record_maintenance.php" id="frm_dtl_advnc" >
                    <div id="heading"> <p>(i)  List of Records verified. ( Annexure)</p> </div>
                    <div class="advnc-wrap">
                      <table border="1" class="table" id="dtl_advnc" style="font-size: 13px; border: 2px solid #2daab0;">
                        <thead>
                          <tr>
                            <th style="width:10px" >Sl.No</th>``
                            <th>Name of the Records</th>
                            <th>Rules</th>
                            <th>Form No </th>
                            <th>Verified </th>
                            <th>Not Verified</th>
                            <th>Not maintained</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if($edit_id == 1 || $save_id==1 ){
                          include("record_maintenance_template_edit.php");
                          }else{
                          include("record_maintenance_template.php");
                          }
                          
                          ?>
                        </tbody>
                      </table>
                      <?php
                      if($edit_id == 1){
                      ?>
                      <input type="hidden"  name="update" value="Update" />
                      <input type="submit" class="btn btn-primary" value="Update" style="margin-right: 125px;" />
                      <?php
                      }else{
                      ?>
                      <input type="hidden"  name="save" value="Save" />
                      <input type="submit" class="btn btn-success" value="Save" style="margin-right: 125px;" <?php  if($save_id == 1){ ?> disabled <?php } ?> />
                      <?php
                      }
                      
                      ?>
                      
                    </div>
                    
                  </form>
                   
                  
                </div>
                <p class="record_non" >(ii)  Consequences of non-maintenance of records</p> 
                <div class="frm2">
                  <form method="POST" id="frm_records_mtnc" action="ajax_record_maintenance.php">

                        <?php

                             $sql = " SELECT * FROM cca_para_3d1 WHERE para_id = '".$para_id."' AND mngplan_id = '".$manageplan_id."' AND version = 0 ";
                             $res = mysqli_query($mysqli,$sql);
                             $records = mysqli_fetch_assoc($res);
                             //print_r($records);
                            
                             if($records['consequences_records'] != '' ){
                                ?>
                                   <textarea  class="form-control records_mtnc" name="records_mtnc" id="records_mtnc" ><?php echo $records['consequences_records']; ?></textarea>
                                   <input type="submit" class="btn btn-success" id="btn_records_mtnc" name="record_update" value="Update"   style="margin: 5px;" />
                                    <input type="hidden"  name="update_records" value="update_records" />
                                    <input type="hidden"  name="consequences_id" value="<?php echo $records['id']; ?>" />
                                <?php
                             }

                             else{
                                ?>

                                 <textarea  class="form-control records_mtnc" name="records_mtnc" id="records_mtnc"></textarea>
                                 <input type="submit" class="btn btn-success" id="btn_records_mtnc" value="Save"  <?php  if($save_id !== 1){ ?> disabled <?php } ?> style="margin: 5px;" />
                                 <input type="hidden"  name="save_records" value="save_records" />
                                <?php
                             }
                         ?>
                          
                       
                          
                          
                    </form>
                </div>
                   
                
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
<script type="text/javascript" ></script>
<script>
$(document).ready(function() {

  showMessage();


  closeAlertBox();
//submit form

$('#frm_dtl_advnc').submit(function(e){
e.preventDefault();

if( frmvalidate() ){

$('#frm_dtl_advnc')[0].submit();
//console.log('success',12345)
}
else{
alert("You must check atleast one of the checkboxes");
return false;
}
});

tinymce.init({
selector: '.records_mtnc'
});

$('#frm_records_mtnc').submit(function(e) {
    e.preventDefault();

    console.log(123);
    $('#frm_records_mtnc')[0].submit();

});



} );

function frmvalidate(){
var c=document.getElementsByTagName('input');
console.log('c',c)
for (var i = 0; i<c.length; i++){
if (c[i].type=='checkbox')
{
if (c[i].checked){return true}
}
}
return false;

}


// the selector will match all input controls of type :checkbox
// and attach a click event handler
$("input:checkbox").on('click', function() {
// in the handler, 'this' refers to the box clicked on
var $box = $(this);
if ($box.is(":checked")) {
// the name of the box is retrieved using the .attr() method
// as it is assumed and expected to be immutable
var group = "input:checkbox[class='" + $box.attr("class") + "']";
console.log(group);
// the checked state of the group/box on the other hand will change
// and the current value is retrieved using .prop() method
$(group).prop("checked", false);
$box.prop("checked", true);
} else {
$box.prop("checked", false);
}
});
</script>