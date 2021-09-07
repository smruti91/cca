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
</style>
<div id="wrapper">
  <?php include "leftpanel.php";?>
  <div id="page-wrapper">
    <div class="container-fluid text-center">
       <div id="alert_msg" ></div> 
      <div class="row content">
        <div class="col-sm-12 text-center">
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
             <form action="ajax_prev_complaince.php" method="POST" >
                 <div class=" right" style="float: right;" ><img src="../images/plus.png" />
                <a  onclick="get_complince_html_row();" href="Javascript:void(0);" style=" color:#1a629c; ">Add another Complaince</a></div>
                <br clear="all"/>
                      <div class="cmp_div" >
                     <?php 
                      if($edit_id == 1 ){
                        include "prev_complaince_template_edit.php" ; 
                      }else{
                         include "prev_complaince_template.php" ; 
                      }
                     
                     ?>
                     </div>
              <div class="row">
                         <div class="col-md-6">
                          
                         </div>
                          <div class="col-md-6">
                             <?php 
                      if($edit_id == 1 ){
                       ?>
                          <button class="btn btn-primary" name="Update_complaince" > Update</button>
                       <?php
                      }else{
                        ?>
                           <button class="btn btn-primary" name="save_complaince" > save</button>
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


  } );

  tinymce.init({
      selector: '.audit_obs1'
  });

   tinymce.init({
      selector: '.complaince1'
  });

   function closeAlertBox(){
window.setTimeout(function () {
  $("#alert_msg").fadeOut(300)
}, 3000);
} 


  var count = 0;
  function get_complince_html_row(){
  count = count+1;
 
  var  html = "";
  html +=  '   <div class="after-add-more control-group subdiv" id="rowdiv_'+count+'"><button type="button" class="close remove" style="color:red;" aria-label="Close"><span aria-hidden="true">&times;</span></button><br><div class="row">'
      html +=           '<div class="col-md-4">'
      html +=           '<div class="col-md-4">'
      html +=              ' <label for="">Audit Type</label>'
      html +=             '</div>'
      html +=             '<div class="col-md-8">'
      html +=              '<div class="form-group">'
      html +=                '<select  class="form-control" name="audit_type[]" >'
      html +=                  '<option>  Audit Type</option>'
      html +=                   '<option>IR</option>'
      html +=                  '<option>IAR</option>'
      html +=                  '<option>EAR</option>'
      html +=                '</select>'
      html +=             '</div>'
      html +=           '</div>'
      html +=        '</div>'
      html +=       '<div class="col-md-3">'
      html +=       '<div class="col-md-4">'
      html +=           '<label for="">Year</label>'
      html +=       '</div>'
      html +=      '<div class="col-md-8">'
      html +=       '<input type="text" class="form-control" name="year[]"  id="year" placeholder="Year of accounts" >'
      html +=          '</div>'
      html +=        '</div>'
      html +=        '<div class="col-md-5">'
      html +=       '<div class="col-md-7">'
      html +=           '<label for="">No. of objection paras </label>'
      html +=        '</div>'
      html +=         '<div class="col-md-5">'
      html +=           '<input type="text" class="form-control" name="no_obs_para[]"  id="no_obs_para"  >'
      html +=         '</div>'
      html +=      '</div>'
      html +=    '</div>'
      html +=     '<div class="row" style="margin: 5px;">'
      html +=       '<div class="col-md-6">'
      html +=         '<label for="">Audit Observation </label>'
      html +=        '<div class="form-group">'
      html +=          '<textarea  class="form-control audit_obs2" name="audit_obs[]"  row="5" style="height:150px" ></textarea>'
      html +=        '</div>'
      html +=     '</div>'
      html +=     '<div class="col-md-6">'
      html +=       '<label for="">Complaince </label>'
      html +=      '<div class="form-group">'
      html +=       '<textarea  class="form-control complaince2" name="complaince[]"   style="height:150px" ></textarea>'
      html +=    '</div><input type="hidden" name="para_edit_id[]" value= -1 >'
      html +=  '</div>'
  
      html +=  '</div>'
      html +=   '</div>' ;

var N = $(".cmp_div > div").length;
 
if(N){

$(".cmp_div").after(html);
tinymce.init({
      selector: '.audit_obs2'
  });

   tinymce.init({
      selector: '.complaince2'
  });
}
else{
cnt = count-1;

$("#rowdiv_"+cnt).after(html);
}
//console.log(html);
}
$("body").on("click",".remove",function(){
$(this).parents(".control-group").remove();
});

function del_cmp(id){

      $("#deleteComplainceModal_"+id).modal('show');
}

function delete_record(id){
 var $ele = $("#deleteComplainceModal_"+id).parent().parent();
  $.post('ajax_prev_complaince.php',{action:'delete',edit_id:id },
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
</script>
