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
if (isset($_SESSION['mngplan_id'])){
$manageplan_id=$_SESSION['mngplan_id'];
$manageplansql= mysqli_query($mysqli,"select plan_name,org_id,team_id,audit_start_date,audit_end_date from cca_manageplan m,cca_plan p where m.plan_id=p.id and m.id='".$manageplan_id."'");
$res_row=mysqli_fetch_array($manageplansql);
$orgname= find_institutionname($res_row['org_id'],$mysqli);
$team_name=find_teamname($res_row['team_id'],$mysqli);
$_SESSION['paraid']=$_POST['para_id'];
//print_r($res_row);
 
}

 $edit_id =  $_POST['edit_id'];
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
                           <form method="POST" action="ajax_advance_position.php" id="frm_dtl_advnc" >
                             
                             <div class="advnc-wrap">
                      <table border="1" class="table" id="dtl_advnc" style="font-size: 13px; border: 2px solid #2daab0;">
                        <thead>
                          <tr>
                          <th style="width:10px" >Sl.No</th>``
                          <th>Name of the Official</th>
                          <th>GPF/e-HRMS Id</th>
                          <th>Cash Book </th>
                          <th>Amount Out standing as on </th>
                          <th>Amount paid during the audit period</th>
                          <th>Amount Adjusted</th>
                          <th>Balance as on</th>
                          <th>Amount outstanding (Audit)</th>
                          <th>Amount outstanding (Cash Book)</th>
                          <th>Difference</th>
                          <th><button class="btn btn-success" onclick="create_tr('dtl_advnc')" ><i class="fa fa-plus" aria-hidden="true"></i></button></th>
                          </tr>
                        </thead>
                        <tbody>
                             <?php
                                 if($edit_id == 1){

                                     include("advance_position_template_edit.php");

                                 }else{

                                  include("advance_position_template.php");
                                 }
                                 
                              ?>
                        </tbody>
                      </table>
                            <?php
                                 if($edit_id == 1){

                                      ?>
                                       <input type="hidden"  name="update" value="Update" />

                                      <input type="submit" class="btn btn-primary" value="Update" />
                                   <?php

                                 }else{
                                   ?>
                                   <input type="hidden"  name="save" value="Save" />
                                   <input type="submit" class="btn btn-success" value="Save" />
                                   <?php
                                 }
                                 
                              ?>
                      
                    </div>

                            
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

  <script>
  $(document).ready(function() {
  
    $('#frm_dtl_advnc').submit(function(e){
        e.preventDefault();

          $('#frm_dtl_advnc')[0].submit();
    })



  });

  showMessage();

  closeAlertBox();


  function create_tr(){
      
     var num = Math.random().toString(16).slice(2)
     
     var data = $("#dtl_advnc tr:eq(1)").clone(true).appendTo("#dtl_advnc");
    
         data.find("input").val('');
         data.find("input").prop('id','test_'+num);
         data.find("a").prop('id','0');
        
  }
 
 function remove(id){
  
   var ele = document.getElementById("0");
      
    if(id == 0){
         remove_tr(ele);
     
    }
    else{
        
      $('#deleteAdvanceModal_'+id).modal()
     
    }
    
 }

 function delete_record(id){
        var $ele = $("#deleteAdvanceModal_"+id).parent().parent();
      $.post('ajax_advance_position.php',{action:'delete',edit_id:id },
      function(res){
        console.log(res);
        if(res=='success'){
            $("#deleteAdvanceModal_"+id).hide();
            $(".modal-backdrop").hide();
             $ele.fadeOut().remove();
           sessionStorage.setItem("type", "error");
           sessionStorage.setItem("message", "Record Deleted Successfully");

            window.location.reload();
        }
      } );
 }


function remove_tr(This){
  let tr = This.closest('tr');
    
    console.log(tr);
   if(This.closest('tbody').childElementCount == 1 ){
      alert('You can`t delete this row');
   }
   else{
       This.closest('tr').remove();
   }
 
}




$('.outStanding_cashBook').keyup(function(event) {
  
       
        var currentRow =  $(this).closest("tr");
        var col1 = currentRow.find(".amut_outStanding_audit").val();

        var col2 = currentRow.find(".outStanding_cashBook").val();

        var diff = col1 - col2;
     
        currentRow.find(".difference").val(diff);

    });

$('#dtl_advnc tr').each(function(i){

     if(i==0) return true;

     var tr = $(this);
     var num1 = parseInt($('td',tr).eq(8).find(".amut_outStanding_audit").val());
     var num2 = parseInt($('td',tr).eq(9).find(".outStanding_cashBook").val() );
     
     $('td',tr).eq(10).find(".difference").val(num1 - num2);
});

function delete_record(id){
 
  $.post('ajax_advance_position.php',{action:'delete',edit_id:id },
    
  function(res){
   
    if(res=='success'){
      
       sessionStorage.setItem("type", "error");
       sessionStorage.setItem("message", "Record Deleted Successfully");

        window.location.reload();
    }
  } );
}


</script>