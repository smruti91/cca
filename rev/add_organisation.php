<?php 
session_start();

include("../common_functions.php");
include_once("../config.php");
$mdays_per_person=107;
$team = array("1");

$sqlselectteam = generateSQL("SELECT * FROM cca_team where  id = ?",$team,false,$mysqli) ;

  $row_team       = reset($sqlselectteam);

 $sqlselectmemberwithoutpion=generateSQL("select (LENGTH(emp_id)- LENGTH(REPLACE(emp_id,',','')) + 1) as noofemployee from cca_team_emp_role where  team_id = ?",$team,false,$mysqli) ;
 
$row_detailsuser       = reset($sqlselectmemberwithoutpion);


//echo $sqlselectmemberwithoutpionrow['noofemployee'] ;
$tdays=$row_detailsuser['noofemployee'] * $mdays_per_person ;


  $toaldays=$tdays - $assignDays;

 
$resdate    = generateSQL("SELECT plan_end_date as date FROM cca_plan",array(),false,$mysqli);
$resdate1 = reset($resdate);

 $STARTDATE = date('d-m-Y', strtotime($resdate1['date']));

$rescategory    =generateSQL("SELECT * FROM cca_audit_category",array(),false,$mysqli);

$inst_category = generateSQL("SELECT * FROM institution_category",array(),false,$mysqli);
$institute = generateSQL("SELECT id FROM cca_institutions",array(),false,$mysqli);
$output="";

if(isset($_GET['action'])&& !empty($_GET['action'] )){

  $get_action = $_GET['action'];
  
 
$output="";
$cat_id=$_GET['institute_category'];
$dist=$_GET['deptid'];
$inst_array =  array();
array_push($inst_array,$dist, $cat_id);


$result_institute=generateSQL("SELECT id,ddo_code as organisation_name FROM cca_institutions where dept_id=? and institution_category=?  order by organisation_name",$inst_array,false,$mysqli);


 
foreach( $result_institute as $row )
{

$output.="<option value='".$row['id']."'>".$row['organisation_name']."</option>";

}



echo $output;
exit;
}

  
?>

<?php include "header.php"; ?>
<style type="text/css">
  
<style> 
  
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

  function choose_cat(cat_id,dept_id)
  {
    
    ShowInstitute(cat_id,dept_id);
  }
  function ShowInstitute(ins_id,dept_id)
{
  
$("#lstBox").html("<option value=''>Please wait...</option>");

$.ajax({
url:" <?php echo BASE_URL?>rev/add_organisation.php?action=show_institute&institute_category="+ins_id+"&deptid="+dept_id+"",
success:function(data){
 
$("#lstBox").html(data);


}
});
}
function clearlistbox(){
  $("#ListBox1").empty()
}
  

