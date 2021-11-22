<?php
   session_start();
   include_once("../config.php");
   include("../common_functions.php");
  //print_r($_SESSION);
  //print_r($_POST); exit;
   $data =  extract($_POST);
    //echo count($audit_type);
      //print_r($_POST);exit;
       $manageplan_id = $_SESSION['mngplan_id'];
       $user_id = $_SESSION['userid'];
       $para_id = $_SESSION['paraid'];
       $ipaddress=get_client_ip();

 if( isset($_POST['action'])  && $_POST['action'] == "save_irreg" )
   {
      
      
           $sql_irreg_0 = " INSERT INTO  cca_para_2b (mngplan_id , para_id , irreg_notice, status , version ,add_by,add_ip ) values ( '$manageplan_id' , '$para_id' , '$irreg_notice' , 'draft' , 0 ,'$user_id' ,'$ipaddress')  ";
          // echo $sql_complaince;
           $insert = mysqli_query($mysqli , $sql_irreg_0);
           $last_id = $mysqli->insert_id;
           //echo $last_id;
            if($last_id){
                $sql_irreg_1 = " INSERT INTO  cca_para_2b (mngplan_id , para_id , irreg_notice , status , version,irreg_id ,add_by,add_ip) values ( '$manageplan_id' , '$para_id' , '$irreg_notice' , 'draft' , 1 ,'$last_id' ,'$user_id' ,'$ipaddress' )  ";
                 $insert1 = mysqli_query($mysqli , $sql_irreg_1);

                  if($insert1){
                   
                     $msg = "success"; 
                    
                  }else{
                     echo("Error description: " . $mysqli -> error);
                  }
            
            }else{
                echo("Error description: " . $mysqli -> error);
            }

            echo $msg ;
            exit;
          
   }

   // update complaince details

 if( isset($_POST['action'])  && $_POST['action'] == "Update_irreg" )
    {
       
         
               $update_sql= "UPDATE `cca_para_2b` SET  `irreg_notice` = '$irreg_notice' WHERE CONCAT(`cca_para_2b`.`id`) = '$edit_id' ";
              
                $update  = mysqli_query($mysqli , $update_sql);
               if( $update ){
                   $update_sql1= "UPDATE `cca_para_2b` SET  `irreg_notice` = '$irreg_notice' WHERE irreg_id = '$edit_id' ";

                    $update1  = mysqli_query($mysqli , $update_sql1);

                   if($update1){
                      $msg =  "success" ;
                   } else{
                   echo("Error description: " . $mysqli -> error);
               }
               }
               else{
                   echo("Error description: " . $mysqli -> error);
               }
        echo $msg;
    }

    if(isset($_POST['action']) && $_POST['action'] == "delete" ){

        $id = $_POST['edit_id'];

         $delete_sql_0 = " DELETE FROM `cca_para_2b` WHERE id='$id' "; 
          $delete_0 = mysqli_query($mysqli, $delete_sql_0);

          if($delete_0){
              $delete_sql_1 = " DELETE FROM `cca_para_2b` WHERE irreg_id='$id' "; 
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