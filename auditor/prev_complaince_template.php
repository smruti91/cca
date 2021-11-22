                  
                  <div class="after-add-more subdiv" style="display:  <?php echo $row_cnt > 0 ? 'none' : ''  ?>">
                    <div class="row">
                      <div class="col-md-6 lbl">
                        <div class="col-md-4">
                          <label for="">Audit Type</label>
                        </div>
                        <div class="col-md-8">
                          <div class="form-group">
                            <select  class="form-control" name="audit_type" required>
                              <option value="">  Audit Type</option>
                              <option>IR</option>
                              <option>IAR</option>
                              <option>EAR</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6 lbl">
                        <div class="col-md-7">
                          <label for="">Audit Report No.</label>
                        </div>
                        <div class="col-md-5">
                          <input type="text" class="form-control" name="audit_no"  id="audit_no" placeholder="Audit Report No." required>
                        </div>
                      </div>
                     
                    </div>

                    <div class="row">
                       <div class="col-md-6 lbl">
                         <div class="col-md-4">
                           <label for="">Year</label>
                         </div>
                         <div class="col-md-8">
                           <input type="text" class="form-control" name="year"  id="year" placeholder="Year of accounts" required>
                         </div>
                       </div>
                       <div class="col-md-6 lbl">
                         <div class="col-md-7">
                           <label for="">No. of objection paras </label>
                         </div>
                         <div class="col-md-5">
                           <input type="text" class="form-control" name="no_obs_para"  id="no_obs_para" placeholder="No. of Paras"  required>
                         </div>
                       </div>
                    </div>

                    <div class="row" style="margin: 5px;">
                      <div class="col-md-6 lbl">
                        <label for="">Audit Observation </label>
                        <div class="form-group">
                          <textarea  class="form-control audit_obs" name="audit_obs" id="audit_obs"></textarea>
                        </div>
                      </div>
                      <div class="col-md-6 lbl">
                        <label for="" style="margin-left: 15px;">Complaince </label>
                        <div class="form-group ">
                          <textarea  class="form-control complaince " name="complaince" id="complaince"></textarea>
                        </div>
                      </div>
                      <input type="hidden" name="action" value="save_complaince" >
                    </div>
                   
                  </div>

               