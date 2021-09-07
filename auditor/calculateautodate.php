<?php


session_start();

include("../common_functions.php");
include_once("../config.php");

 if(isset($_POST['action']) && ($_POST['action']=='add')){

 
 $plan = $_POST['plan'];
 $dept= $_POST['dept'];
 $team= $_POST['team'];
 //$inst_audt= $_POST['inst_audt'];
 $startdate = date('Y-m-d', strtotime($_POST['startdate']));
 $inst_id= explode(",", $_POST['chosen']);
 
 //$inst_year = explode(",", $_POST['chose']);
$year_array = array();

 for($i=0;$i<count($inst_id);$i++)
 	//foreach($inst_year as $inst_ids)
   {
   	

   		//print_r($i);
	$inst_years = explode("-", $inst_id[$i]);
	
	//print_r($inst_ids);
  //
 //$show_year = $inst_years['1'].'-'.$inst_years['2'];
 
 //$mandays = generateSQL("SELECT mandays_audit,mandays_review FROM cca_institutions where id=?",array($inst_id[$i]),false,$mysqli);
 
//$mandays = reset($mandays);

	$inser_year = generateSQL("UPDATE `cca_pendingyear` SET `status` = '1' WHERE `cca_pendingyear`.`id` =?",array($inst_years[0]),true,$mysqli);

	$inst_years2 =  $inst_years[1];

 array_push($year_array, $inst_years[1]);

	
 }
$a = array_unique($year_array);

foreach ($a as $inst_ids) {
	$avialable_org = generateSQL("SELECT org_id,id,audit_start_date  FROM `cca_manageplan` WHERE `org_id` =?" ,array($inst_ids),false,$mysqli);

	if(count($avialable_org)>0){
//For Second time organisation entry

$avialable_org = reset($avialable_org);
	
$startdate =date('Y-m-d', strtotime( $avialable_org['audit_start_date']));

$cal_mandays = generateSQL("SELECT sum(mandays_audit) as totalassigndays,sum(mandays_review) as totalreviewdays FROM `cca_pendingyear` WHERE `org_id` = ? AND status=? ",array($avialable_org['org_id'],'1'),false,$mysqli);
 	$res_mandays = reset($cal_mandays);
$total_mandys = $res_mandays['totalassigndays'] + $res_mandays['totalreviewdays'] + '4';

$sqlselectmemberwithoutpion=generateSQL("select count(*) as noofemployee from cca_team_emp_role where  team_id = ?",array($team),false,$mysqli) ;
 
$row_detailsuser       = reset($sqlselectmemberwithoutpion);
 $totalMan  = $row_detailsuser['noofemployee'];
	$Days = $res_mandays['totalassigndays'] * 2 / $totalMan ; 
	$totalDays = round($Days);

	
	//echo "totalDays = ".$totalDays."<br>";
	$totalDays = $totalDays + $res_mandays['totalreviewdays'] + '4';


	$endDateWithHoliday = date('Y-m-d', strtotime($startdate . '+'.$totalDays.' day'));
	
	$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));
	$sqlHoliday = mysqli_query($mysqli,"SELECT  * FROM    cca_calendar AS c WHERE   c.hl_date >='".$startdate."' AND  c.hl_date <='".$endDateWithHoliday."'");
	$no_cnt=mysqli_num_rows($sqlHoliday);
	
	do
	{
		$endDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+'.$no_cnt.' day'));
		$sqlHoliday9 =mysqli_query($mysqli,"SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'");
		//echo "SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'";
		
		//echo "sqlho=".$sqlHoliday9."<br>";
		//$resHoliday9 = mysql_query($sqlHoliday9);
		$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));
		$no_cnt1= mysqli_num_rows($sqlHoliday9);
		//$numHoliday = mysql_num_rows($resHoliday9);
		//echo "numHolidaykkk = ".$numHoliday."<br>";
	}
	while($no_cnt1 > 0);
$endAuditDate = $endDateWithHoliday;
 
	$update = generateSQL("UPDATE `cca_manageplan` SET `audit_start_date` = ?, `audit_end_date` = ? WHERE `org_id` = ? " ,array($startdate,$endAuditDate,$avialable_org['org_id']),true,$mysqli);
	$organisation = $avialable_org['id'];

