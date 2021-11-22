<?php
  $sql_para  = " SELECT b.record_name, b.rules,b.form_no,a.status FROM ".$table." a ,`cca_recordslist` b  WHERE a.recordList_id = b.id AND para_id = '".$para_id."' AND mngplan_id = '".$_SESSION['mngplan_id']."' AND add_by =  '".$_SESSION['userid']."' AND version = 0 "; 
  //echo  $sql_para;
  $sql_para_res   = mysqli_query($mysqli,$sql_para);


 ?>
                  <h3>Part D.    Maintenance of Records</h3>
                  <p style="font-weight: 500;">(i)  List of Records verified. ( Annexure)</p>
                        <table class="table table-striped table-bordered" id="report_tbl">
                            <thead>
                                <tr>
                                    <th style="width:20px">Sl.No</th>
                                    <th style="width:150px" >Name of the Record </th>
                                    <th style="width:150px">Rules   </th>
                                    <th style="width:150px">Form No</th>
                                    <th style="width:165px">Verified </th>
                                    <th style="width:150px">Not Verified</th>
                                    <th style="width:150px">Not maintained</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                   $count =0;
                                    while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                                 
                                          //print_r( $para_row);
                                          $count++;
                    
                                 ?>
                                  <tr>
                                      <td> <?php echo $count; ?></td>
                                      <td> <?php echo $para_row['record_name']; ?></td>
                                      <td> <?php echo $para_row['rules'] ?></td>
                                      <td> <?php echo $para_row['form_no']; ?></td>
                                      <td> <?php echo $para_row['status'] == 1 ?  'YES' : ''; ?></td>
                                      <td> <?php echo $para_row['status'] == 2 ?  'YES' : ''; ?></td>
                                      <td> <?php echo $para_row['status'] == 0 ?  'YES' : ''; ?></td>
                                     
                                  </tr>

                                 <?php
                                   }
                                  ?>
                                
                            </tbody>
                         </table>



<?php
  $sql_para  = " SELECT * FROM `cca_para_3d1` WHERE para_id = '".$para_id."' AND mngplan_id = '".$_SESSION['mngplan_id']."' AND add_by =  '".$_SESSION['userid']."' AND version = 0 "; 
  //echo  $sql_para;
  $sql_para_res   = mysqli_query($mysqli,$sql_para);
  $para_row  = mysqli_fetch_assoc($sql_para_res);

  $records = $para_row['consequences_records'];
  //$res     = preg_replace('/<p\b[^>]*>(.*?)<\/p>/i', ' ', $records);
  $text=str_ireplace('<p>','',$records);
  $text=str_ireplace('</p>','',$text);  

 ?>
                  
                  <p style="font-weight: 500;">(ii)  Consequences of non-maintenance of records.</p>

                        <textarea 
                           style="width: 60%;height: 35vh;margin: 20px;border: 1px solid black;"
                         >
                         <?php echo $text ; ?>
                             
                         </textarea>