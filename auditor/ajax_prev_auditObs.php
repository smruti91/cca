<?php
   session_start();
   include_once("../config.php");
   include("../common_functions.php");
 // print_r($_SESSION);
  //echo  count($_POST);

   $data =  extract($_POST);
    //echo count($audit_type);
     
      $manageplan_id = $_SESSION['mngplan_id'];
      $user_id = $_SESSION['userid'];
      $para_id = $_SESSION['paraid'];
       $ipaddress=get_client_ip();

   if( isset($_POST['save_complaince']) )
   {
        $num = count($audit_type);
       
        
       // $msg = array();
        for($i = 0;$i<$num;$i++  )
        {
           $sql_complaince_0 = " INSERT INTO  cca_para_2a (mngplan_id , paragraph_id , audit_type ,audit_no, year , no_objctn_para ,audit_obs,compliance , status , version,add_by,add_ip) values ( '$manageplan_id' , '$para_id' , '$audit_type[$i]' , '$audit_no[$i]', '$year[$i]' , '$no_obs_para[$i]' ,'$audit_obs[$i]','$complaince[$i]' , 'draft' , 0 ,'$user_id' ,'$ipaddress' )  ";
          // echo $sql_complaince;
           $insert = mysqli_query($mysqli , $sql_complaince_0);
           $last_id = $mysqli->insert_id;
           //echo $last_id;
            if($last_id){
                $sql_complaince_1 = " INSERT INTO  cca_para_2a (mngplan_id , paragraph_id , audit_type ,audit_no, year , no_objctn_para ,audit_obs,compliance , status , complaince_id, version , add_by , add_ip) values ( '$manageplan_id' , '$para_id' , '$audit_type[$i]' , '$audit_no[$i]', '$year[$i]' , '$no_obs_para[$i]' ,'$audit_obs[$i]','$complaince[$i]' , 'draft' ,'$last_id', 1 , '$user_id' ,'$ipaddress' )  ";
                 $insert1 = mysqli_query($mysqli , $sql_complaince_1);

                  if($insert1){
                    // header('Location: manage_auditreport.php ');
                     $msg[] = "success"; 
                     //$_SESSION['type'] = "success";
                     //array_push($msg , "success");
                  }else{
                     echo("Error description: " . $mysqli -> error);
                  }
            
            }else{
                echo("Error description: " . $mysqli -> error);
            }
           
        }
          
       //print_r($msg);
        if( in_array( "success", $msg) ){
             //$_SESSION['type'] = "success";
            
            echo "<script> sessionStorage.setItem('message', 'Complaince added successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'manage_auditreport.php';</script>";
            
             
        }
          
   }

  
    if( isset($_POST['Update_complaince']) ){

        //echo 1123;exit;
        //print_r($_POST);exit;
          $num = count($audit_type);

            for($i = 0;$i<$num;$i++  ){

                 //add new row 
                 if($para_edit_id[$i] == -1){

                      $sql_complaince_0 = " INSERT INTO  cca_para_2a (mngplan_id , paragraph_id , audit_type , audit_no , year , no_objctn_para ,audit_obs,compliance , status , version , add_by , add_ip ) values ( '$manageplan_id' , '$para_id' , '$audit_type[$i]' , '$audit_no[$i]', '$year[$i]' , '$no_obs_para[$i]' ,'$audit_obs[$i]','$complaince[$i]' , 'draft' , 0 ,'$user_id' ,'$ipaddress')  ";
                      //echo $sql_complaince_0; exit;
                      $insert = mysqli_query($mysqli , $sql_complaince_0);
                      $last_id = $mysqli->insert_id;
                      //echo $last_id;
                      if($last_id){
                          $sql_complaince_1 = " INSERT INTO  cca_para_2a (mngplan_id , paragraph_id , audit_type , audit_no , year , no_objctn_para ,audit_obs,compliance , status , complaince_id, version , add_by , add_ip) values ( '$manageplan_id' , '$para_id' , '$audit_type[$i]' , '$audit_no[$i]', '$year[$i]' , '$no_obs_para[$i]' ,'$audit_obs[$i]','$complaince[$i]' , 'draft' ,'$last_id', 1 ,'$user_id' ,'$ipaddress')  ";
                          //echo $sql_complaince_1; exit;
                           $insert1 = mysqli_query($mysqli , $sql_complaince_1);

                            if($insert1){
                               //header('Location: manage_auditreport.php ');
                              $msg['message'] =  "success" ;
                            }else{
                               echo("Error description: " . $mysqli -> error);
                            }
                      
                      }else{
                          echo("Error description: " . $mysqli -> error);
                      }
                 }
               $update_sql= "UPDATE `cca_para_2a` SET  `audit_type` = '$audit_type[$i]', `audit_no` = '$audit_no[$i]', `year` = '$year[$i]', `no_objctn_para` = '$no_obs_para[$i]', `audit_obs` = '$audit_obs[$i]', `compliance` = '$complaince[$i]' WHERE CONCAT(`cca_para_2a`.`id`) = '$para_edit_id[$i]' ";
              
                $update  = mysqli_query($mysqli , $update_sql);
               if( $update ){
                   $update_sql1= "UPDATE `cca_para_2a` SET  `audit_type` = '$audit_type[$i]',  `audit_no` = '$audit_no[$i]', `year` = '$year[$i]', `no_objctn_para` = '$no_obs_para[$i]', `audit_obs` = '$audit_obs[$i]', `compliance` = '$complaince[$i]' WHERE complaince_id = '$para_edit_id[$i]' ";

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
              
               
            }
        
        // if($msg['message'] == 'success'){
        //       header('Location: manage_auditreport.php ');
        // }

             // show message  

               if( in_array( "success", $msg) ){
                         
               echo "<script> sessionStorage.setItem('message', 'Complaince update successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'manage_auditreport.php';</script>";
            
             
        }

    }

    if(isset($_POST['action']) =='delete' ){

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
               //$_SESSION['type'] = "success";
              
               echo "success";
          }
    }
 ?>