<?php
   include_once("../config.php");
   include("../common_functions.php");
   session_start();
   $userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1); 

   //print_r($_POST);
  //echo $_POST['distance'];
  //print_r($_FILES);
  //exit;
   if(isset($_POST['action']) && $_POST['action']=='planName')
   {
     
      $org_id = $_POST['org_id'];
      $plan_sql = "SELECT plan_id,actual_audit_start_date as commencent_dt,actual_audit_end_date as completion_dt FROM `cca_manageplan` WHERE  org_id =". $org_id;
      //echo $plan_sql;exit;
      $res = mysqli_query($mysqli,$plan_sql);
      $row = mysqli_fetch_assoc($res);

      $data['act_dt_commencent'] = $row['commencent_dt'];
      $data['act_dt_completion'] = $row['completion_dt'];
      $data['planId'] = $row['plan_id'];


      echo json_encode($data);
      exit;
      
   }
   if(isset($_POST['action']) && $_POST['action']=='edit')
  {
   
    $id = $_POST['user_id'];

    $fetch_sql = "select * from cca_tour_details where ID =".$id;
   // echo $fetch_sql;
    $res = mysqli_query($mysqli,$fetch_sql);
    $row = mysqli_fetch_array($res);
    //print_r($row);
    $data['tour_for_the_month'] = $row['tour_for_the_month'];
    $data['plan_name'] = $row['plan_name'];
    $data['purpose_category_id'] = $row['t_category'];
    $data['years_of_ac'] = $row['year_of_account'];
    $data['act_dt_commencent'] = $row['act_dt_commencent'];
    $data['act_dt_completion'] = $row['act_dt_completion'];
    $data['dt_commencent'] = $row['dt_commencent'];
    $data['dt_completion'] = $row['dt_completion'];
    $data['purpose'] = $row['purpose'];
    $data['distance'] = $row['distance'];
    $data['remarks'] = $row['remarks'];
    $data['id'] = $row['ID'];
    
     //echo("Error description: " . $mysqli -> error);
    // echo "SUCCESS";
   
    echo json_encode($data);
    exit;
  }

  if(isset($_POST['action'])&& $_POST['action']=='add')
  {
    //print_r($_POST); exit;
    //$plan_id_sql = "select id from "
  $tour_for_month      = date("Y-m-d",strtotime($_POST['tour_for_month']));
  $plan_name           = $_POST['plan_name'];
  $tour_catagory       = $_POST['tour_catagory'];
  $years_of_acc        = $_POST['years_of_acc'];
  $act_dt_commencement = date("Y-m-d",strtotime($_POST['act_dt_commencement']));
  $act_dt_completion   = date("Y-m-d",strtotime($_POST['act_dt_completion']));
  $dt_commencement     = date("Y-m-d",strtotime($_POST['dt_commencement']));
  $dt_completion       = date("Y-m-d",strtotime($_POST['dt_completion']));
  $purpose             = $_POST['purpose'];
  $distance            = $_POST['distance'];
  $remark              = $_POST['remark'];
  $tour_id             = $_POST['tour_id'];
  $dept_id             = $_POST['dept_id'];
  $emp_id              = $_POST['emp_id'];
  $plan_id             = $_POST['plan_id'];
  //echo $tour_id;exit;
  //echo $tour_catagory ; exit;
    if($tour_id !='' )
    {
       $sql = "UPDATE cca_tour_details 

                SET 
               
                tour_for_the_month  = ?,
                plan_name           = ?,
                t_category          = ?,
                year_of_account     = ?,
                act_dt_commencent   = ?,
                act_dt_completion   = ?,
                dt_commencent       = ?,
                dt_completion       = ?,
                purpose             = ?,
                distance            = ?,
                remarks             = ?
                
                WHERE ID = ". $tour_id;

        $insert=generateSQL($sql,array($tour_for_month,$plan_name,$tour_catagory,$years_of_acc,$act_dt_commencement,$act_dt_completion,$dt_commencement,$dt_completion,$purpose,$distance,$remark),true,$mysqli);



                $message = 'Success#Tour Updated Successfully';
                //echo $sql;
    }
    else 
    {
     
        $sql = "INSERT INTO cca_tour_details(
                   user_id,
                   tour_for_the_month,
                   plan_id,
                   plan_name,
                   t_category,
                   emp_id,
                   dept_id,
                   year_of_account,
                   act_dt_commencent,
                   act_dt_completion,
                   dt_commencent,
                   dt_completion,
                   purpose,
                   distance,
                   remarks,
                   status
                   ) 
                   values ($userid,'".$tour_for_month."',$plan_id,'".$plan_name."',$tour_catagory,$emp_id,$dept_id,$years_of_acc,'".$act_dt_commencement."','".$act_dt_completion."','".$dt_commencement."','".$dt_completion."','".$purpose."',$distance,'".$remark."','draft')";
      //echo $sql;exit;
    // $insert=generateSQL($sql,array($userid,$tour_for_month,$plan_id,$plan_name,$tour_catagory,$emp_id,$dept_id,$years_of_acc,$act_dt_commencement,$act_dt_completion,$dt_commencement,$dt_completion,$purpose,$distance,$remark,'draft'),true,$mysqli);

                   $message = 'Success#Tour Created Successfully';
    }
    
                  // $new_ir_id=$insert->insert_id;
                  // echo $new_ir_id;
    //echo $message;exit;
   $res_insert = mysqli_query($mysqli,$sql);
    
    if($res_insert)
    {
     echo $msg = $message;
     exit;
    }
    else{
      echo("Error description: " . $mysqli -> error);
      exit;
    }
   
  }

  if(isset($_POST['action'])&& $_POST['action']=='send'){
      
      $user_id = $_POST['user_id'];

      $send_sql = "UPDATE cca_tour_details SET status = 'pending' where ID =".$user_id;

      $res_send = mysqli_query($mysqli,$send_sql);

      if($res_send)
      {
        echo "Success";
        exit;
      }
      else{
        echo("Error description: " . $mysqli -> error);
        exit;
      }
  }

  if(isset($_POST['action']) && $_POST['action']=='delete'  )
  {
    $user_id = $_POST['user_id'];
    
    $sql = "DELETE FROM cca_tour_details WHERE ID =".$user_id ;

      $res_delete = mysqli_query($mysqli,$sql);
      echo "success";
      exit();
  }
  
  //uploadFile

  
  if((!empty($_FILES["tourReport"])) && ($_FILES['tourReport']['error'] == 0))
  {
   
    $filename =strtolower(basename($_FILES['tourReport']['name']));
    $ext = substr($filename, strrpos($filename, '.') + 1);
    $final_remark =  $_POST['final_review']; 
    $userId   = $_POST['user_id'];
    if ($ext == "pdf" )
    {
                  $ext=".".$ext;
                  $cca_referenceno= gen_uuid();
                  $newFilename = $cca_referenceno . $ext;
                  $newname = 'audit_report/'.$newFilename;
                   
                            
                         if(move_uploaded_file($_FILES['tourReport']['tmp_name'],$newname))
                         {
                          
                            $user_id = $userid;
                            $document_id = $_POST['document_id'];
                           
                            $sql = "INSERT INTO cca_tour_document (document_id,user_id,final_remark,document_name) 
                                    values ('$document_id','$user_id', '".$final_remark."' ,'".$newname."') ";
                            $query_insert = mysqli_query($mysqli,$sql);

                            $distance_sql = "UPDATE cca_tour_details SET distance = '".$_POST['distance']."'WHERE ID = '".$userId."' ";
                            //echo $distance_sql;exit;
                            $distance_query =  mysqli_query($mysqli,$distance_sql);
                             //header("Location: tour_program_review");

                            if($distance_query)
                                          {
                                             echo "Success";
                                            // header("Location: tour_program_review");
                                             exit;
                                          }
                                          else{
                                            echo("Error description: " . $mysqli -> error);
                                            exit;
                                          }
                              exit();
                             echo "Success#".$newname; 
                             exit;
                         }
                  
                   else
                   {
                     echo "File sze exids";
                     exit;
                   }
    }
  }

  if(isset($_POST['action']) && $_POST['action']=='remove_report'  )
{
  $user_id = $_POST['user_id'];
  //echo "SELECT document_name FROM cca_tour_document WHERE document_id = '".$user_id."'";
  $filename_query = mysqli_query($mysqli,"SELECT document_name FROM cca_tour_document WHERE document_id = '".$user_id."'");
  $filename = mysqli_fetch_array($filename_query);
  $document_name =  $filename['document_name'];
  
    $file_path = '/cca_new/Auditor/'.$document_name;
  //$file_path =  realpath($document_name);
    $path = $_SERVER['DOCUMENT_ROOT'].$file_path;
    //echo $path;exit;
  if($path)
  {
    //echo $file_path;
    //unlink($file_path);
    unlink($path);
    //exit;
  }
  else
  {
    echo "File Not Found";
    exit;
  }
// echo $file_path; exit;
  $sql = "DELETE FROM cca_tour_document WHERE document_id =".$user_id ;
    
     $res_delete = mysqli_query($mysqli,$sql);
     echo "success";
    exit();
}

