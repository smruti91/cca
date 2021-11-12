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
</style>
<div id="wrapper">
  <?php include "leftpanel.php";?>
  <div id="page-wrapper">
    <div class="container-fluid text-center">
       <div id="alert_msg" ></div> 
      <div class="row content">
        <div class="col-sm-12 text-center">
          <button class="btn btn-warning bckbtn" onclick="history.back(-1)"><img src="../images/backb.png" /><b>Back</b></button>
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
             <form  id="obs_form" method="POST" action="ajax_prev_auditObs.php" >
                <div class="right" style="float: left;" ><img style="width: 15px;" src="../images/report_icon3.png" />
                <a  onclick="get_complince_report();" href="Javascript:void(0);" style=" color:#1a629c; ">Report</a></div>
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
                          <!-- <button  class="btn btn-primary" name="Update_complaince" > Update</button> -->
                          <input type="submit" class="btn btn-primary" name="Update_complaince" value="Update" />
                       <?php
                      }else{
                        ?>
<!--                            <button class="btn btn-primary" name="save_complaince" > save</button> -->
                          <input type="submit" class="btn btn-primary" name="save_complaince" value="Save" />
                        <?php
                      }
                     

                     ?>
                            
                           
                          </div>
                    </div>
              </form>

              <!-- Report Modal HTML -->
                                              <div id="reportComplainceModal" class="modal fade">
                                                <div class="modal-dialog" style="width: 1300px;">
                                                  <div class="modal-content">
                                                   
                                                      <div class="modal-header">            
                                                        <h4 class="modal-title">Complaince Report </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                      </div>
                                                      <div class="modal-body">          
                                                        <?php
                                                             include "prev_audit_obs_report.php";
                                                         ?>
                                                       
                                                      </div>
                                                      <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                                        
                                                      </div>
                                                   
                                                  </div>
                                                </div>
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
  
   
     showMessage();

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

    $('#obs_form').submit(function(e){
        e.preventDefault();

         frmvalidate();
         
        if( frmvalidate() ){
          $('#obs_form')[0].submit();
        }

    })


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
      html +=           '<div class="col-md-6 lbl">'
      html +=           '<div class="col-md-4">'
      html +=              ' <label for="">Audit Type</label>'
      html +=             '</div>'
      html +=             '<div class="col-md-8">'
      html +=              '<div class="form-group">'
      html +=                '<select  class="form-control" name="audit_type[]" required>'
      html +=                  '<option>  Audit Type</option>'
      html +=                   '<option>IR</option>'
      html +=                  '<option>IAR</option>'
      html +=                  '<option>EAR</option>'
      html +=                '</select>'
      html +=             '</div>'
      html +=           '</div>'
      html +=        '</div>'
      html +=       '<div class="col-md-6 lbl">'
      html +=       '<div class="col-md-7">'
      html +=           '<label for="">Audit Report No.</label>'
      html +=       '</div>'
      html +=      '<div class="col-md-5">'
      html +=       '<input type="text" class="form-control" name="audit_no[]"  id="audit_no" placeholder="Audit Report No." required>'
      html +=          '</div>'
      html +=        '</div>'
       html +=  '</div>'
      html +=              '<div class="row">'
      html +=                 '<div class="col-md-6 lbl">'
      html +=                    '<div class="col-md-4">'
      html +=                       '<label for="">Year</label>'
      html +=                    '</div>'
      html +=                   '<div class="col-md-8">'
      html +=                    '<input type="text" class="form-control" name="year[]"  id="year" placeholder="Year of accounts" required>'
      html +=                 '</div>'
      html +=              '</div>'
      html +=              '<div class="col-md-6 lbl">'
      html +=               '<div class="col-md-7">'
      html +=                  '<label for="">No. of objection paras  </label>'
      html +=              '</div>'
      html +=              '<div class="col-md-5">'
      html +=                '<input type="text" class="form-control" name="no_obs_para[]"  id="no_obs_para" placeholder="No. of Paras"  required>'
      html +=             '</div>'
      html +=           '</div>'
      html +=        '</div>'
      html +=     '<div class="row" style="margin: 5px;">'
      html +=       '<div class="col-md-6 lbl">'
      html +=         '<label for="">Audit Observation </label>'
      html +=        '<div class="form-group">'
      html +=          '<textarea  class="form-control audit_obs2" name="audit_obs[]" style="height:150px" ></textarea>'
      html +=        '</div>'
      html +=     '</div>'
      html +=     '<div class="col-md-6 lbl">'
      html +=       '<label for="" style="margin-left: 15px;">Complaince </label>'
      html +=      '<div class="form-group">'
      html +=       '<textarea  class="form-control complaince2" name="complaince[]"   style="height:150px" ></textarea>'
      html +=    '</div><input type="hidden" name="para_edit_id[]" value= -1 >'
     
  
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

function get_complince_report(){
   $('#reportComplainceModal').modal('show');
}

</script>
