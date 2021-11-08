<?php
   session_start();
   include_once("../config.php");
   include("../common_functions.php");

      $data =  extract($_POST);


      $manageplan_id = $_SESSION['mngplan_id'];
      $user_id = $_SESSION['userid'];
      $para_id = $_SESSION['paraid'];
      $ipaddress=get_client_ip();

   //print_r($_POST);exit;

    if( isset($_POST['save_assesment']) ){

       $num = count($assessment);

       for($i = 0;$i<$num;$i++  )
        {
           $sql_complaince_0 = " INSERT INTO  cca_para_4a (mngplan_id , para_id , assmnt_aspt ,strong_ctrl, weak_ctrl , assmnt_result ,improvment, status , version,add_by,add_ip) values ( '$manageplan_id' , '$para_id' , '$assessment[$i]' , '$strong[$i]', '$weak[$i]' , '$checklist[$i]' ,'$improvement[$i]','draft' , 0 ,'$user_id' ,'$ipaddress' )  ";
          // echo $sql_complaince;
           $insert = mysqli_query($mysqli , $sql_complaince_0);
           $last_id = $mysqli->insert_id;
           //echo $last_id;
            if($last_id){
                $sql_complaince_1 = " INSERT INTO  cca_para_4a (mngplan_id , para_id , assmnt_aspt ,strong_ctrl, weak_ctrl , assmnt_result ,improvment, status ,controls_id, version , add_by , add_ip) values ( '$manageplan_id' , '$para_id' , '$assessment[$i]' , '$strong[$i]', '$weak[$i]' , '$checklist[$i]' ,'$improvement[$i]', 'draft' ,'$last_id', 1 , '$user_id' ,'$ipaddress' )  ";
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
               echo 'success';
            echo "<script> </script>";
            //header('Location: internal_control.php ');
             
        }

    }

    if( isset($_POST['action']) && $_POST['action'] == 'update_assesment'){

        //echo 1123;exit;
        //print_r($_POST);exit;
         

               $update_sql= "UPDATE `cca_para_4a` SET  `assmnt_aspt` = '$assessment', `strong_ctrl` = '$strong', `weak_ctrl` = '$weak', `assmnt_result` = '$checklist', `improvment` = '$improvement'  WHERE CONCAT(`cca_para_4a`.`id`) = '$edit_id' ";
              
                $update  = mysqli_query($mysqli , $update_sql);
               if( $update ){
                   $update_sql1= "UPDATE `cca_para_4a` SET  `assmnt_aspt` = '$assessment',  `strong_ctrl` = '$strong', `weak_ctrl` = '$weak', `assmnt_result` = '$checklist', `improvment` = '$improvement'  WHERE controls_id = '$edit_id' ";

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
       
             // show message  
               


        //        if( in_array( "success", $msg) ){
                         
        //        echo "<script> sessionStorage.setItem('message', 'Assessment Aspect update successfully') ; sessionStorage.setItem('type', 'success') ; window.location = 'internal_control.php';</script>";
            
             
        // }

    }

   if(isset($_POST['action']) && $_POST['action'] == 'delete' ){

        $id = $_POST['edit_id'];

         $delete_sql_0 = " DELETE FROM `cca_para_4a` WHERE id='$id' "; 
          $delete_0 = mysqli_query($mysqli, $delete_sql_0);

          if($delete_0){
              $delete_sql_1 = " DELETE FROM `cca_para_4a` WHERE controls_id='$id' "; 
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