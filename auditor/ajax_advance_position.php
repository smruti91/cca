<?php
   session_start();
   include_once("../config.php");
   include("../common_functions.php");

  // print_r($_SESSION);
    //print_r($_POST); exit;

     $data =  extract($_POST);
    //echo count($audit_type);
     
      $manageplan_id = $_SESSION['mngplan_id'];
      $user_id = $_SESSION['userid'];
      $para_id = $_SESSION['paraid'];
      $ipaddress=get_client_ip();

      if(isset($_POST['save'])) {

         $num = count($offical_name);

         for ($i=0; $i <$num ; $i++) { 
            
             $sql_advance_0 = " INSERT INTO  cca_para_c1 (mngplan_id , paragraph_id , offical_name , gpf_Ehrms_id ,scheme_cash_book, amnt_outstd , amnt_paid_atAudit ,amnt_adjusted,balance_asOn ,amnt_outstd_audit,amnt_outstd_cashBook, status , version,add_by,add_ip) values ( '$manageplan_id' , '$para_id' , '$offical_name[$i]' , '$gpf_Ehrms_id[$i]' , '$scheme_cashBook[$i]', '$amut_outStanding[$i]' , '$amut_paid_audit_period[$i]' ,'$amut_adjust[$i]','$balance_asOn[$i]' ,'$amut_outStanding_audit[$i]' ,'$amut_outStanding_cashBook[$i]' , 'draft' , 0 ,'$user_id' ,'$ipaddress' )  ";

            
             $insert = mysqli_query($mysqli , $sql_advance_0);
             $last_id = $mysqli->insert_id;
             


              if($last_id){
                  $sql_advance_1 = " INSERT INTO  cca_para_c1 (mngplan_id , paragraph_id , offical_name , gpf_Ehrms_id ,scheme_cash_book, amnt_outstd , amnt_paid_atAudit ,amnt_adjusted,balance_asOn ,amnt_outstd_audit,amnt_outstd_cashBook, status , version,add_by, tbl_id,add_ip) values ( '$manageplan_id' , '$para_id' , '$offical_name[$i]' , '$gpf_Ehrms_id[$i]' , '$scheme_cashBook[$i]', '$amut_outStanding[$i]' , '$amut_paid_audit_period[$i]' ,'$amut_adjust[$i]','$balance_asOn[$i]' ,'$amut_outStanding_audit[$i]' ,'$amut_outStanding_cashBook[$i]' , 'draft' , 1 ,'$user_id' , '$last_id','$ipaddress' )  ";
                  // echo $sql_complaince_1; exit;
                 $insert1 = mysqli_query($mysqli , $sql_advance_1);

                 if($insert1){
                    
                     $msg[] = "success"; 
                     
                  }else{
                     echo("Error description: " . $mysqli -> error);
                  }

              }else{
                echo("Error description: " . $mysqli -> error);
            }

         }

         if( in_array( "success", $msg) ){
             //$_SESSION['type'] = "success";
            
            echo "<script> sessionStorage.setItem('message', ' Advance  Position added successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'manage_auditreport.php';</script>";
            
             
        }
      }


      // udate data

      
      if(isset($_POST['update'])) {

          $num = count($offical_name);

          for ($i=0; $i <$num ; $i++) { 
               
               if($para_edit_id[$i] == ''){
                 
                  $sql_advance_0 = " INSERT INTO  cca_para_c1 (mngplan_id , paragraph_id , offical_name , gpf_Ehrms_id ,scheme_cash_book, amnt_outstd , amnt_paid_atAudit ,amnt_adjusted,balance_asOn ,amnt_outstd_audit,amnt_outstd_cashBook, status , version,add_by,add_ip) values ( '$manageplan_id' , '$para_id' , '$offical_name[$i]' , '$gpf_Ehrms_id[$i]' , '$scheme_cashBook[$i]', '$amut_outStanding[$i]' , '$amut_paid_audit_period[$i]' ,'$amut_adjust[$i]','$balance_asOn[$i]' ,'$amut_outStanding_audit[$i]' ,'$amut_outStanding_cashBook[$i]' , 'draft' , 0 ,'$user_id' ,'$ipaddress' )  ";

                  //echo $sql_complaince_0 ;
                  $insert = mysqli_query($mysqli , $sql_advance_0);
                  $last_id = $mysqli->insert_id;
                  


                   if($last_id){
                       $sql_advance_1 = " INSERT INTO  cca_para_c1 (mngplan_id , paragraph_id , offical_name , gpf_Ehrms_id ,scheme_cash_book, amnt_outstd , amnt_paid_atAudit ,amnt_adjusted,balance_asOn ,amnt_outstd_audit,amnt_outstd_cashBook, status , version,add_by, tbl_id,add_ip) values ( '$manageplan_id' , '$para_id' , '$offical_name[$i]' , '$gpf_Ehrms_id[$i]' , '$scheme_cashBook[$i]', '$amut_outStanding[$i]' , '$amut_paid_audit_period[$i]' ,'$amut_adjust[$i]','$balance_asOn[$i]' ,'$amut_outStanding_audit[$i]' ,'$amut_outStanding_cashBook[$i]' , 'draft' , 1 ,'$user_id' , '$last_id','$ipaddress' )  ";
                       // echo $sql_complaince_1; exit;
                      $insert1 = mysqli_query($mysqli , $sql_advance_1);

                      if($insert1){
                         
                          $msg[] = "success"; 
                          
                       }else{
                          echo("Error description: " . $mysqli -> error);
                       }

                   }else{
                     echo("Error description: " . $mysqli -> error);
                 }
                 
               }
               else{


                 $update_sql = "UPDATE `cca_para_c1` SET `offical_name` = '$offical_name[$i]', `gpf_Ehrms_id` = '$gpf_Ehrms_id[$i]', `scheme_cash_book` = '$scheme_cashBook[$i]', `amnt_outstd` = '$amut_outStanding[$i]', `amnt_paid_atAudit` = '$amut_paid_audit_period[$i]', `amnt_adjusted` = '$amut_adjust[$i]', `balance_asOn` = '$balance_asOn[$i]', `amnt_outstd_audit` = '$amut_outStanding_audit[$i]', `amnt_outstd_cashBook` = '$amut_outStanding_cashBook[$i]' WHERE `cca_para_c1`.`id` = '$para_edit_id[$i]'";
                 
                $update  = mysqli_query($mysqli , $update_sql);
               if( $update ){
                   $update_sql1= "UPDATE `cca_para_c1` SET `offical_name` = '$offical_name[$i]',  `gpf_Ehrms_id` = '$gpf_Ehrms_id[$i]',  `scheme_cash_book` = '$scheme_cashBook[$i]', `amnt_outstd` = '$amut_outStanding[$i]', `amnt_paid_atAudit` = '$amut_paid_audit_period[$i]', `amnt_adjusted` = '$amut_adjust[$i]', `balance_asOn` = '$balance_asOn[$i]', `amnt_outstd_audit` = '$amut_outStanding_audit[$i]', `amnt_outstd_cashBook` = '$amut_outStanding_cashBook[$i]' WHERE `cca_para_c1`.`tbl_id` = '$para_edit_id[$i]'";

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
            
             

          }

          if( in_array( "success", $msg) ){
             //$_SESSION['type'] = "success";
            
            echo "<script> sessionStorage.setItem('message', 'Advance  Position  Updated successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'manage_auditreport.php';</script>";
            
             
        }
      }

       if(isset($_POST['action']) =='delete' ){

        $id = $_POST['edit_id'];

         $delete_sql_0 = " DELETE FROM `cca_para_c1` WHERE id='$id' "; 
          $delete_0 = mysqli_query($mysqli, $delete_sql_0);

          if($delete_0){
              $delete_sql_1 = " DELETE FROM `cca_para_c1` WHERE tbl_id='$id' "; 
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