$find_start = generateSQL("SELECT audit_end_date  FROM `cca_manageplan` WHERE `id` =?" ,array($avialable_org['id']),false,$mysqli);
 $find_start = reset($find_start);

 $startdate =date('Y-m-d', strtotime( $find_start['audit_end_date']. '+1 day'));

 // $selct_next = generateSQL("SELECT id FROM  `cca_manageplan` WHERE team_id=1 and id > 1",array($team,'1'),false,$mysqli);

 $selct_next=mysqli_query($mysqli,"SELECT id,org_id FROM  `cca_manageplan` WHERE team_id='".$team."' and id > '".$avialable_org['id']."'");

while($key = mysqli_fetch_array($selct_next)) {

 $cal_mandays = generateSQL("SELECT sum(mandays_audit) as totalassigndays,sum(mandays_review) as totalreviewdays FROM `cca_pendingyear` WHERE `org_id` = ? AND status=?",array($key['org_id'],'1'),false,$mysqli);
 	$res_mandays = reset($cal_mandays);
$total_mandys = $res_mandays['totalassigndays'] + $res_mandays['totalreviewdays'] + '4';

$sqlselectmemberwithoutpion=generateSQL("select count(*) as noofemployee from cca_team_emp_role where  team_id = ?",array($team),false,$mysqli) ;
 
$row_detailsuser       = reset($sqlselectmemberwithoutpion);
 $totalMan  = $row_detailsuser['noofemployee'];
	$Days = $res_mandays['totalassigndays'] * 2 / $totalMan ; 
	$totalDays = round($Days);

	
	//echo "totalDays = ".$totalDays."<br>";
	$totalDays = $totalDays + $res_mandays['totalreviewdays'] + '4';


	$endDateWithHoliday = date('Y-m-d', strtotime($startdate . '+'.$totalDays.' day'));
	
	$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));

	$sqlHoliday = mysqli_query($mysqli,"SELECT  * FROM    cca_calendar AS c WHERE   c.hl_date >='".$startdate."' AND  c.hl_date <='".$endDateWithHoliday."'");
	$no_cnt=mysqli_num_rows($sqlHoliday);
	
	do
	{
		$endDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+'.$no_cnt.' day'));
		$sqlHoliday9 =mysqli_query($mysqli,"SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'");
		
		//echo "sqlho=".$sqlHoliday9."<br>";
		//$resHoliday9 = mysql_query($sqlHoliday9);
		$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));
		$no_cnt1= mysqli_num_rows($sqlHoliday9);
		//$numHoliday = mysql_num_rows($resHoliday9);
		//echo "numHolidaykkk = ".$numHoliday."<br>";
	}
	while($no_cnt1 > 0);
$endAuditDate = $endDateWithHoliday;
	
	$update = generateSQL("UPDATE `cca_manageplan` SET `audit_start_date` = ?, `audit_end_date` = ? WHERE `id` = ? " ,array($startdate,$endAuditDate,$key['id']),true,$mysqli);
	
	$startdate = $startDateWithHoliday;
	
	
}

	}
	else{
//For Fitst Time organsation entry
		$cal_mandays = generateSQL("SELECT sum(mandays_audit) as totalassigndays,sum(mandays_review) as totalreviewdays FROM `cca_pendingyear` WHERE `org_id` = ? AND status=?",array($inst_ids,'1'),false,$mysqli);
 	$res_mandays = reset($cal_mandays);
$total_mandys = $res_mandays['totalassigndays'] + $res_mandays['totalreviewdays'] + '4';

$inser_inst = generateSQL("INSERT INTO cca_manageplan(dept_id,org_id,team_id,plan_id,assign_days) values (?,?,?,?,?)",array($dept,$inst_ids,$team,$plan,$total_mandys),true,$mysqli);

$sqlselectmemberwithoutpion=generateSQL("select count(*) as noofemployee from cca_team_emp_role where  team_id = ?",array($team),false,$mysqli) ;
 
$row_detailsuser       = reset($sqlselectmemberwithoutpion);
 $totalMan  = $row_detailsuser['noofemployee'];
	$Days = $res_mandays['totalassigndays'] * 2 / $totalMan ; 
	$totalDays = round($Days);

	
	
	$totalDays = $totalDays + $res_mandays['totalreviewdays'] + '4';

	 
	$find_date = generateSQL("SELECT audit_end_date  FROM `cca_manageplan` WHERE `team_id` = ? AND `plan_id` = ?  
ORDER BY `cca_manageplan`.`audit_start_date` DESC",array($team,$plan),false,$mysqli);
	
	$find_date1 = reset($find_date);
	
	if($find_date1['audit_end_date']==''){


	$endDateWithHoliday = date('Y-m-d', strtotime($startdate . '+'.$totalDays.' day'));
	
	$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));

//echo "SELECT  count(*) as no_cnt FROM   cca_calendar AS c WHERE   c.hl_date >= '?'        
//AND     c.hl_date < '?'";
//echo ("SELECT  count(*) as no_cnt FROM    cca_calendar AS c WHERE   c.hl_date >='".$startdate."' AND  c.hl_date <='".$endDateWithHoliday."'");exit;
	$sqlHoliday = mysqli_query($mysqli,"SELECT  * FROM    cca_calendar AS c WHERE   c.hl_date >='".$startdate."' AND  c.hl_date <='".$endDateWithHoliday."'");
	$no_cnt=mysqli_num_rows($sqlHoliday);
	
	do
	{
		$endDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+'.$no_cnt.' day'));
		$sqlHoliday9 =mysqli_query($mysqli,"SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'");
		
		//echo "sqlho=".$sqlHoliday9."<br>";
		//$resHoliday9 = mysql_query($sqlHoliday9);
		$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));
