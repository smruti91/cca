                             
            <?php
               $record_sql = "SELECT * FROM `cca_recordslist`";
            
               $record_res = mysqli_query($mysqli, $record_sql);

               while( $row = mysqli_fetch_assoc($record_res) ) {

                ?>

                               <tr >
                                    <td>&nbsp;</td>
                                    <td>  <?php echo $row['record_name'] ?> </td>
                                    <td>  <?php echo $row['rules'] ?>  </td>
                                    <td>  <?php echo $row['form_no'] ?> </td>
                                    <td> <input type="checkbox" class="check[<?php echo $row['form_no'] ?>]"  name = "checklist[]" id="verified" value="0"> </td>
                                    <td> <input type="checkbox" class="check[<?php echo $row['form_no'] ?>]" name = "checklist[]" id="notVerified" value="1"  > </td>
                                    <td> <input type="checkbox" class="check[<?php echo $row['form_no'] ?>]" name = "checklist[]" id = "notMaintained" value="2" ></td>
                                    <input type="hidden" name="record_list_id[]" value="<?php echo $row['id'] ?>" >
                                   
                                     
                                  </tr>

                <?php
               }


             ?>                   
                               