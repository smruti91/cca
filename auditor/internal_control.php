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
//$_SESSION['paraid']=$_POST['para_id'];
//print_r($res_row);

}

//print_r($_SESSION);

     $sql_para  = " SELECT * FROM cca_para_4a WHERE para_id = '".$_SESSION['paraid']."' AND mngplan_id = '".$manageplan_id."' AND version = 0 ";
     //echo $sql_para;
     $sql_para_res   = mysqli_query($mysqli,$sql_para);
     //print_r($sql_para_res);
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
                 <div class=" right" style="float: right;" ><img src="../images/plus.png" />
                <a  onclick="add_more_div();" href="Javascript:void(0);" style=" color:#1a629c; ">Add more</a></div>
                <br clear="all"/>
                      <div class="cmp_div" >
                     <?php 
                       include "internal_control_template.php" ; 
                      // if($edit_id == 1 ){
                      //   include "internal_control_template_edit.php" ; 
                      // }else{
                      //    include "internal_control_template.php" ; 
                      // }
                     
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
                          <input type="submit" class="btn btn-primary" name="Update_complaince" value="Update" style="float: left;" />
                       <?php
                      }else{
                        ?>
<!--                            <button class="btn btn-primary" name="save_complaince" > save</button> -->
                          <input type="submit" class="btn btn-primary" name="save_complaince" value="Save" style="float: left;" />
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

     // $('.assessment').change(function(){
     //      var m = this.value;
     //      //var id = $('.field-control').attr('id');
     //     var id = this.id;
     //     var myid = id.split('_');

     //      //console.log(myid[1]);
     //      if(m == 0){
     //        $('#test_'+myid[1]).hide();
     //      }
     //      else{
     //         $('#test_'+myid[1]).show();
     //      }
         
     // });




  } );

  $(document).on('change' , '.assessment',function(){
   
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
      selector: '.strong',
   
  });

   tinymce.init({
      selector: '.weak'
  });
    

   function closeAlertBox(){
window.setTimeout(function () {
  $("#alert_msg").fadeOut(300)
}, 3000);
} 

//clone div under check