$no_cnt1=0;
		if ($result=mysqli_query($mysqli,"SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'"))
  		{
			$no_cnt1= mysqli_num_rows($result);
		}
		//$numHoliday = mysql_num_rows($resHoliday9);
		//echo "numHolidaykkk = ".$numHoliday."<br>";
	}
	while($no_cnt1 > 0);
$endAuditDate = $endDateWithHoliday;
	$update = generateSQL("UPDATE `cca_manageplan` SET `audit_start_date` = ?, `audit_end_date` = ? WHERE `org_id` = ? " ,array($startdate,$endAuditDate,$inst_ids),true,$mysqli);
	
 }
 else{

 		$startdate =date('Y-m-d', strtotime( $find_date1['audit_end_date']. '+1 day'));
 		$endDateWithHoliday = date('Y-m-d', strtotime($startdate . '+'.$totalDays.' day'));
	
	$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));

	$sqlHoliday = mysqli_query($mysqli,"SELECT  * FROM    cca_calendar AS c WHERE   c.hl_date >='".$startdate."' AND  c.hl_date <='".$endDateWithHoliday."'");
	$no_cnt=mysqli_num_rows($sqlHoliday);
	
	do
	{
		$endDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+'.$no_cnt.' day'));
		$sqlHoliday9 =mysqli_query($mysqli,"SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'");
		
		//echo "sqlho=".$sqlHoliday9."<br>";
		//$resHoliday9 = mysql_query($sqlHoliday9);
		$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));
		$no_cnt1= mysqli_num_rows($sqlHoliday9);
		//$numHoliday = mysql_num_rows($resHoliday9);
		//echo "numHolidaykkk = ".$numHoliday."<br>";
	}
	while($no_cnt1 > 0);
$endAuditDate = $endDateWithHoliday;
 
	$update = generateSQL("UPDATE `cca_manageplan` SET `audit_start_date` = ?, `audit_end_date` = ? WHERE `org_id` = ? " ,array($startdate,$endAuditDate,$inst_ids),true,$mysqli);
 }

	}
}

 }

