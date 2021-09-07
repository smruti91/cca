<?php
//session_start();

include("../common_functions.php");
include_once("../config.php");
//print_r($_POST);
 if(isset($_POST['action'])&& !empty($_POST['action'] )){

 
 $plan = $_POST['plan'];
 $dept= $_POST['dept'];
 $team= $_POST['team'];
 $inst_audt= $_POST['inst_audt'];
 $startdate = date('Y-m-d', strtotime($_POST['startdate']));
 $inst_id= explode(",", $_POST['chosen']);
 $inst_year = explode(",", $_POST['chose']);

 for($i=0;$i<count($inst_year);$i++)
 	//foreach($inst_year as $inst_ids)
   {
   	

   		//print_r($i);
	$inst_years = explode("-", $inst_year[$i]);
	
	//print_r($inst_ids);
  //
 $show_year = $inst_years['1'].'-'.$inst_years['2'];
 
 $mandays = generateSQL("SELECT mandays_audit FROM cca_institutions where id=?",array($inst_id[$i]),false,$mysqli);
 
$mandays = reset($mandays);

	$inser_year = generateSQL("INSERT INTO cca_pendingyear(pending_year,org_id,show_year,cat_audit_type,mandays) values (?,?,?,?,?)",array($inst_years['1'],$inst_id[$i],$show_year,$inst_audt,$mandays['mandays_audit']),true,$mysqli);
	
 }
 $a = array_unique($inst_id);
 foreach ($a as $inst_ids) {
 
 	$cal_mandays = generateSQL("SELECT sum(mandays) as totalassigndays FROM `cca_pendingyear` WHERE `org_id` = ? ",array($inst_ids),false,$mysqli);
 	$res_mandays = reset($cal_mandays);

$inser_inst = generateSQL("INSERT INTO cca_manageplan(dept_id,org_id,team_id,plan_id,assighn_days) values (?,?,?,?,?)",array($dept,$inst_ids,$team,$plan,$res_mandays['totalassigndays']),true,$mysqli);
 }

}

?>