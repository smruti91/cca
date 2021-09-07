<?php 

session_start();

include("../common_functions.php");
include_once("../config.php");

$mdays_per_person=220;
if(isset($_POST['team'])){
  $team = $_POST['team'];
  $plan = $_POST['plan'];


$department =  $_SESSION['dept_id'];
$sqlselectteam = generateSQL("SELECT * FROM cca_team where  id = ?",array($team),false,$mysqli) ;

  $row_team       = reset($sqlselectteam);

 $sqlselectmemberwithoutpion=generateSQL("select count(*) as noofemployee from cca_team_emp_role where  team_id = ?",array($team),false,$mysqli) ;
 
$row_detailsuser       = reset($sqlselectmemberwithoutpion);


//echo $sqlselectmemberwithoutpionrow['noofemployee'] ;
$tdays=$row_detailsuser['noofemployee'] * $mdays_per_person ;


  $toaldays=220;

 
$resdate    = generateSQL("SELECT plan_start_date as date FROM cca_plan WHERE dept_id=? ",array($department),false,$mysqli);
$resdate1 = reset($resdate);

 $STARTDATE = date('d-m-Y', strtotime($resdate1['date']));

$rescategory    = generateSQL("SELECT * FROM `cca_audit_category` where 1=?",array('1'),false,$mysqli);

$inst_category  = generateSQL("SELECT * FROM institution_category where 1=?",array('1'),false,$mysqli);

}

$output="";

if(isset($_POST['action']) && ($_POST['action'] =='show_institute' )){

 $get_action = $_POST['action'];
$output="";
$cat_id=$_POST['institute_category'];
$dist=$_POST['deptid'];
$teamid= $_POST['team_id'];
$inst_array =  array();
array_push($inst_array,$dist, $cat_id);
$result_institute=generateSQL("SELECT id,ddo_code as organisation_name,institution_name FROM cca_institutions where dept_id=? and institution_category=? and id not in (select org_id from `cca_manageplan` where team_id!='".$teamid."') order by organisation_name",$inst_array,false,$mysqli);

// echo "SELECT id,ddo_code as organisation_name,institution_name FROM cca_institutions where dept_id=? and institution_category=?  order by organisation_name";
// print_r($inst_array);
// print_r($result_institute);exit;
 
foreach( $result_institute as $row )
{
  $output.="<option title='".$row['institution_name']."' value='".$row['id']."'>".$row['organisation_name']."</option>";
}


echo $output;
exit;
}