if(isset($_POST['action']) && ($_POST['action']=='delete')){
 $inst_id = $_POST['org'];
 $year_id = $_POST['id'];
 $team = $_POST['team'];
$select_inst = generateSQL("select count(*) as noofinst from `cca_pendingyear` where  org_id = ? AND status=?",array($inst_id,'1'),false,$mysqli);
$select_inst = reset($select_inst);

if($select_inst['noofinst'] > 1){

$delete_year = generateSQL("UPDATE `cca_pendingyear` SET `status` = '0' WHERE `cca_pendingyear`.`id` = ?",array($year_id),true,$mysqli);
$avialable_org = generateSQL("SELECT org_id,id,audit_start_date  FROM `cca_manageplan` WHERE `org_id` =?" ,array($inst_id),false,$mysqli);
$avialable_org = reset($avialable_org);
	
$startdate =date('Y-m-d', strtotime( $avialable_org['audit_start_date']));

$cal_mandays = generateSQL("SELECT sum(mandays_audit) as totalassigndays,sum(mandays_review) as totalreviewdays FROM `cca_pendingyear` WHERE `org_id` = ? AND status=?",array($avialable_org['org_id'],'1'),false,$mysqli);
 	$res_mandays = reset($cal_mandays);


$total_mandys = $res_mandays['totalassigndays'] + $res_mandays['totalreviewdays'] + '4';

$sqlselectmemberwithoutpion=generateSQL("select count(*) as noofemployee from cca_team_emp_role where  team_id = ?",array($team),false,$mysqli) ;
 
$row_detailsuser       = reset($sqlselectmemberwithoutpion);
 $totalMan  = $row_detailsuser['noofemployee'];
	$Days = $res_mandays['totalassigndays'] * 2 / $totalMan ; 
	$totalDays = round($Days);

	
	//echo "totalDays = ".$totalDays."<br>";
	$totalDays = $totalDays + $res_mandays['totalreviewdays'] + '4';


	$endDateWithHoliday = date('Y-m-d', strtotime($startdate . '+'.$totalDays.' day'));
	
	$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));

	$sqlHoliday = mysqli_query($mysqli,"SELECT  * FROM    cca_calendar AS c WHERE   c.hl_date >='".$startdate."' AND  c.hl_date <='".$endDateWithHoliday."'");
	$no_cnt=mysqli_num_rows($sqlHoliday);
	
	do
	{
		$endDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+'.$no_cnt.' day'));
		$sqlHoliday9 =mysqli_query($mysqli,"SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'");
		
		//echo "sqlho=".$sqlHoliday9."<br>";
		//$resHoliday9 = mysql_query($sqlHoliday9);
		$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));
		$no_cnt1= mysqli_num_rows($sqlHoliday9);
		//$numHoliday = mysql_num_rows($resHoliday9);
		//echo "numHolidaykkk = ".$numHoliday."<br>";
	}
	while($no_cnt1 > 0);
$endAuditDate = $endDateWithHoliday;
 
	$update = generateSQL("UPDATE `cca_manageplan` SET `audit_start_date` = ?, `audit_end_date` = ? WHERE `org_id` = ? " ,array($startdate,$endDateWithHoliday,$avialable_org['org_id']),true,$mysqli);

	$organisation = $avialable_org['id'];

$find_start = generateSQL("SELECT audit_end_date  FROM `cca_manageplan` WHERE `id` =?" ,array($avialable_org['id']),false,$mysqli);
 $find_start = reset($find_start);

 $startdate =date('Y-m-d', strtotime( $find_start['audit_end_date']. '+1 day'));

 // $selct_next = generateSQL("SELECT id FROM  `cca_manageplan` WHERE team_id=1 and id > 1",array($team,'1'),false,$mysqli);

 $selct_next=mysqli_query($mysqli,"SELECT id FROM  `cca_manageplan` WHERE team_id='".$team."' and id > '".$avialable_org['id']."'");

while($key = mysqli_fetch_array($selct_next)) {

 $cal_mandays = generateSQL("SELECT sum(mandays_audit) as totalassigndays,sum(mandays_review) as totalreviewdays FROM `cca_pendingyear` WHERE `org_id` = ? AND status=?",array($avialable_org['org_id'],'1'),false,$mysqli);
 	$res_mandays = reset($cal_mandays);
$total_mandys = $res_mandays['totalassigndays'] + $res_mandays['totalreviewdays'] + '4';

$sqlselectmemberwithoutpion=generateSQL("select count(*) as noofemployee from cca_team_emp_role where  team_id = ?",array($team),false,$mysqli) ;
 
$row_detailsuser       = reset($sqlselectmemberwithoutpion);
 $totalMan  = $row_detailsuser['noofemployee'];
	$Days = $res_mandays['totalassigndays'] * 2 / $totalMan ; 
	$totalDays = round($Days);

	
	//echo "totalDays = ".$totalDays."<br>";
	$totalDays = $totalDays + $res_mandays['totalreviewdays'] + '4';


	$endDateWithHoliday = date('Y-m-d', strtotime($startdate . '+'.$totalDays.' day'));
	
	$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));

	$sqlHoliday = mysqli_query($mysqli,"SELECT  * FROM    cca_calendar AS c WHERE   c.hl_date >='".$startdate."' AND  c.hl_date <='".$endDateWithHoliday."'");
	$no_cnt=mysqli_num_rows($sqlHoliday);
	
	do
	{
		$endDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+'.$no_cnt.' day'));
		$sqlHoliday9 =mysqli_query($mysqli,"SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'");
		
		//echo "sqlho=".$sqlHoliday9."<br>";
		//$resHoliday9 = mysql_query($sqlHoliday9);
		$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));
		$no_cnt1= mysqli_num_rows($sqlHoliday9);
		//$numHoliday = mysql_num_rows($resHoliday9);
		//echo "numHolidaykkk = ".$numHoliday."<br>";
	}
	while($no_cnt1 > 0);