if(isset($_POST['action']) && $_POST['action']=='submit_report'  )
{
  $user_id = $_POST['user_id'];
  $distance = $_POST['distance'];
     $audit_sql = "UPDATE cca_tour_details SET audit_report = (SELECT id FROM `cca_tour_document` WHERE document_id = '".$user_id."') , distance = '".$distance."' WHERE ID = '".$user_id."' ";
     
   $audit_report = mysqli_query($mysqli,$audit_sql);
  if($audit_report)
                {
                   echo "Success";
                   header("Location: tour_program_review");
                   exit;
                }
                else{
                  echo("Error description: " . $mysqli -> error);
                  exit;
                }
    exit();
}

if(isset($_POST['action']) && $_POST['action']=='edit_report'  )
{
  $user_id = $_POST['user_id'];
  $report_sql = "SELECT final_remark , document_name FROM `cca_tour_document` WHERE document_id = '".$user_id."' ";
  $res = mysqli_query($mysqli,$report_sql);
  $row = mysqli_fetch_array($res);

  $data['remark'] = $row['final_remark'];
  $data['file_path'] = $row['document_name'];
   echo json_encode($data);
   exit;

}

if(isset($_POST['action']) && $_POST['action']=='cancel_report')
{
  $user_id = $_POST['user_id'];
  $comment = $_POST['cancel_comment'];
  $cancel_report_sql = "UPDATE cca_tour_details SET status = 'cancel', cancel_comment = '".$comment."' WHERE ID = '".$user_id."' ";
  
  $res = mysqli_query($mysqli,$cancel_report_sql);

  if($res)
  {
    echo "Success";
    exit;
  }
  else
  {
    echo "Something going Worng".$mysqli -> error;
    exit;
  }
}
   
   if(isset($_POST['action']) && $_POST['action']=='search')
   {
       $from_dt  = $_POST['from_dt'];
       $to_dt    = $_POST['to_dt'];
       $tour_id  = $_POST['tour_id'];
       $user_id  = $_POST['user_id'];
       
                            
                           
                           
                             $sql ="SELECT * FROM `cca_tour_details` WHERE emp_id= '".$tour_id."' ";
                              //echo $sql;exit;
                             $result_query = mysqli_query($mysqli, $sql); 
                             
                            
                             if(mysqli_num_rows($result_query)>0)
                             {
                               $count=0;
                               while($res = mysqli_fetch_assoc($result_query) )
                               {
                                  //print_r($res);
                                   $count++;
                                   $json['count'] =$count;
                                   $json['tour_for_the_month']=$res['tour_for_the_month'];
                                   $json['plan_name']=$res['plan_name'];

                                   $cat_sql = "select * from cca_tour_catagory where id = ".$res['t_category'];
                                   //echo $cat_sql;
                                       $cat_res = mysqli_query($mysqli,$cat_sql);
                                       $row = mysqli_fetch_assoc($cat_res);
                                       //print_r($row);
                                        if($row['ID'])
                                          {
                                            $json['catagory_name'] = $row['catagory_name'];

                                          };
                                    $sql      = "select * from cca_users where ID= ".$user_id;
                                          $sql_res  = mysqli_query($mysqli,$sql);
                                          $user_row = mysqli_fetch_array($sql_res);
                                          if( count($user_row) > 0 )
                                          {
                                            $json['Name'] = $user_row['Name'];
                                          }

                                          $json['dt_commencent'] = $res['dt_commencent'];
                                          $json['dt_completion'] = $res['dt_completion'];
                                          $json['purpose'] = $res['purpose'];
                                          $json['distance'] = $res['distance'];
                                          $json['remarks'] = $res['remarks'];
                                          $json['status'] = $res['status'];
                                          
                                          if($res['status'] == 'accept')
                                          {
                                           
                                             if($res['audit_report'])
                                                  {
                                                     $sql_report = "SELECT doc.document_name,doc.final_remark from cca_tour_details td , cca_tour_document doc WHERE td.audit_report = doc.id  AND  td.ID = '".$res['ID']."'  ";
                                                     //echo $sql_report;
                                                     $sql_report_res = mysqli_query($mysqli,$sql_report);
                                                     $sql_report_row = mysqli_fetch_array($sql_report_res);
                                                     if(count($sql_report_row)>0)
                                                     {

                                                      $json['report']=$sql_report_row['document_name'];
                                                       
                                                     }
                                                  }
                                          }
                                          if($res['status'] == 'reject') 
                                          {
                                            $sql_reject = "SELECT reject_msg FROM `cca_tour_reject` WHERE `tour_id` = '".$res['ID']."'";
                                                 $sql_reject_res = mysqli_query($mysqli,$sql_reject);
                                                 $sql_reject_row = mysqli_fetch_array($sql_reject_res);
                                                 if(count($sql_reject_row)>0)
                                                 {
                                                  $json['report'] =  $sql_reject_row['reject_msg'];
                                                 }
                                                
                                          }
                                          if($res['status'] == 'cancel') 
                                          {
                                            $json['report'] =  $res['cancel_comment'];
                                          }
                                    
                                   $result[]=$json;
                                   
                                             
                                   
                               }

                                     
                             }
                            $row_data['data'] = $result;
                          echo json_encode($row_data);
                                     exit;

   }

   if(isset($_POST['action']) && $_POST['action']=='report_search')
   {

      $reviewer_id = $_POST['reviewer_id'];
      $tour_month    = $_POST['tour_month'];

      $d     = date_parse_from_format("Y-m-d", $tour_month);
      $month =  $d["month"];
      $year  =  $d["year"];

       // $time=strtotime($tour_month);
       // $month=date("F",$time);
       // $year=date("Y",$time);
       // echo $year;

       //$sql = "SELECT * FROM `cca_tour_details` WHERE user_id = '".$reviewer_id."' AND  month(tour_for_the_month) = '".$month."' AND year(tour_for_the_month)='".$year."'";
      $sql = "SELECT d.tour_for_the_month,d.year_of_account,d.plan_name,c.catagory_name,d.year_of_account,d.dt_commencent,d.dt_completion,d.purpose,d.distance,d.remarks FROM `cca_tour_details` d ,`cca_tour_catagory` c  WHERE d.t_category = c.ID AND d.user_id = '".$reviewer_id."' AND  month(d.tour_for_the_month) = '".$month."' AND year(d.tour_for_the_month)='".$year."'";
       //echo $sql;

       $result_query = mysqli_query($mysqli, $sql); 

       if(mysqli_num_rows($result_query)>0)
       {
           $count=0;
           while($res = mysqli_fetch_assoc($result_query) )
           {
                $count++;
                $time=strtotime($res['tour_for_the_month']);
                $month=date("F",$time);
                $year=date("Y",$time);
                
               $json['count'] = $count;
               $json['month'] = $month;
               $json['year']  = $year;
               $json['tour_for_the_month'] =$res['tour_for_the_month'];
               $json['year_of_account'] =$res['year_of_account'];
               $json['catagory_name'] =$res['catagory_name'];
               $json['plan_name'] =$res['plan_name'];
               $json['dt_commencent'] =$res['dt_commencent'];
               $json['dt_completion'] =$res['dt_completion'];
               $json['purpose'] =$res['purpose'];
               $json['distance'] =$res['distance'];
               $json['remarks'] =$res['remarks'];
               
               $result[]=$json;
           }

           

           $row_data['data'] = $result;
           echo json_encode($row_data);
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
 