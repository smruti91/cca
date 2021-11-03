<?php
   if(isset($_POST['mangPlan_id'])){

      $mngplanid = $_POST['mangPlan_id'];
      $para_id   = $_POST['para_id'];
   }else{
      $mngplanid = $_SESSION['mngplan_id'];
      $para_id   = $_SESSION['paraid'];
   }
     
      $sql = "SELECT d.id, r.record_name,r.rules,r.form_no,d.status FROM `cca_para_3d` d , cca_recordslist r WHERE d.recordList_id =r.id AND d.para_id = '".$para_id."' AND d.mngplan_id = '".$mngplanid."' AND d.version = 0 ";
      //echo $sql
      $res = mysqli_query($mysqli,$sql);

      while($row = mysqli_fetch_assoc($res)){
      	//print_r($row);

      	?>

      	                        <tr >
                                    <td>&nbsp;</td>
                                    <td>  <?php echo $row['record_name'] ?> </td>
                                    <td>  <?php echo $row['rules'] ?>  </td>
                                    <td>  <?php echo $row['form_no'] ?> </td>
                                    <td> <input type="checkbox" class="check[<?php echo $row['form_no'] ?>]"  name = "checklist[]" id="verified" value="1" <?php if($row['status']==1){ echo " checked=\"checked\"";  } ?> > </td>
                                    <td> <input type="checkbox" class="check[<?php echo $row['form_no'] ?>]" name = "checklist[]" id="notVerified" value="2"  <?php if($row['status']==2){ echo " checked=\"checked\"";  } ?> > </td>
                                    <td> <input type="checkbox" class="check[<?php echo $row['form_no'] ?>]" name = "checklist[]" id = "notMaintained" value="0"  <?php if($row['status']==0){ echo " checked=\"checked\"";  } ?> ></td>
                                    <input type="hidden" name="record_list_id[]" value="<?php echo $row['id'] ?>" >
                                   
                                     
                                  </tr>
      	<?php
      }

 ?>