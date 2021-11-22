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
 
      $sql_para  = " SELECT * FROM cca_para_2a WHERE para_id = '".$_SESSION['paraid']."' AND mngplan_id = '".$manageplan_id."' AND version = 0 "; 
      $sql_para_res   = mysqli_query($mysqli,$sql_para);
     
      $row_cnt = $sql_para_res->num_rows;
      
      $edit_id =  0;

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
             <form  id="obs_form" method="POST"  >
                <div class="right" style="float: left;" ><img style="width: 15px;" src="../images/report_icon3.png" />
                <a  onclick="get_complince_report();" href="Javascript:void(0);" style=" color:#1a629c; ">Report</a></div>
                 <div class=" right" style="float: right;" ><img src="../images/plus.png" />
               <!--  <a  onclick="get_complince_html_row();" href="Javascript:void(0);" style=" color:#1a629c; ">Add another Complaince</a></div> -->
                <a  onclick="add_more();" href="Javascript:void(0);" style=" color:#1a629c; ">Add more</a></div>
                <br clear="all"/>
                      <div class="cmp_div" >
                     <?php 
                          include "prev_complaince_template.php" ;
                                          
                     ?>
                     </div>
              <div class="row">
                         <div class="col-md-6">
                          
                         </div>
                          <div class="col-md-6">
                             <?php 
                      if($edit_id == 1 ){
                       ?>
                             <input type="submit" class="btn btn-primary" name="Update_complaince" value="Update" />
                       <?php
                      }else{
                        ?>
                          <input type="submit" class="btn btn-primary btn_save" style="display:  <?php echo $row_cnt > 0 ? 'none' : ''  ?>" name="save_complaince" value="Save" />
                        <?php
                      }
                     

                     ?>
                            
                           
                          </div>
                    </div>
              </form>

              <hr>

                <?php
                   if($row_cnt > 0){
                      include "prev_complaince_template_edit.php" ; 
                      ?>
                       <!-- <input type="button" class="btn btn-primary"  id="save_assesment" value="Save All" onclick="save_all()" /> -->
                      <?php
                   }
                
                 ?>
              
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

  } );

  $('#obs_form').submit(function(e){
        e.preventDefault();


        if(frmvalidate()){
           
           tinyMCE.triggerSave();
           $.ajax({
               type: 'POST',
               url: 'ajax_prev_auditObs',
               data: $('#obs_form').serialize(),
               success: function(res){
                 console.log(res);
                 sessionStorage.setItem('message', 'Audit Observations and Compliance added successfully') ; 
                 sessionStorage.setItem('type', 'success');
                 location.reload();
                 
               }
           })
        }

    });

       function frmvalidate(){

          var year        = $('#year').val();
          var no_obs_para = $('#no_obs_para').val();
          var audit_no    = $('#audit_no').val();
         //console.log(year);
         
          $(".error").remove();
          
          
          if (year.length < 4) {
              $('#year').after('<span class="error"> * Year not valid</span>');
              return false;
          }
          if (isNaN(year)) {
              $('#year').after('<span class="error"> * Year not valid</span>');
              return false;
          }

          if( no_obs_para < 1 ){
             $('#no_obs_para').after('<span class="error"> * Not a Number</span>');
              return false;
          }

      return true;
  }

  tinymce.init({
     
        mode : "specific_textareas",
        editor_selector : "audit_obs"  
  });

   tinymce.init({
    
        mode : "specific_textareas",
        editor_selector : "complaince"  
  });

   function add_more(){
     $('.subdiv').show();
     $('.btn_save').show();

   }

   function show_div(id){

 
  $('.del_'+id).show();
  $('.edit_'+id).show();

  // set readony proerty to fields
  tinyMCE.get('audit_obs_'+id).setMode("readonly");
  tinyMCE.get('complaince_'+id).setMode("readonly");
  $('#audit_no_'+id).prop('disabled',true);
  $('#year_'+id).prop('disabled',true);
  $('#no_obs_para_'+id).prop('disabled',true);

   $('#test_'+id).toggle();
  
  var color = $('#test_'+id).is(':hidden') ? '#296c0e' : '#1b7a7e';
  var text  = $('#test_'+id).is(':hidden') ? 'View' : 'Hide';

  var del   = $('#test_'+id).is(':hidden') ? 'none' : '';

    $('.view_'+id).css({'background': color });
    $('.view_'+id).val(text);
   

   //hide del & edit on click hide btn 
    $('.del_'+id).css({ "display": del});   
    $('.edit_'+id).css({ "display": del});
   
}

//Edit from
function edit_cmp(id){

  $('.edit_'+id).hide();
  $('.update_'+id).show();

  //disable readonly property of fields
  $('#audit_type_'+id).prop('disabled',false);
  $('#audit_no_'+id).prop('disabled',false);
  $('#year_'+id).prop('disabled',false);
  $('#no_obs_para_'+id).prop('disabled',false);
  
  tinyMCE.get('audit_obs_'+id).getBody().setAttribute('contenteditable', true);
  tinyMCE.get('complaince_'+id).getBody().setAttribute('contenteditable', true);
 

}

//update form

function update_cmp(id){

       var audit_type =  $('#audit_type_'+id).val();
       var audit_no   =  $('#audit_no_'+id).val();
       var year       =  $('#year_'+id).val();
       var no_obs_para   =  $('#no_obs_para_'+id).val();

       var audit_obs    = tinyMCE.get('audit_obs_'+id).getContent();
       var complaince   = tinyMCE.get('complaince_'+id).getContent();
      
   
       $.ajax({
         type:'POST',
         url: 'ajax_prev_auditObs',
         data: {edit_id:id,audit_type:audit_type,audit_no:audit_no,year:year,no_obs_para:no_obs_para,audit_obs:audit_obs,complaince:complaince,action:'Update_complaince'},
         success:function(res){
             
              sessionStorage.setItem('message', 'Assessment Aspect updated successfully') ; 
              sessionStorage.setItem('type', 'success');
               location.reload();
         }
       });

}

//delete functionlity
function del_cmp(id){
  
       var audit_obs    = tinyMCE.get('audit_obs_'+id).getContent();
       var complaince   = tinyMCE.get('complaince_'+id).getContent();
   
      if(audit_obs != '' && complaince != ''){
         $('.btn-dlt').prop('disabled', true);
      }
      else{
         $('.btn-dlt').prop('disabled', false);
      }

       $("#deleteComplainceModal_"+id).modal('show');
       //$("#deleteComplainceModal_"+id).modal('show');
}

function delete_record(id){
 var $ele = $("#deleteComplainceModal_"+id).parent().parent();
  $.post('ajax_prev_auditObs.php',{action:'delete',edit_id:id },
  function(res){
    console.log(res);
    if(res=='success'){
       $("#deleteEmployeeModal_"+id).hide();
       $(".modal-backdrop").hide();
       $ele.fadeOut().remove();
       sessionStorage.setItem("type", "error");
       sessionStorage.setItem("message", "Record Deleted Successfully");

        window.location.reload();
    }
  } );
}

$("body").on("click",".remove",function(){
$(this).parents(".control-group").remove();
});



function get_complince_report(){
   $('#reportComplainceModal').modal('show');
}

</script>