function create_div(){

       
       var num = Math.random().toString(16).slice(2);
       //data.find(".subdiv").prop('id','subdiv_'+num);assessment
       //var data = $(".subdiv").clone(true).appendTo(".cmp_div");
       //tinymce.remove("#strong_"+num);
       //tinymce.remove()
      
//tinymce.execCommand('mceRemoveControl',false,'#strong_');
       var data = $("#subdiv").clone(true).attr('id', 'subdiv_'+ num).appendTo(".cmp_div");

//        data.find('.mce-content-body').each(function () {
//            var ids =   $(this).removeAttr('id');
//            console.log(ids);
// });

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

//add more div 

 var count = 0;

function add_more_div(){

count = count+1;
      var  row = "";
           row += '<div class="after-add-more subdiv" id="subdiv_'+count+'">'
           row += '<div class="row">'
           row +=            '<div class="col-md-12 lbl">'
           row +=            ' <div class="col-md-3">'
           row +=              '<label for="">Assessment Aspect</label>'
           row +=             '</div>'
           row +=             '<div class="col-md-5">'
           row +=               '<div class="form-group">'
           row +=                 '<select  class="form-control assessment"  id="asmnt_'+count+'" name="assessment[]" required>'
           row +=                   '<option value="0">  Select Assessment Aspect</option>'
           row +=                   '<option value="1">Cash Managemet</option>'
           row +=                   '<option value="2">Bank Reconciliations</option>'
           row +=                   '<option value="3">Funds/Grants Management</option>'
           row +=                 '</select>'
           row +=               '</div>'
           row +=             '</div>'
           row +=             '<div class="col-md-2">'
                        
           row +=             '</div>'
           row +=             '<div class="col-md-2">'
           row +=                 '<input type="button" class="btn btn-info" id="" name="view" value="View" onclick="show_div(this.id)">'
           row +=                 '<input type="button" class="btn btn-danger remove" style="margin-left:5px;"  name="delete" value="Delete">'
           row +=             '</div>'
           row +=           '</div>'

           row +=         '</div>'

           row +=        '<div class ="field-control" id="test_'+count+'" style="display:none">'
           row +=         '<div class="row" style="margin: 5px;">'
           row +=           '<div class="col-md-6 lbl">'
           row +=             '<label for="">Indication of Strong Controls  </label>'
           row +=             '<div class="form-group">'
           row +=                '<textarea  class="form-control strong2" name="strong[]" id="strong_'+count+'"></textarea>'
           row +=             '</div>'
           row +=           '</div>'
           row +=           '<div class="col-md-6 lbl">'
           row +=             '<label for="" style="margin-left: 15px;">Indication of Weak Controls </label>'
           row +=             '<div class="form-group ">'
           row +=               '<textarea  class="form-control weak2" name="weak[]" id="weak_'+count+'"></textarea>'
           row +=             '</div>'
           row +=           '</div>'
           row +=           '<input type="hidden" name="save_complaince" value="save" ><input type="hidden" name="para_edit_id[]" value= -1 >'
           row +=         '</div>'

           row +=         '<div class="row" >'
           row +=           '<div class="col-md-6 lbl" style="margin-top: 35px;">'
           row +=             '<div class="col-md-4">'
           row +=                '<label for="">Assessment Result </label>'
           row +=             '</div>'
                        
           row +=             '<div class=" col-md-8 form-group ">'
           row +=                  '<label class="checkbox-inline ">'
           row +=                    '<input type="checkbox"  name = "checklist[]" value="1">Strong'
           row +=                  '</label>'
           row +=                  '<label class="checkbox-inline">'
           row +=                    '<input type="checkbox"  name = "checklist[]"  value="2">Moderate'
           row +=                  '</label>'
           row +=                  '<label class="checkbox-inline">'
           row +=                    '<input type="checkbox"   name = "checklist[]" value="3">Weak'
           row +=                  '</label>'
           row +=             '</div>'
           row +=           '</div>'

           row +=           '<div class="col-md-6 lbl">'
           row +=             '<label for="">Recommendation for improvement  </label>'
           row +=             '<div class="form-group" >'
           row +=                '<textarea  class="form-control improvement1 txt" name="improvement[]" id="improvement" style="width: 490px;height: 120px;"></textarea>'
           row +=             '</div>'
           row +=           '</div>'
                     
           row +=         '</div>'

           row +=          '</div>'
                   
           row +=       '</div>' ;

  var N = $(".cmp_div > div").length;
  var numItems = $('.subdiv').length
  numItems = 0;
 console.log(numItems);

 if(numItems > 1){

  $("#subdiv_"+count+"").after(row);
 }
 else{
  $(".cmp_div").after(row);
 }
  



tinymce.init({
      selector: '#strong_'+count+''
  });

   tinymce.init({
      selector: '#weak_'+count+''
  });
}

//remove div
$("body").on("click",".remove",function(){
$(this).parents(".subdiv").remove();
});



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
  //var id = $('.subdiv').attr('id');
  //var ele = $(this).parents("#subdiv");
  //$('#'+id).hide();
  $('#test_'+id).toggle();
  //$(this).toggleClass('class1');background: #d9534f;

  var color=$('#test_'+id).is(':hidden') ? '#296c0e' : '#1b7a7e';
  var text=$('#test_'+id).is(':hidden') ? 'View' : 'Hide';

    $('#'+id).css({'background': color , 'content' :'Hide'});
    $('#'+id).val(text);
  //console.log(id);
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

    frmvalidate();
    //console.log(id);

   if(frmvalidate()){
      //$('#frm_internal_review')[0].submit();
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



//delete functionlity
function del_asmnt(id){

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


</script>