if(isset($_POST['action']) && ($_POST['action']=='review')){

  $review_team_id = $_POST['id'];
  $send_review = generateSQL("UPDATE `cca_team` SET `reviewer_party_status` = 'Review' WHERE CONCAT(`cca_team`.`id`) =?",array($review_team_id),true,$mysqli );
}

 $countreviewdays= generateSQL("SELECT sum(cp.mandays_audit) as totalassigndays,sum(cp.mandays_review) as totalreviewdays,mandys_drafting as draftdays FROM `cca_pendingyear`  cp LEFT JOIN  cca_manageplan cm on cm.org_id = cp.org_id 
  LEFT JOIN cca_institutions ci on ci.id = cm.org_id
  WHERE cm.team_id =? AND cm.dept_id=? AND cp.status=?",array($team,$department,'1'),false,$mysqli);

  
$countreviewdays = reset($countreviewdays);
  $totalrevmandayscount= $countreviewdays['totalreviewdays'];
  $totalauditday = $countreviewdays['totalassigndays'];
  $Days = $countreviewdays['totalassigndays'] * 2 / $row_detailsuser['noofemployee'] ; 
  $totalauditdays = round($Days);
$countdraftdays = generateSQL("SELECT  DISTINCT(org_id) FROM `cca_manageplan` WHERE `team_id` =?",array($team),false,$mysqli);
    $totaldraftdays = 0;  
        
    foreach ($countdraftdays as $draftdays) 
      
      {
        $orgid = $draftdays['org_id'];
        $selectdraftday = generateSQL("SELECT mandys_drafting FROM `cca_institutions` WHERE `id` = ?",array($orgid),false,$mysqli);
       $selectdraftday = reset($selectdraftday);

       $totaldraftdays = $totaldraftdays+ $selectdraftday['mandys_drafting'];
       
    }
     $totaldraftdays;
  //$totaldraftdays = $countdraftdays['draftdays'] * 15;

if(isset($_POST['action']) && ($_POST['action']=='check')){
  $org_id= $_POST['org_id'];
  $year_check = generateSQL("SELECT cp.show_year,ci.ddo_code,cp.id,ci.id as inst FROM `cca_pendingyear`  cp  
  LEFT JOIN cca_institutions ci on ci.id = cp.org_id
  WHERE cp.status=? AND cp.org_id=?",array('0',$org_id),false,$mysqli);
 foreach( $year_check as $row1 )
{

$output.="<option  value='".$row1['id']."-".$row1['inst']."'>".$row1['ddo_code'].'-'.$row1['show_year']."</option>";

}
echo $output;
  exit;
}
?>

<?php include "header.php"; ?>
<style type="text/css">
  
div.main{
height:100%;
}
  
table, th, td { 
  border: 1px solid black; 
  border-collapse: collapse; 
    
} 
/* setting the text-align property to center*/ 
 td { 
  padding: 5px; 
  text-align:center; 
        
} 

</style>
<script type="text/javascript">

  function showUser(text){
  

// var text = $('#ListBox1').find(":selected").text();
// document.getElementById("#ListBox2").text;
 var str="";
  var count=document.getElementById("ListBox1").options.length;
  //alert(document.getElementById("lstBox").value);
  for(i=0;i<count;i++)
  {
  if(document.getElementById("ListBox1").options[i].selected)
  {
SecListBox2(document.getElementById("ListBox2"),$('#ListBox1').find(":selected").text(),document.getElementById("ListBox1").value);
str+=i+',';

  }
  

  }

if(str!=""){
  str=str.substring(0,str.length-1);
  var newstr=str.split(",");
  var lcount=0;
  for(v=0;v<newstr.length;v++){
    document.getElementById("ListBox1").remove(newstr[v]-lcount);
  lcount++;
  }
  }
}

function remove_list(id){
  var str="";
var count=document.getElementById("ListBox2").options.length;

  for(i=0;i<count;i++)
  {
  if(document.getElementById("ListBox2").options[i].selected)
  {
    SecListBox2(document.getElementById("ListBox1"),$('#ListBox2').find(":selected").text(),document.getElementById("ListBox2").options[i].value);

  str+=i+',';
 
  }
  }
 
  if(str!=""){

  str=str.substring(0,str.length-1);
  var newstr=str.split(",");
  var lcount=0;
  for(v=0;v<newstr.length;v++){

    document.getElementById("ListBox2").remove(newstr[v]-lcount);
  lcount++;
  }
  }
}

function SecListBox2(ListBox,text,value)
  {

  // try
  // {
  var option=document.createElement("OPTION");


 
  option.value=value;
  option.text=text;
  ListBox.options.add(option)
  }

  function autodate(rDay)
{

var asd=document.orgform.asd.value ;
window.location='calculateautodate.php?asd='+asd+'&restDay='+rDay;
}

  function choose_cat(cat_id,dept_id,team_id)
  {
    
    ShowInstitute(cat_id,dept_id,team_id);
  }


function ShowInstitute(ins_id,dept_id,team_id)
{
  $("#lstBox").html("<option value=''>Please wait...</option>");
  $.post("<?php echo BASE_URL?>auditor/add_organisation.php",{action: 'show_institute',institute_category: ins_id,deptid: dept_id,team_id: team_id},function(data){
    console.log(data);
    $("#lstBox").html(data);
  });

  // $.ajax({
  // url:" <?php echo BASE_URL?>auditor/add_organisation.php?action=show_institute&institute_category="+ins_id+"&deptid="+dept_id+"&teamid="+team_id+"",
  // success:function(data){
  //   console.log(data);
  // $("#lstBox").html(data);
  // }
  // });
}


function clearlistbox(){
  $("#ListBox1").empty()
}
  

function SecListBox(ListBox,text,value)
  {
     $("#ListBox1").html("<option value=''>Please wait...</option>");
    if(value!=''){

      $.post("<?php echo BASE_URL?>auditor/add_organisation.php",{action:"check",org_id:value},

          function(data){
          //console.log(data);
              //window.location.reload();
              $("#ListBox1").html(data);
          }
          );
    }
//console.log(fin_year);
   
}
  // catch(er)
  // {
  // alert(er)
  // }
  // }

function FirstListBox()
  {
  try
  {
   
  var str="";
  var count=document.getElementById("lstBox").options.length;
  
  for(i=0;i<count;i++)
  {
   
  if(document.getElementById("lstBox").options[i].selected)
  {
    clearlistbox();
  SecListBox(document.getElementById("ListBox1"),document.getElementById("lstBox").options[i].text,document.getElementById("lstBox").value);
  
  str+=i+',';
  }
  }
  if(str!=""){
  str=str.substring(0,str.length-1);
  var newstr=str.split(",");
  var lcount=0;
  for(v=0;v<newstr.length;v++){
    document.getElementById("lstBox").remove(newstr[v]-lcount);
  lcount++;
  }

  }
  

  }
  catch(er)
  {
  alert(er)
  }
  }

  function SortAllItems()
  {
  var arr=new Array();
  for(i=0;i<document.getElementById("lstBox").options.length;i++)
  {
  arr[i]=document.getElementById("lstBox").options[i].value}arr.sort();
  RemoveAll();
  for(i=0;i<arr.length;i++)
  {
  SecListBox(document.getElementById("lstBox"),arr[i],arr[i])}}function RemoveAll(){try{document.getElementById("lstBox").options.length=0
  }
  catch(er)
  {
  alert(er)
  }
  }
  function SecondListBox()
  {
  var str="";
  try
  {
  var count=document.getElementById("ListBox1").options.length;
  for(i=0;i<count;i++)
  {
  if(document.getElementById("ListBox1").options[i].selected){SecListBox(document.getElementById("lstBox"),document.getElementById("ListBox1").options[i].text,document.getElementById("ListBox1").options[i].value);
  str+=i+',';
  }
  }
  if(str!=""){
  str=str.substring(0,str.length-1);
  var newstr=str.split(",");
  var lcount=0;
  for(v=0;v<newstr.length;v++){
    document.getElementById("ListBox1").remove(newstr[v]-lcount);
  lcount++;
  }
  }
  //SortAllItems()
  }
  catch(er)
  {
  alert(er)
  }
  }


</script>
<script type="text/javascript">
  function save_inst(){
    
    len = document.orgform.ListBox2.length;
   
i = 0
chosen = ""
chose = ""
for (i = 0; i < len; i++) 
{
 
if (document.getElementById("ListBox2").options[i].value !='') 
{

 var inst_list = document.getElementById("ListBox2").options[i].value;
 inst_list.trim() ;
chosen = chosen + inst_list + ",";

}
}

chosen = chosen.slice(0, -1);


//chose = chose.slice(0, -1);
var inst_cat = document.getElementById("inst_category").value;
//var inst_audt = document.getElementById("audit_category").value;
var team = "<?php echo $row_team['id']; ?>";
var plan = "<?php echo $row_team['plan_id']; ?>";
var dept = "<?php echo $_SESSION["dept_id"];?>";
var startdate = document.getElementById("asd").value;

if(chosen !=''  && team!='' && plan!='' && dept!=''){

  $.post("<?php echo BASE_URL?>auditor/calculateautodate.php",{action:"add",plan:plan,dept:dept,team:team,chosen:chosen,startdate:startdate},

      function(res){
      //console.log(res);
window.location.reload();

      }
      );
}
else{
  alert("A field is blank");
}
//document.orgform.selectedinstuteid.value=chosen ;


//url=url+"?courseid="+chosen+"&au_id="+autype+"&au_th="+authm+"&action=fetch_pending_year_account"
//xmlHttp.onreadystatechange=stateChanged 
//xmlHttp.open("GET",url,true)
//xmlHttp.send(null)

  }
</script>


  <!-- Bootstrap Date-Picker Plugin -->

    
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Add Organisation</h1>

            <hr>
            <form class="form-horizontal" action="" method="post" name="orgform" id="orgform">
              
              <table  class="" width="100%" border="1" cellspacing="1" cellpadding="1">
                <tr>
                <td  colspan="4"align="right" valign="top" class="headingboldtext"><div align="center"><u> <?php echo find_planname($plan,$mysqli);?> </u></div></td>
              </tr>
              <tr>
                <td colspan='4' align="right" valign="top" id="teamid" class="headingboldtext" value = "<?php echo $row_team['id'] ?>"><div align="center"><u>Party:- <?php echo htmlentities($row_team['team_name']);?> </u></div></td>
              </tr>
            <tr>
              <td align="center">&nbsp;</td>
              <td colspan="3"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr class="brownboldtext">
                  <td width="17%" align="center">Allotted Mandays</td>
                  
                  <td width="14%" align="center"> Audit</td>
                  <td width="13%" align="center">Review </td>
                  <td width="21%" align="center">Drafting </td>
                  <td width="16%" align="center">Consumed </td>
                  <td width="19%" align="center">Balance Mandays </td>
                </tr>
              </table>
              </td>
            </tr>
             <tr>
              <td align="center" class="brownboldtext">Current Programme </td>
              <td colspan="3"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="17%" align="center"><input type="text" name="total_days" id="total_days" value="<?php echo htmlentities($toaldays)?>"  size="7" class="input" disabled="disabled" /></td>
                  <td width="16%" align="center"><input type="text" name="total_mandays" id="total_mandays" value="<?php echo $totalauditdays?>"  size="7" class="input" disabled="disabled" /></td>
                  <td width="14%" align="center"><input type="text" name="total_revdays" id="total_revdays" value="<?php echo htmlentities($totalrevmandayscount)?>"  size="7" class="input" disabled="disabled" /></td>
                  <td width="13%" align="center"><input type="text" name="total_transitdays" id="total_transitdays" value="<?php echo $totaldraftdays?>"  size="7" class="input" disabled="disabled" /></td>
                  <td width="21%" align="center"><input type="text" name="total_consumeDays" id="total_consumeDays" value="<?php echo ($totalauditdays+$totalrevmandayscount+$totaldraftdays);?>"  size="7" class="input" disabled="disabled" /></td>

                  <?php 
                    $totalmandays=$totalauditdays+$totalrevmandayscount+$totaldraftdays;
                   ?>
                  <td width="19%" align="center"><input type="text" name="assighn_days" id="assighn_days" value="<?php echo $toaldays-($totalauditdays+$totalrevmandayscount+$totaldraftdays)?>"  size="7" class="input" disabled="disabled" <?php if($toaldays-$totalmandays<=0)echo "style='background:#FF0000;color:#FFFFFF;'";?> /></td>
                </tr>
              </table>
            </td>
            </tr>
           <div> 
            <tr>
            <td colspan = "4"><table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
            <tr>
              <td class="brownboldtext">Select Category Of Audit</td>
              <td><select name="audit_category" id="audit_category" >
                       <option value="">--Select Category--</option>
                        <?php
                        foreach($rescategory as $QueryYearrow)
            {?>

            <option value='<?php echo htmlentities($QueryYearrow['id']);?>'><?php echo htmlentities($QueryYearrow['audit_type']);?></option>

            <?php }?>
                    </select>  
            </td>
   <!-- <td class="brownboldtext">Select Theme</td>
    <td></td>-->
    <td class="brownboldtext">Date Of Commencement Of Audit</td>
              <td><input type="text" name="asd2" id="asd" style="width:80px;"  value="<?php echo htmlentities($STARTDATE);?>" readonly/></td>
              <td class="brownboldtext">Institution Category:</td>
              <td><select name="inst_category" id="inst_category" onchange="choose_cat(this.value,'<?php echo $_SESSION['dept_id'] ?>','<?php echo $team ?>');clearlistbox();">
           <option value="">--Select Category--</option>
            <?php
            foreach($inst_category as $Queryinst)
{?>

<option  value='<?php echo htmlentities($Queryinst['ID']);?>' ><?php echo htmlentities($Queryinst['Category']);?></option>

<?php }?>
        </select></td>
            </tr>
          </table>
        </td>
        </tr>
         </div> 
         <div> 
          <tr>
            <td colspan="4"><label>Institution</label></td>
          </tr>
            <tr>
              <td>
                <select  name="lstBox" id="lstBox" size="7" multiple="multiple" style="width:300px;"  >
                  <option   value="$row['id']"></option>
      
                </select>
              </td>
              
              <td >
          <table  border="0" style="float:left;">
          <tr>
          <td align="center">&nbsp;</td>
          </tr>
          <tr>
          <td align="center"><a href="javascript:FirstListBox();"><img src="<?php echo BASE_URL?>images/farrow.jpg" alt="forword" width="32" height="18" style="border:none;" /></a></td>
          </tr>
          <tr>
          <td align="center"></td>
          </tr>
          <tr>
          <td align="center">&nbsp;</td>
          </tr>
          <tr>
          <td align="center">&nbsp;</td>
          </tr>
          </table>        </td>
          <td ><select name="ListBox1" size="7" multiple="multiple" id="ListBox1" style="width:200px;" onclick="javascript:showUser(this.value);">
                
               
              </select></td>
              <td   >
      <div id="year_span">  
      <select name="ListBox2" size="7" multiple="multiple" id="ListBox2" style="width:200px;" onclick="remove_list(this.value)">
        <option ></option>
       
        </select> 
        </div>          </td>
            </tr>
              </div>
              <tr>
                  <td  colspan="4";>
                    <?php 
                     if($toaldays-$totalmandays>=0)  {

                    ?>
                <center>  <button  type="button" onclick="if(confirm('Do you want to Save audit institutions?')) save_inst()" class="btncca">Submit data</button></center>
              <?php } 
              else{
                echo "<font color= 'red' weight='bold' size='5'> Party Consumed All The Mandays</font>";
              }?>
                </td>
              </tr>

              <tr>
                <td colspan="4">
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                    
                    <tr class="Ttext1">
                       <td width="30" height="46" align="center">Sl No </td>
                          <td width="52" align="center">Audit Department</td>
                          <td width="94" align="center">Institution</td>
                          <td width="71" align="center">Pending Year Of Accounts </td>
                          <!-- <td width="48" align="center">Days Alloted </td> -->
                          <td width="101" align="center">Date of Commencement</td>
                          <td width="84" align="center">Date of Completion</td>
                          <td width="48" align="center">Party No</td>
                          
                          
                           <td width="103" align="center">Action</td>


                    </tr>
            <?php 
            $sl=1;
            $QueryManagePlan = generateSQL("SELECT cp.id,cp.show_year,cm.dept_id,cp.org_id,cp.mandays_audit,cm.audit_start_date,cm.audit_end_date FROM `cca_pendingyear` cp LEFT JOIN  cca_manageplan cm on cm.org_id = cp.org_id WHERE cm.team_id =? AND cp.status=? group by cp.org_id,cp.show_year order by cm.id",array($team,'1'),false,$mysqli);

              foreach ($QueryManagePlan as  $values) {
                
                $Department = generateSQL("SELECT F_descr  FROM `cca_departments` WHERE `ID` =?",array($values['dept_id']),false,$mysqli);        
                $Department = reset($Department);
                $institution = generateSQL("SELECT ddo_code,institution_name  FROM `cca_institutions` WHERE `id` =?",array($values['org_id']),false,$mysqli);        
                $institution = reset($institution);

            ?>        
                      <tr>
                       
                        <td><?php echo $sl++?></td>
                        <td><?php echo $Department['F_descr']; ?></td>
                        <td title="<?php echo $institution['institution_name']; ?>"><?php echo $institution['ddo_code']; ?></td>
                        <td><?php echo $values['show_year']; ?></td>
                       <!--  <td><?php //echo $values['mandays']; ?></td> -->
                        <td><?php echo date('d-m-Y', strtotime($values['audit_start_date'])); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($values['audit_end_date'])); ?></td>
                        <td><?php echo $row_team['team_name']; ?></td>
                        
                        
                        <td><button  type="button" onclick="dlt_inst('<?php echo $values['id']; ?>','<?php echo $values['org_id']; ?>','<?php echo $team; ?>')" class="btn btn-danger">Delete</button></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="11">
                         <center>  <button  type="button" onclick="if(confirm('Do you want to send for review?')) send_review('<?php echo $team; ?>')" class="btncca">Send For Review</button></center> 
                        </td>
                      </tr>
                  </table>
                </td>
              </tr>
            
            </table>
             
              
              
            
            
             
            </form>
           <script type="text/javascript">
             function dlt_inst(id,org,team){
              
           if(id!=''){ 
                $.post("<?php echo BASE_URL?>auditor/calculateautodate.php",{action:"delete",id:id,org:org,team:team},

                      function(res){
                     //console.log(res);  
                window.location.reload();

                      }
                      );
                }
             }


             function send_review(id){
             

              if(id!=''){ 
           
                $.post("<?php echo BASE_URL?>auditor/add_organisation.php",{action:"review",id:id},

                      function(res){
                     //console.log(res);  
                window.location.href = "<?php echo BASE_URL?>auditor/manage_plan.php";

                      }
                      );

                }
             }
</script> 
             

                <div class="row" style="padding-bottom:50px;">
                    
            </div>
          </div>
    </div>
</div>
        <!-- /.container-fluid -->
    </div>
    <div class="clear:both;"></div>
</div>

<?php include "footer.php"; ?>


    



