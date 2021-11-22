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

}

 $sql_para  = " SELECT * FROM cca_para_2b WHERE para_id = '".$_SESSION['paraid']."' AND mngplan_id = '".$manageplan_id."' AND version = 0 " ; 
 $sql_para_res   = mysqli_query($mysqli,$sql_para);
 $row_cnt = $sql_para_res->num_rows;
 $edit_id = 0;

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
             <form  method="POST" id="irreg_form" >
                 <div class=" right" style="float: right;" ><img src="../images/plus.png" />
                <a  onclick="add_more();" href="Javascript:void(0);" style=" color:#1a629c; ">Add more</a></div>
                <br clear="all"/>
                      <div class="cmp_div" >
                     <?php 

                        include "irreg_template.php" ; 
                     
                     ?>
                     </div>
                     <div class="row">
                         <div class="col-md-6">
                          
                         </div>
                          <div class="col-md-6">

                            <input type="submit" class="btn btn-primary btn_save" name="save_irreg" value="Save" style="display:  <?php echo $row_cnt > 0 ? 'none' : ''  ?>"/>
                           
                          </div>
                    </div>
              </form>

              <hr>

                <?php
                   if($row_cnt > 0){
                      include "irreg_template_edit.php" ; 
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
 
  <script src="../tinymce/tinymce.min.js"></script>

  <script>
  $(document).ready(function() {
  
   showMessage();

  closeAlertBox();

     
 tinymce.init({
     // selector: '.irreg_notice'
        mode : "specific_textareas",
        editor_selector : "irreg_notice"  
  });


  } );

       function frmvalidate(){

        
          var irreg_notice = tinymce.get("irreg_notice").getContent();
        
           
             irreg_notice = irreg_notice.replace(/^\<p\>/,"").replace(/\<\/p\>$/,"");
         
          $(".error").remove();
          
          if( irreg_notice.length < 1 ){
             $('#irreg_notice').after('<span class="error"> * This field should not be Empty</span>');
              return false;
          }
      
      return true;
  }


    $('#irreg_form').submit(function(e){
         e.preventDefault();

         if( frmvalidate() ){
           tinyMCE.triggerSave();
           $.ajax({
               type: 'POST',
               url: 'ajax_persistent_irreg',
               data: $('#irreg_form').serialize(),
               success: function(res){
                 console.log(res);
                 sessionStorage.setItem('message', 'Audit Observations and Compliance added successfully') ; 
                 sessionStorage.setItem('type', 'success');
                 location.reload();
                 
               }
           })
         }

     })


function add_more(){
  $('.subdiv').show();
  $('.btn_save').show();
}
     function show_div(id){

   
    $('.del_'+id).show();
    $('.edit_'+id).show();

    // set readony proerty to fields
    tinyMCE.get('irreg_notice'+id).setMode("readonly");
    
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

  function edit_irreg(id){

      $('.edit_'+id).hide();
      $('.update_'+id).show();
     tinyMCE.get('irreg_notice'+id).getBody().setAttribute('contenteditable', true);
  }

  //update form

  function update_cmp(id){

         var irreg_notice   = tinyMCE.get('irreg_notice'+id).getContent();
        
         $.ajax({
           type:'POST',
           url: 'ajax_persistent_irreg',
           data: {edit_id:id,irreg_notice:irreg_notice,action:'Update_irreg'},
           success:function(res){
               console.log(res);
                sessionStorage.setItem('message', 'Assessment Aspect updated successfully') ; 
                sessionStorage.setItem('type', 'success');
                 location.reload();
           }
         });

  }

$("body").on("click",".remove",function(){
$(this).parents(".control-group").remove();
});

function del_irreg(id){
    var irreg_notice    = tinyMCE.get('irreg_notice'+id).getContent();

         if(irreg_notice != '' ){
                $('.btn-dlt').prop('disabled', true);
            }
           else{
                $('.btn-dlt').prop('disabled', false);
             }

      $("#deleteIrregeModal_"+id).modal('show');
}

function delete_record(id){
 var $ele = $("#deleteIrregeModal_"+id).parent().parent();
  $.post('ajax_persistent_irreg.php',{action:'delete',edit_id:id },
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
