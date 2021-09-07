<?php
include_once("../config.php");
include("../common_functions.php");
session_start();
   //print_r($_POST);
  //print_r($_FILES);
 //  exit;

    if((!empty($_FILES["circular_file"])) && ($_FILES['circular_file']['error'] == 0))
    {
    	
    	$subject_id       = $_POST['circular_subject'];
    	$circular_date    =  date("Y-m-d",strtotime($_POST['circular_date']));
        $date             = date("Y/m/d");
        $user_id          = $_POST['user_id'];
        $edit_id          = $_POST['notice_edit_id'];

      $filename =strtolower(basename($_FILES['circular_file']['name']));
      $ext = substr($filename, strrpos($filename, '.') + 1);
      
      if ($ext == "pdf" )
      {
                    $ext=".".$ext;
                    $cca_referenceno= gen_uuid();
                    $newFilename = $cca_referenceno . $ext;
                    //$file_name   
                    $newname = '../circular/'.$newFilename;
                     
                              
                           if(move_uploaded_file($_FILES['circular_file']['tmp_name'],$newname))
                           {
                            
                              if($edit_id > 0){
                              	$sql = "UPDATE `cca_circularnotice` SET sub_id = '".$subject_id."' , doc_name = '".$newFilename."',circular_date = '".$circular_date."' WHERE notice_id = '".$edit_id."' " ;

                                $message = "Circular Notice Updated Successfully";

                              } 
                              else{

                              	$sql = "INSERT INTO `cca_circularnotice` (sub_id,user_id,doc_name,circular_date,`date`) 
                              	        values ('".$subject_id."', '".$user_id."' ,'".$newFilename."','".$circular_date."','".$date."') ";

                                $message = "Circular Notice Added Successfully";
                              }                            
                              
                             
                              $query_insert = mysqli_query($mysqli,$sql);
                               
                               echo "Success#".$message; 
                               //header("Location: circular_notice.php");
                               exit;
                           }
                    
                     else
                     {
                       echo "Error#File size exids";
                       exit;
                     }
      }
    }

    if ( isset($_FILES["circular_file"]) && $_FILES['circular_file']['error'] >0 && $_POST['notice_edit_id'] >0 )
    {
         	$subject_id       = $_POST['circular_subject'];
             	$circular_date    =  date("Y-m-d",strtotime($_POST['circular_date']));
                 
                 $user_id          = $_POST['user_id'];
                 $edit_id          = $_POST['notice_edit_id'];

                 $sql = "UPDATE `cca_circularnotice` SET sub_id = '".$subject_id."' , circular_date = '".$circular_date."' WHERE notice_id = '".$edit_id."' " ;

                 $res = mysqli_query($mysqli,$sql);
                 
                 if($res)
                 {
                 	 $message = "Circular Notice Updated Successfully";
                 }
                 else
                 {
                 	$message = "Error#Something Went Wrong".$mysqli->error;
                 }
                                       
                 echo "Success#".$message; 
                                      
                 exit;
    }
    
    

    if (isset($_POST['action']) && $_POST['action']=='edit' ) {
    	
    	$notice_id = $_POST['notice_id'];
    	
    	$sql = "SELECT cn.notice_id,sub.sub_id,cn.doc_name,cn.circular_date FROM `cca_circularnotice` cn , `cca_circular_subject` sub WHERE cn.sub_id = sub.sub_id AND cn.notice_id = ".$notice_id;

    	$res = mysqli_query($mysqli,$sql);
        $row = mysqli_fetch_array($res);
        $data['notice_id'] = $row['notice_id'];
        $data['sub'] = $row['sub_id'];
        $data['file_path'] = $row['doc_name'];
        $data['circular_date'] = $row['circular_date'];

         echo json_encode($data);
         exit;
    }
    //send  Circular Notice


    if (isset($_POST['action']) && $_POST['action']=='send' ) {
    	
    	$notice_id = $_POST['notice_id'];
    	
    	$sql = " UPDATE  `cca_circularnotice` SET status = 1 WHERE notice_id = ".$notice_id;

    	$res = mysqli_query($mysqli,$sql);
        
        if($res)
        {
           $message =  "Success#Circular / Notice Send Successfully";
        }
        else
        {
           $message =  "Error#Something Went Wrong".$mysqli->error;
        }
       
       echo $message;
       exit;
    }

    //delete edit Circular Notice

       if (isset($_POST['action']) && $_POST['action']=='delete_report' ) {

       	 $notice_id = $_POST['notice_id'];
         
    	$sql = "DELETE FROM  `cca_circularnotice`  WHERE notice_id =  ".$notice_id;

    	$res = mysqli_query($mysqli,$sql);
        
        if ($res) {
        	$message = "Success#Circular Deleted Successfully";
        }
        else
        {
        	$message = "Error#Something Wrong".$mysqli->error;
        
        }

        echo $message;
        exit;

       }

    if (isset($_POST['action']) && $_POST['action']=='remove_report' ) {
    	$notice_id = $_POST['notice_id'];
    	 
    	  $notice_sql = "SELECT * FROM  `cca_circularnotice` WHERE notice_id = '".$notice_id."' AND  status = 0 ";
    	  
    	  $res = mysqli_query($mysqli,$notice_sql);
          $row = mysqli_fetch_array($res);
          
          $file_path = '../circular/'.$row['doc_name'];
          
          
          if($file_path)
          {
          	
          	$doc_sql = "UPDATE `cca_circularnotice` SET doc_name = 0 WHERE notice_id = '".$notice_id."' AND  status = 0 " ;
            $res = mysqli_query($mysqli,$doc_sql);

            if($res)
            {
            	unlink($file_path);
            	$message =  "Success#File Remove Successfully";
            	
            }
            else{
            	$message= "Error#Sql ERROR".$mysqli->error;
            }
          }

          else
          {
          	$message= "Error#File Not Found";
          }

    	

      echo $message;


    }
  
  // circular Subject Section starts  

    //edit Subject 

    if (isset($_POST['action']) && $_POST['action']=='edit_sub' ) {
    	
    	$sub_id = $_POST['sub_id'];
    	
    	$sql = "SELECT * FROM  `cca_circular_subject`  WHERE sub_id =  ".$sub_id;

    	$res = mysqli_query($mysqli,$sql);
        $row = mysqli_fetch_array($res);
       
        $data['sub_name'] = $row['sub_name'];
       

         echo json_encode($data);
         exit;
    }

    //********************

    //delete edit Subject 

       if (isset($_POST['action']) && $_POST['action']=='delete_sub' ) {

       	 $sub_id = $_POST['sub_id'];
         
    	$sql = "DELETE FROM  `cca_circular_subject`  WHERE sub_id =  ".$sub_id;

    	$res = mysqli_query($mysqli,$sql);
        
        if ($res) {
        	$message = "Success#Subject Deleted Successfully";
        }
        else
        {
        	$message = "Error#Something Wrong".$mysqli->error;
        
        }

        echo $message;
        exit;

       }

    // ********** End delete subject  *********

    // Add Subject
          if (isset($_POST['action']) && $_POST['action']=='add_sub' ) {

          	 $sub_name = $_POST['sub_name'];
          	 $status = $_POST['status'];
          	 $sub_id = $_POST['sub_id'];

          	 if($sub_id > 0)
          	 {
               $sql = "UPDATE `cca_circular_subject` SET `sub_name` = '".$sub_name."' WHERE sub_id = '".$sub_id."'  ";

               $message = "Success#Subject Updated Successfully";
          	 }
          	 else
          	 {
          	 	$sql = "INSERT INTO `cca_circular_subject` (`sub_name`, `status`) VALUES ( '".$sub_name."', '".$status."') ";

          	 	$message = "Success#Subject Added Successfully";
          	 }
            
            	$res = mysqli_query($mysqli,$sql);
           
           if ($res) {
              echo $message;
             exit;
           }
           else
           {
            echo 	$message = "Error#Something Wrong".$mysqli->error;
            exit;
           
           }

          

          }

    function gen_uuid() 
    { 
       $s = strtoupper(md5(uniqid(date("YmdHis"),true))); 
         $guidText = 
           substr($s,0,4) . '-' . 
           substr($s,4,4)."-" ; 
           $date=date("his");
         return "CCA-".$guidText.$date;
      }

 ?>