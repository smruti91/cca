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
                           <form method="POST" action="ajax_details_advance.php" id="frm_dtl_advnc" >
                             
                             <div class="advnc-wrap">
                      <table border="1" class="table" id="dtl_advnc" style="font-size: 13px; border: 2px solid #2daab0;">
                        <thead>
                          <tr>
                          <th style="width:10px" >Sl.No</th>``
                          <th>Advance outstanding as on</th>
                          <th>Cash book </th>
                          <th>Amount Out standing</th>
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

                                     include("details_advance_edit_template.php");

                                 }else{

                                  include("details_advance_template.php");
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
  
  if ( sessionStorage.type=="success" ) {
            $('#alert_msg').show();
              console.log(123);
             $("#alert_msg").addClass("alert alert-success").html(sessionStorage.message);
             closeAlertBox();
               //sessionStorage.reloadAfterPageLoad = false;
             sessionStorage.removeItem("message");
             sessionStorage.removeItem("type");
       }
     if(sessionStorage.type=="error")
     {
        $('#alert_msg').show();

             $("#alert_msg").addClass("alert alert-danger").html(sessionStorage.message);
             closeAlertBox();

             sessionStorage.removeItem("message");
             sessionStorage.removeItem("type");
     }



    $('#frm_dtl_advnc').submit(function(e){
        e.preventDefault();

          $('#frm_dtl_advnc')[0].submit();

    })


  } );

  
   function closeAlertBox(){
window.setTimeout(function () {
  $("#alert_msg").fadeOut(300)
}, 3000);
} 


  
  function create_tr(){
      
     var num = Math.random().toString(16).slice(2)
      var counter = 1;
     var data = $("#dtl_advnc tr:eq(1)").clone(true).attr( 'id',  num ).appendTo("#dtl_advnc");
    // console.log(data);
        data.find("input").val('');

        //.attr( 'id', this.id + '_' + num )
    
  }

function remove_tr(This){
  let tr = This.closest('tr');
    

   if(This.closest('tbody').childElementCount == 1 ){
      alert('You can`t delete this row');
   }
   else{
       This.closest('tr').remove();
   }
 
}

// $('.outStanding_cashBook').on( 'keyup' function(){

//     console.log(123);
// })

$('.outStanding_cashBook').keyup(function(event) {
  
        //alert('You released a key');
        // var trclass = $(this).parent();
        // var r1 = $(this).closest(".outStanding_cashBook");
        // console.log(r1);

       var amt_audit =  $('.outStanding_audit').val();
       var amt_cashbook = $('.outStanding_cashBook').val();
       var diff = amt_audit - amt_cashbook;
        console.log(amt_audit - amt_cashbook);

        $('.difference').val(diff);

    });



</script>