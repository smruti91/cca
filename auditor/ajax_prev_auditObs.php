<?php
   session_start();
   include_once("../config.php");
   include("../common_functions.php");
  // print_r($_SESSION);
  // print_r($_POST); exit;

   $data =  extract($_POST);
    //echo count($audit_type);
     
      $manageplan_id = $_SESSION['mngplan_id'];
      $user_id = $_SESSION['userid'];
      $para_id = $_SESSION['paraid'];
       $ipaddress=get_client_ip();

   if( isset($_POST['action'])  && $_POST['action'] == "save_complaince" )
   {
               
      
           $sql_complaince_0 = " INSERT INTO  cca_para_2a (mngplan_id , para_id , audit_type ,audit_no, year , no_objctn_para ,audit_obs,compliance , status , version,add_by,add_ip) values ( '$manageplan_id' , '$para_id' , '$audit_type' , '$audit_no', '$year' , '$no_obs_para' ,'$audit_obs','$complaince' , 'draft' , 0 ,'$user_id' ,'$ipaddress' )  ";
          // echo $sql_complaince;
           $insert = mysqli_query($mysqli , $sql_complaince_0);
           $last_id = $mysqli->insert_id;

           //print_r($insert->num_rows);exit;
            if($last_id){
                $sql_complaince_1 = " INSERT INTO  cca_para_2a (mngplan_id , para_id , audit_type ,audit_no, year , no_objctn_para ,audit_obs,compliance , status , complaince_id, version , add_by , add_ip) values ( '$manageplan_id' , '$para_id' , '$audit_type' , '$audit_no', '$year' , '$no_obs_para' ,'$audit_obs','$complaince' , 'draft' ,'$last_id', 1 , '$user_id' ,'$ipaddress' )  ";
                 $insert1 = mysqli_query($mysqli , $sql_complaince_1);

                  if($insert1){
                  
                     $msg = "success"; 
                   
                  }else{
                     echo("Error description: " . $mysqli -> error);
                  }
            
            }else{
                echo("Error description: " . $mysqli -> error);
            }
       
       echo $msg;
   }

  
    if(  isset($_POST['action']) && $_POST['action'] == 'Update_complaince' ){

                              
               $update_sql= "UPDATE `cca_para_2a` SET  `audit_type` = '$audit_type', `audit_no` = '$audit_no', `year` = '$year', `no_objctn_para` = '$no_obs_para', `audit_obs` = '$audit_obs', `compliance` = '$complaince' WHERE CONCAT(`cca_para_2a`.`id`) = '$edit_id' ";
              //echo $update_sql;exit;
                $update  = mysqli_query($mysqli , $update_sql);
               if( $update ){
                   $update_sql1= "UPDATE `cca_para_2a` SET  `audit_type` = '$audit_type',  `audit_no` = '$audit_no', `year` = '$year', `no_objctn_para` = '$no_obs_para', `audit_obs` = '$audit_obs', `compliance` = '$complaince' WHERE complaince_id = '$edit_id' ";

                    $update1  = mysqli_query($mysqli , $update_sql1);

                   if($update1){
                      $msg['message'] =  "success" ;
                   } else{
                   echo("Error description: " . $mysqli -> error);
               }
               }
               else{
                   echo("Error description: " . $mysqli -> error);
               }
         
          exit;
    }

    if(isset($_POST['action'])  && $_POST['action'] == 'delete' ){

        $id = $_POST['edit_id'];

         $delete_sql_0 = " DELETE FROM `cca_para_2a` WHERE id='$id' "; 
          $delete_0 = mysqli_query($mysqli, $delete_sql_0);

          if($delete_0){
              $delete_sql_1 = " DELETE FROM `cca_para_2a` WHERE complaince_id='$id' "; 
               $delete_1 = mysqli_query($mysqli, $delete_sql_1);

               if($delete_1){
                   $msg['message'] =  "success" ;
                }else{
                   echo("Error description: " . $mysqli -> error);
                }
          }
          else{
             echo("Error description: " . $mysqli -> error);
          }

          if( in_array( "success", $msg) ){
                            
               echo "success";
          }
    }
 ?>