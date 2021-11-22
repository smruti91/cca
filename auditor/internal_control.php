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


     $sql_para  = " SELECT * FROM cca_para_4a WHERE para_id = '".$_SESSION['paraid']."' AND mngplan_id = '".$manageplan_id."' AND version = 0 ";
    
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
             <form  id="frm_internal_review" method="POST"  >
               <!--   <div class=" right" style="float: right;" ><img src="../images/plus.png" />
                <a  onclick="add_more_div();" href="Javascript:void(0);" style=" color:#1a629c; ">Add more</a></div> -->
                <br clear="all"/>
                      <div class="cmp_div" >
                     <?php 
                       include "internal_control_template.php" ; 
                     
                     
                     ?>
                     </div>
              <div class="row">
                         <div class="col-md-6">
                          
                         </div>
                          <div class="col-md-6">
                             <?php 
                      if($edit_id == 1 ){
                       ?>
                         
                          <input type="submit" class="btn btn-primary" name="Update_complaince" value="Update" style="float: left;" />
                       <?php
                      }else{
                        ?>

                          <input type="submit" class="btn btn-primary" name="save_complaince" id="btn_save" value="Add" style="float: left; display: none;" />
                        <?php
                      }
                     

                     ?>
                            
                           
                          </div>
                    </div>
                   
              </form>

               <hr>
                     <?php
                        if($row_cnt > 0){
                           include "internal_control_template_edit.php" ; 
                           ?>
                            <input type="submit" class="btn btn-primary"  id="save_assesment" value="Save All" onclick="save_all()" />
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

  //select one checkbox


$('input[type=checkbox]').on('click',function(){
            // in the handler, 'this' refers to the box clicked on
  var $box = $(this);
  if ($box.is(":checked")) {
    // the name of the box is retrieved using the .attr() method
    // as it is assumed and expected to be immutable
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    // the checked state of the group/box on the other hand will change
    // and the current value is retrieved using .prop() method
    $(group).prop("checked", false);
    $box.prop("checked", true);
  } else {
    $box.prop("checked", false);
  }
})


  $(document).on('change' , '.assessment',function(){

           $('#btn_save').show();
          var m = this.value;
          //var id = $('.field-control').attr('id');
         var id = this.id;
         var myid = id.split('_');
         //console.log(id);
          //console.log(m);
          if(m == 0){
            $('#test_'+myid[1]).hide();
          }
          else{
             $('#test_'+myid[1]).show();
          }
         
     });

  tinymce.init({
      //selector: '.strong',
        mode : "specific_textareas",
        editor_selector : "strong"  
   
  });

   tinymce.init({
      //selector: '.weak'
        mode : "specific_textareas",
        editor_selector : "weak"  
  });
 

//clone div under check

function create_div(){

       
       var num = Math.random().toString(16).slice(2);
      
       var data = $("#subdiv").clone(true).attr('id', 'subdiv_'+ num).appendTo(".cmp_div");

       data.find(".field-control").prop('id','test_'+num).attr('style', 'display:none');
       data.find(".assessment").prop('id','asmnt_'+num);
       data.find("input").prop('id',num);
       data.find("input:checkbox").removeAttr('checked');
       data.find('.txt').val('');
       data.find('.strong').attr('id','strong_'+num);
           
       tinymce.init({
         selector: '#strong_'+num,
       
       });
      
}

function remove(id){
   if (id == 123){
      alert("You can't delete this item");
   }
   else{
   if(confirm('You sure want to delete this'))
   {
      $('#subdiv_'+id).remove();
   }
   else{
    return false;
   }
   }
   console.log('subdiv_'+id);

}

function show_div(id){

  
  $('.del_'+id).show();
  $('.edit_'+id).show();

  
  tinyMCE.get('strong_'+id).setMode("readonly");
  tinyMCE.get('weak_'+id).setMode("readonly");
  $('.checklist_edt').prop('disabled',true);


  $('#test_'+id).toggle();
  
  var color=$('#test_'+id).is(':hidden') ? '#296c0e' : '#1b7a7e';
  var text=$('#test_'+id).is(':hidden') ? 'View' : 'Hide';

  var del = $('#test_'+id).is(':hidden') ? 'none' : '';

    $('#'+id).css({'background': color , 'content' :'Hide'});
    $('#'+id).val(text);

   //hide del & edit on click hide btn 
    $('.del_'+id).css({ "display": del});   
     $('.edit_'+id).css({ "display": del});
   
}


//form validte


     function frmvalidate(){

        var Assmnt_ascept   = $('.assessment').val();
        
        console.log(Assmnt_ascept);
        $(".error").remove();
       
        
        if (Assmnt_ascept == 0) {
            $('.assessment').after('<span class="error"> * choose one option</span>');
            return false;
        }
       

       
    
    return true;
}

//save form

$('#frm_internal_review').submit(function(e){

   e.preventDefault();

   if(frmvalidate()){
     
      tinyMCE.triggerSave();
      $.ajax({
          type: 'POST',
          url: 'ajax_internal_review',
          data: $('#frm_internal_review').serialize(),
          success: function(res){
            console.log(res);
            sessionStorage.setItem('message', 'Assessment Aspect added successfully') ; 
            sessionStorage.setItem('type', 'success');
            location.reload();
            
          }
      })
   }
})

//update from
function edit_asmnt(id){

  $('.edit_'+id).hide();
  $('.update_'+id).show();

  //disable readonly property of fields
  $('#asmnt_'+id).prop('disabled',false);
  tinyMCE.get('strong_'+id).getBody().setAttribute('contenteditable', true);
  tinyMCE.get('weak_'+id).getBody().setAttribute('contenteditable', true);
  $('input[type=checkbox]').prop('disabled',false);
  $('.txt').prop('readonly',false);

}

function update_asmnt(id){
       var assment = $('#asmnt_'+id).val();
       var strong = tinyMCE.get('strong_'+id).getContent();
       var weak   = tinyMCE.get('weak_'+id).getContent();
       var result = $('input[type=checkbox]:checked').val();
       var improvement = $('#improvement_'+id).val();
       var improvement = improvement.replace(/\s/g, '');
   
       $.ajax({
         type:'POST',
         url: 'ajax_internal_review',
         data: {edit_id:id,assessment:assment,strong:strong,weak:weak,checklist:result,improvement:improvement,action:'update_assesment'},
         success:function(res){
              console.log(res);
              sessionStorage.setItem('message', 'Assessment Aspect updated successfully') ; 
              sessionStorage.setItem('type', 'success');
               location.reload();
         }
       });

}

//delete functionlity
function del_asmnt(id){
  
   var strong = tinyMCE.get('strong_'+id).getContent();
   var weak = tinyMCE.get('weak_'+id).getContent();
   
      if(strong != '' && weak != ''){
         $('.btn-dlt').prop('disabled', true);
      }
      else{
         $('.btn-dlt').prop('disabled', false);
      }

      $("#deleteComplainceModal_"+id).modal('show');
}

function delete_record(id){
 var $ele = $("#deleteComplainceModal_"+id).parent().parent();
  $.post('ajax_internal_review.php',{action:'delete',edit_id:id },
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

function save_all(){
  console.log(123);
   sessionStorage.setItem('message', 'Assessment Aspect added successfully') ;
   sessionStorage.setItem('type', 'success') ;
    window.location = 'manage_auditreport.php';
}


</script>
