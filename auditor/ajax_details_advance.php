<?php
   session_start();
   include_once("../config.php");
   include("../common_functions.php");

  // print_r($_SESSION);
    print_r($_POST); exit;

     $data =  extract($_POST);
    //echo count($audit_type);
     
      $manageplan_id = $_SESSION['mngplan_id'];
      $user_id = $_SESSION['userid'];
      $para_id = $_SESSION['paraid'];
      $ipaddress=get_client_ip();

      if(isset($_POST['save'])) {

      	 $num = count($advnc_outstanding_asOn);

      	 for ($i=0; $i <$num ; $i++) { 
      	 	
      	 	 $sql_complaince_0 = " INSERT INTO  cca_para_c (mngplan_id , paragraph_id , advnc_outstd_asOn ,cash_book, amnt_outstd , amnt_paid_atAudit ,amnt_adjusted,balance_asOn ,amnt_outstd_audit,amnt_outstd_cashBook, status , version,add_by,add_ip) values ( '$manageplan_id' , '$para_id' , '$advnc_outstanding_asOn[$i]' , '$cashBook[$i]', '$amut_outStanding[$i]' , '$amut_paid_audit_period[$i]' ,'$amut_adjust[$i]','$balance_asOn[$i]' ,'$amut_outStanding_audit[$i]' ,'$amut_outStanding_cashBook[$i]' , 'draft' , 0 ,'$user_id' ,'$ipaddress' )  ";

      	 	 //echo $sql_complaince_0 ;
      	 	 $insert = mysqli_query($mysqli , $sql_complaince_0);
             $last_id = $mysqli->insert_id;
             


              if($last_id){
                  $sql_complaince_1 = " INSERT INTO  cca_para_c (mngplan_id , paragraph_id , advnc_outstd_asOn ,cash_book, amnt_outstd , amnt_paid_atAudit ,amnt_adjusted,balance_asOn ,amnt_outstd_audit,amnt_outstd_cashBook, status , version,add_by, tbl_id,add_ip) values ( '$manageplan_id' , '$para_id' , '$advnc_outstanding_asOn[$i]' , '$cashBook[$i]', '$amut_outStanding[$i]' , '$amut_paid_audit_period[$i]' ,'$amut_adjust[$i]','$balance_asOn[$i]' ,'$amut_outStanding_audit[$i]' ,'$amut_outStanding_cashBook[$i]' , 'draft' , 1 ,'$user_id' , '$last_id','$ipaddress' )  ";
                  // echo $sql_complaince_1; exit;
      	 	     $insert1 = mysqli_query($mysqli , $sql_complaince_1);

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
            
            echo "<script> sessionStorage.setItem('message', 'Details of Advance  added successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'manage_auditreport.php';</script>";
            
             
        }
      }


      // udate data

      
      if(isset($_POST['update'])) {

          $num = count($advnc_outstanding_asOn);

          for ($i=0; $i <$num ; $i++) { 
            
             $sql_complaince_0 = " INSERT INTO  cca_para_c (mngplan_id , paragraph_id , advnc_outstd_asOn ,cash_book, amnt_outstd , amnt_paid_atAudit ,amnt_adjusted,balance_asOn ,amnt_outstd_audit,amnt_outstd_cashBook, status , version,add_by,add_ip) values ( '$manageplan_id' , '$para_id' , '$advnc_outstanding_asOn[$i]' , '$cashBook[$i]', '$amut_outStanding[$i]' , '$amut_paid_audit_period[$i]' ,'$amut_adjust[$i]','$balance_asOn[$i]' ,'$amut_outStanding_audit[$i]' ,'$amut_outStanding_cashBook[$i]' , 'draft' , 0 ,'$user_id' ,'$ipaddress' )  ";

             //echo $sql_complaince_0 ;
             $insert = mysqli_query($mysqli , $sql_complaince_0);
             $last_id = $mysqli->insert_id;
             


              if($last_id){
                  $sql_complaince_1 = " INSERT INTO  cca_para_c (mngplan_id , paragraph_id , advnc_outstd_asOn ,cash_book, amnt_outstd , amnt_paid_atAudit ,amnt_adjusted,balance_asOn ,amnt_outstd_audit,amnt_outstd_cashBook, status , version,add_by, tbl_id,add_ip) values ( '$manageplan_id' , '$para_id' , '$advnc_outstanding_asOn[$i]' , '$cashBook[$i]', '$amut_outStanding[$i]' , '$amut_paid_audit_period[$i]' ,'$amut_adjust[$i]','$balance_asOn[$i]' ,'$amut_outStanding_audit[$i]' ,'$amut_outStanding_cashBook[$i]' , 'draft' , 1 ,'$user_id' , '$last_id','$ipaddress' )  ";
                  // echo $sql_complaince_1; exit;
                 $insert1 = mysqli_query($mysqli , $sql_complaince_1);

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
            
            echo "<script> sessionStorage.setItem('message', 'Details of Advance  added successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'manage_auditreport.php';</script>";
            
             
        }
      }

 ?>