function SecListBox(ListBox,text,value)
  {

  // try
  // {
  var option=document.createElement("OPTION");


  var currentYear = new Date().getFullYear();
for(var i = 0; i < 5; i++){
  var next = currentYear+1;
  var year = currentYear + '-' + next.toString();
 // $('#financialYear').append(new Option(year, year));
  currentYear--;
  option.value=value;
  option.text=text+'-'+year;
  // alert(option.text);
  $('#ListBox1').append('<option value="'+value+'">'+text+'-'+year+'</option>');

  //$('#ListBox1').append(option);
  //ListBox.options.add(option)
  }
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
  //alert(document.getElementById("lstBox").value);
  for(i=0;i<count;i++)
  {
  if(document.getElementById("lstBox").options[i].selected)
  {
    clearlistbox();
  SecListBox(document.getElementById("ListBox1"),document.getElementById("lstBox").options[i].text,document.getElementById("lstBox").options[i].value);
  
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
var text_inst = document.getElementById("ListBox2").options[i].text;
chose = chose+text_inst+",";
}
}


chosen = chosen.slice(0, -1);
chose = chose.slice(0, -1);
//var inst_cat = document.getElementById("inst_category").value;
var inst_audt = document.getElementById("audit_category").value;
var team = "<?php echo $row_team['id']; ?>";
var plan = "<?php echo $row_team['plan_id']; ?>";
var dept = "<?php echo $_SESSION["dept_id"];?>";
var startdate = document.getElementById("asd").value;

if(chosen !='' && inst_audt!='' && team!='' && plan!='' && dept!=''){

  $.post("<?php echo BASE_URL?>rev/calculateautodate.php",{action:"add",plan:plan,dept:dept,team:team,inst_audt:inst_audt,chosen:chosen,startdate:startdate,chose:chose},

      function(res){
      // console.log(res);
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
              
              <table width="100%" border="1" cellspacing="1" cellpadding="1">
                <tr>
                <td  colspan="4"align="right" valign="top" class="headingboldtext"><div align="center"><u> <?php echo htmlentities($_SESSION['plan_name']);?> </u></div></td>
              </tr>
              <tr>
                <td colspan='4' align="right" valign="top" id="teamid" class="headingboldtext" value = "<?php echo $row_team['id'] ?>"><div align="center"><u>Party:- <?php echo htmlentities($row_team['team_name']);?> </u></div></td>
              </tr>
            <tr>
              <td align="center">&nbsp;</td>
              <td colspan="3"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr class="brownboldtext">
                  <td width="17%" align="center">Allotted Mandays</td>
                  <td width="16%" align="center"> Mandays Available </td>
                  <td width="14%" align="center">Review Days </td>
                  <td width="13%" align="center">Transit Days </td>
                  <td width="21%" align="center">Mandays Consumed </td>
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
                  <td width="16%" align="center"><input type="text" name="total_mandays" id="total_mandays" value="456"  size="7" class="input" disabled="disabled" /></td>
                  <td width="14%" align="center"><input type="text" name="total_revdays" id="total_revdays" value="789"  size="7" class="input" disabled="disabled" /></td>
                  <td width="13%" align="center"><input type="text" name="total_transitdays" id="total_transitdays" value="963"  size="7" class="input" disabled="disabled" /></td>
                  <td width="21%" align="center"><input type="text" name="total_consumeDays" id="total_consumeDays" value="852"  size="7" class="input" disabled="disabled" /></td>
                  <td width="19%" align="center"><input type="text" name="assighn_days" id="assighn_days" value="<?php echo $toaldays-($totalmandays+$totalrevmandayscount+$totaltransdayscount)?>"  size="7" class="input" disabled="disabled" <?php if($toaldays-$totalmandays<=0)echo "style='background:#FF0000;color:#FFFFFF;'";?> /></td>
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
              <td><select name="inst_category" id="inst_category" onchange="choose_cat(this.value,'<?php echo $_SESSION['dept_id'] ?>');clearlistbox();">
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
                <select  name="lstBox" id="lstBox" size="7" multiple="multiple" style="width:200px;"  >
                  <option  value="$row['id']"></option>
      
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
          <td align="center"><a href="javascript:SecondListBox();"><img src="<?php echo BASE_URL?>images/rarrow.jpg" alt="forword" width="32" height="18" style="border:none;" /></a></td>
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
                <center>  <button  type="button" onclick="save_inst()">Submit data</button></center>
                </td>
              </tr>

              <tr>
                <td colspan="4">
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                    
                    <tr class="Ttext1">
                       <td width="30" height="46" align="center">Sl No </td>
                          <td width="52" align="center">Audit Department</td>
                          <td width="94" align="center">Institution</td>
                          <td width="48" align="center">Days Alloted </td>
                          <td width="101" align="center">Date of Commencement</td>
                          <td width="84" align="center">Date of Completion</td>
                          <td width="48" align="center">Party No</td>
                          <td width="71" align="center">Pending Year Of Accounts </td>
                          <td width="41" align="center">Order</td>
                           <td width="50" align="center">Transit Day </td>
                           <td width="103" align="center">Action</td>


                    </tr>
            <?php 
            $sl=1;
            $QueryManagePlan = generateSQL("SELECT * FROM `cca_pendingyear` cp 
LEFT JOIN  cca_manageplan cm on cm.org_id = cp.org_id WHERE cm.team_id =? ",$team,false,$mysqli);

  foreach ($QueryManagePlan as  $values) {
    


            ?>        
                      <tr>
                       
                        <td><?php echo $sl++?></td>
                        <td><?php echo $values['dept_id']; ?></td>
                        <td><?php echo $values['org_id']; ?></td>
                        <td><?php echo $values['mandays']; ?></td>
                        <td><?php //echo //$value['']; ?></td>
                        <td><?php //echo //$value['']; ?></td>
                        <td><?php echo $values['team_id']; ?></td>
                        <td><?php echo $values['show_year']; ?></td>
                        <td><?php //echo //$value['']; ?></td>
                        <td><?php //echo //$value['']; ?></td>
                        <td><?php //echo //$value['']; ?></td>
                      </tr>
                      <?php } ?>
                  </table>
                </td>
              </tr>
            
            </table>
             
              
              
            
            
             
            </form>
            
             

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


    



