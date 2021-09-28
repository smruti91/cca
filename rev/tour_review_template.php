  
                              
                                   <?php
                                      //echo $res['ID'];
                                        $sql = "SELECT doc.document_name,doc.id as doc_id,td.audit_report FROM cca_tour_details td , cca_tour_document doc WHERE td.ID = doc.document_id AND td.ID = ".$res['ID'];
                                        $query = mysqli_query($mysqli,$sql);
                                        $doc_row = mysqli_fetch_array($query);
                                        //print_r($doc_row);
                                       // echo BASE_URL;
                                        if( empty($doc_row['document_name']) )
                                        {
                                          ?>
                                            <a href="#myModal_<?php echo $res['ID']; ?>" type="button" class="btn btn-primary" data-toggle="modal" id="report" style="color: #fff" >Report <i class="material-icons" data-toggle="tooltip" title="file_upload" >file_upload</i></a>

                                            <a href="#cancelModal_<?php echo $res['ID'];?>" type="button" class="btn btn-danger" data-toggle="modal" style="color:#fff; margin-top: 5px;" >Cancel</a>
                                            
                                            <!-- <a class="waves-effect waves-light btn-small gray" style="color: #fff" >cancel</a> -->
                                          <?php
                                        }
                                        else
                                        {
                                            $doc_path = $doc_row['document_name'];
                                            $file_path = BASE_URL."Auditor/".$doc_path;
                                            $file_img_view  = BASE_URL."images/document_pdf.png";
                                            $file_img_delete = BASE_URL."images/cross.png";
                                            $audit_report = $doc_row['audit_report'];
                                            //echo $audit_report;
                                            if($audit_report==0)
                                            {
                                            ?>
                                             <!--  <a href="<?php echo $file_path?>"><i class="material-icons" data-toggle="tooltip" title="Edit" >&#xE254;</i></a> -->

                                              <a href="#" id="<?php echo $res['ID'] ?>" class="edit_report" ><i class="material-icons" data-toggle="tooltip" title="Edit" style="">&#xE254;</i></a>

                                              <a href="#" class="send"  id="<?php echo $res['ID']; ?>" onclick="submit_report(this.id);" ><i class="material-icons" data-toggle="tooltip" title="Save" style="color: #24a0e3">send</i></a>

                                            <?php
                                          }
                                          else{
                                            ?>
                                              <a href="<?php echo $file_path?>" target="_blank"><i class="material-icons" data-toggle="tooltip" title="picture_as_pdf" style="font-size: 3rem; color:#f25149;" >picture_as_pdf</i></a>
                                            <?php
                                          }
                                        }

                                     
                                      ?>

                                      <!--- Cancel Modal ---->

                                           <div class="modal custom fade" id="cancelModal_<?php echo $res['ID']; ?>">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                           
                                           <div class="modal-header">
                                               <div><b>Reasion For Cancel Tour</b></div>
                                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                 </button>
                                           </div>
                                          <!-- Modal body -->
                                          <div class="modal-body">
                                          
                                          
                                         <form  class="cancelForm" method="post"  enctype="multipart/form-data">

                                          <div class="form-group">
                                              <label> Comments :   </label>
                                              <textarea  class="form-control cancel_comment" name="cancel_comment" rows="3"></textarea>
                                          </div>

                                          <div class="form-group">
                                           <input type="submit" name="submit"  value="Send" class="btn btn-info uploadBtn" />
                                          </div>
                                          
                                      <input type="hidden" name="user_id"  value="<?php echo $res['ID']; ?>">
                                      <input type="hidden" name="action" value="cancel_report" >
                                          
                                         </form>
                                         <!-- <div id="loader-icon" style="display:none;"><img src="loader.gif" /></div> -->
                                        
                                       </div>
                                       <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          
                                       </div>
                                      </div>
                                         


                                        </div>
                                      </div>

                                      <!--- Cancel Modal End ---->
                                    

                             <!-- Upload Modal HTML -->
                                  <div class="modal custom fade" id="myModal_<?php echo $res['ID']; ?>">
                             <div class="modal-dialog">
                               <div class="modal-content">
                                  
                                  <div class="modal-header">
                                      <div><b>Upload Audit Report</b></div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                        </button>
                                  </div>
                                 <!-- Modal body -->
                                 <div class="modal-body">
                                 
                                 
                                <form  class="reportForm" method="post"  enctype="multipart/form-data">
                                  <div class="form-group">
                                  <label> Final Review  </label>
                                   <textarea  class="form-control final_review" name="final_review" rows="3"></textarea>
                                 </div>
                                  <div class="form-group">
                                  <label> Final Distance  </label>
                                  <input type="number" name="distance" class="form-control" id="distnc" value="<?php echo $res['distance']; ?>" placeholder="Final Distance" >
                                 </div>
                                 <div class="form-group upload_file">
                                  <label>File Upload</label>
                                  <input type="file" name="tourReport" class="form-control-file report_file"  accept=".jpg, .png , .pdf" />
                                 </div>
                                 <input type="hidden" name="user_id"  value="<?php echo $res['ID']; ?>">
                                 <div>
                                   <a href="" style="display: none;" ></a>
                                 </div>
                                 <div class="progress" style="display: none;">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                 </div>
                                 <div class="form-group uploaded_file ">
                                    <div class="uploaded_report" style="display: none;" >
                                       <label>Uploaded Report :</label>
                                        <a href="" class="uploaded_report" target="_blank"><i class="material-icons" data-toggle="tooltip" title="picture_as_pdf" style="color:#f25149; " >picture_as_pdf</i> Tour Report</a>
                                       
                                          <a href="" class="remove_report" id="<?php echo $res['ID']; ?>" style="padding-left: 5px" ><i class="material-icons" data-toggle="tooltip" title="picture_as_pdf" style="color:#f25149; " >clear</i></a>

                                    </div>
                                    
                                 </div>
                                 
                                 <div class="uploadStatus"> </div>

                                 <div class="form-group">
                                  <input type="submit" name="submit"  value="Upload" class="btn btn-info uploadBtn" />
                                 </div>
                                  <div class="actionBtn">
                                    <a class="btn btn-warning view_report" role="button" target="_blank" style="color: #fff" >Preview Pdf</a>
                                    <button class="btn btn-primary" id="<?php echo $res['ID']; ?>" onclick="submit_report(this.id)" >Submit</button>
                                   
                                  </div>
                                 <input type="hidden" name="document_id"  value="<?php echo $res['ID']; ?>">
                                 <div id="targetLayer" style="display:none;"></div>
                                </form>
                                <!-- <div id="loader-icon" style="display:none;"><img src="loader.gif" /></div> -->
                               
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                 <button type="button" class="btn btn-primary" id="<?php echo $res['ID']; ?>" 
                                  onclick="save_report(this.id)" >Save Report</button>
                              </div>
                             </div>
                                


                               </div>
                             </div>
                              
  
                       
                      
                  