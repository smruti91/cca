<?php
    session_start();
   include_once("../config.php");
   include("../common_functions.php");

   
      $data =  extract($_POST);
   //print_r($_POST); exit;
      $manageplan_id = $_SESSION['mngplan_id'];
      $user_id = $_SESSION['userid'];
      $para_id = $_SESSION['paraid'];
      $ipaddress=get_client_ip();

      if(isset($_POST['save'])){

      	 $num = count($record_list_id);

      	 for($i=0; $i<$num ; $i++  )
      	 {
      	 	$sql_maintance_0 = " INSERT INTO `cca_para_3d` ( `mngplan_id`, `paragraph_id`, `recordList_id`, `status`, `version`, `add_by`, `add_ip`) VALUES ('$manageplan_id', '$para_id', '$record_list_id[$i]', '$checklist[$i]', 0, '$user_id', '$ipaddress') ";

      	 	//echo $sql_maintance;

      	 	$insert = mysqli_query($mysqli,$sql_maintance_0) ;
      	 	$last_id = $mysqli->insert_id;

      	 	if($last_id){

      	 	$sql_maintance_1 = " INSERT INTO `cca_para_3d` ( `mngplan_id`, `paragraph_id`, `recordList_id`, `status`, `version`, `add_by`, `tbl_id`, `add_ip`) VALUES ('$manageplan_id', '$para_id', '$record_list_id[$i]', '$checklist[$i]', 1 , '$user_id', '$last_id' , '$ipaddress') ";

      	 	 $insert1 = mysqli_query($mysqli,$sql_maintance_1) ;

      	 	 if($insert1){
      	 		$msg[] = "success";
      	 	}
      	 	else{
      	 		 echo("Error description: " . $mysqli -> error);
      	 	}

      	 	}

      	 	if($insert){
      	 		$msg[] = "success";
      	 	}
      	 	else{
      	 		 echo("Error description: " . $mysqli -> error);
      	 	}
      	 }

           if( in_array( "success", $msg) ){
               $_SESSION['save_id'] = 1;
              
              echo "<script> sessionStorage.setItem('message', ' List of Records verified successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'record_maintenance.php';</script>";
              
               
          }

      	 
      }

      if(isset($_POST['update'])){

         $num = count($record_list_id);

         for ($i=0; $i < $num ; $i++) { 
            
             $update_sql = "UPDATE `cca_para_3d` SET `status` = '$checklist[$i]' WHERE `cca_para_3d`.`id` = '$record_list_id[$i]';";
             $update_res = mysqli_query($mysqli,$update_sql);

             if($update_res){
                
                 $update_sql1 = "UPDATE `cca_para_3d` SET `status` = '$checklist[$i]' WHERE `cca_para_3d`.`tbl_id` = '$record_list_id[$i]';";
                 $update_res1 = mysqli_query($mysqli,$update_sql1);

                 if($update_res1){
                    $msg['message'] =  "success" ;
                 }else{
                   echo("Error description: " . $mysqli -> error);
                   }

             }else{
                   echo("Error description: " . $mysqli -> error);
               }

         }

                if( in_array( "success", $msg) ){

                          
                echo "<script> sessionStorage.setItem('message', 'List of Records verified update successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'manage_auditreport.php';</script>";
             
              
         }

      }

      if(isset($_POST['save_records'])){

        // echo $records_mtnc;

         $sql_records = " INSERT INTO `cca_para_3d1` ( `mngplan_id`, `paragraph_id`, `consequences_records`, `version`, `add_by`, `add_ip`) VALUES ('$manageplan_id', '$para_id',  '$records_mtnc', 0, '$user_id', '$ipaddress')";

         $sql_records_insert = mysqli_query($mysqli,$sql_records);

         $last_id = $mysqli->insert_id;
         echo $last_id;

         if($last_id){

            $sql_records1 = " INSERT INTO `cca_para_3d1` ( `mngplan_id`, `paragraph_id`, `consequences_records`, `version`, `add_by`, `tbl_id`, `add_ip`) VALUES ('$manageplan_id', '$para_id',  '$records_mtnc', 1, '$user_id', '$last_id', '$ipaddress')";

             $sql_records_insert1 = mysqli_query($mysqli,$sql_records1);
            
            if($sql_records_insert1){
               echo "success";
            }
            else{
               echo ("Error description: ". $mysqli-> error );
            }

         }else{
            echo ("Error description: ". $mysqli -> error );
         }
      }

      if(isset($_POST['update_records'])){
         $update_sql = "UPDATE `cca_para_3d1` SET `consequences_records` = '$records_mtnc' WHERE `cca_para_3d1`.`id` = '$consequences_id' ";
         $update_res = mysqli_query($mysqli,$update_sql);

         if($update_res){
                
                 $update_sql1 = "UPDATE `cca_para_3d1` SET `consequences_records` = '$records_mtnc' WHERE `cca_para_3d1`.`tbl_id` = '$consequences_id';";
                 $update_res1 = mysqli_query($mysqli,$update_sql1);

                 if($update_res1){
                    $msg['message'] =  "success" ;
                 }else{
                   echo("Error description: " . $mysqli -> error);
                   }

             }else{
                   echo("Error description: " . $mysqli -> error);
               }

               if( in_array( "success", $msg) ){

                          
                echo "<script> sessionStorage.setItem('message', 'List of Records verified update successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'manage_auditreport.php';</script>";
             
              
         }

      }

?>