$endAuditDate = $endDateWithHoliday;
	
	$update = generateSQL("UPDATE `cca_manageplan` SET `audit_start_date` = ?, `audit_end_date` = ? WHERE `id` = ? " ,array($startdate,$endDateWithHoliday,$key['id']),true,$mysqli);
	
	$startdate = $startDateWithHoliday;
	
	
}
}
else{



$delete_inst = generateSQL("UPDATE `cca_pendingyear` SET `status` = '0' WHERE `cca_pendingyear`.`id` = ?",array($year_id),true,$mysqli);


$avialable_org = generateSQL("SELECT org_id,id,audit_start_date  FROM `cca_manageplan` WHERE `org_id` =?" ,array($inst_id),false,$mysqli);
$avialable_org = reset($avialable_org);

$selct_next=mysqli_query($mysqli,"SELECT id FROM  `cca_manageplan` WHERE team_id='".$team."' and id > '".$avialable_org['id']."'");
	
$startdate =date('Y-m-d', strtotime( $avialable_org['audit_start_date']));

while($key = mysqli_fetch_array($selct_next)) {

 $cal_mandays = generateSQL("SELECT sum(mandays_audit) as totalassigndays,sum(mandays_review) as totalreviewdays FROM `cca_pendingyear` WHERE `org_id` = ? AND status=?",array($avialable_org['org_id'],'1'),false,$mysqli);
 	$res_mandays = reset($cal_mandays);
$total_mandys = $res_mandays['totalassigndays'] + $res_mandays['totalreviewdays'] + '4';

$sqlselectmemberwithoutpion=generateSQL("select count(*) as noofemployee from cca_team_emp_role where  team_id = ?",array($team),false,$mysqli) ;
 
$row_detailsuser       = reset($sqlselectmemberwithoutpion);
 $totalMan  = $row_detailsuser['noofemployee'];
	$Days = $res_mandays['totalassigndays'] * 2 / $totalMan ; 
	$totalDays = round($Days);

	
	//echo "totalDays = ".$totalDays."<br>";
	$totalDays = $totalDays + $res_mandays['totalreviewdays'] + '4';


	$endDateWithHoliday = date('Y-m-d', strtotime($startdate . '+'.$totalDays.' day'));
	
	$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));

	$sqlHoliday = mysqli_query($mysqli,"SELECT  * FROM    cca_calendar AS c WHERE   c.hl_date >='".$startdate."' AND  c.hl_date <='".$endDateWithHoliday."'");
	$no_cnt=mysqli_num_rows($sqlHoliday);
	
	do
	{
		$endDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+'.$no_cnt.' day'));
		$sqlHoliday9 =mysqli_query($mysqli,"SELECT  * FROM   cca_calendar WHERE  hl_date >='".$startDateWithHoliday."' AND   hl_date =<'".$endDateWithHoliday."'");
		
		//echo "sqlho=".$sqlHoliday9."<br>";
		//$resHoliday9 = mysql_query($sqlHoliday9);
		$startDateWithHoliday = date('Y-m-d', strtotime($endDateWithHoliday . '+1 day'));
		$no_cnt1= mysqli_num_rows($sqlHoliday9);
		//$numHoliday = mysql_num_rows($resHoliday9);
		//echo "numHolidaykkk = ".$numHoliday."<br>";
	}
	while($no_cnt1 > 0);
$endAuditDate = $endDateWithHoliday;
	
	$update = generateSQL("UPDATE `cca_manageplan` SET `audit_start_date` = ?, `audit_end_date` = ? WHERE `id` = ? " ,array($startdate,$endDateWithHoliday,$key['id']),true,$mysqli);
	
	$startdate = $startDateWithHoliday;
	
	
}

$delete_year = generateSQL("DELETE FROM `cca_manageplan` WHERE `cca_manageplan`.`org_id` =?",array($inst_id),true,$mysqli);


}
}
?>