                  
                  <div class="after-add-more subdiv" id="subdiv">
                    <div class="row">
                      <div class="col-md-12 lbl">
                        
                        <div class="col-md-5">
                          <div class="form-group">
                            <select  class="form-control assessment"  id="asmnt_" name="assessment[]" required>
                              <option value="0">  Select Assessment Aspect</option>
                              <option value="1">Cash Managemet</option>
                              <option value="2">Bank Reconciliations</option>
                              <option value="3">Funds/Grants Management</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-2">
                        
                        </div>
                        <div class="col-md-2">
                           <!--  <input type="button" class="btn btn-info" id="" name="view" value="View" onclick="show_div(this.id)"> -->
                           
                        </div>
                      </div>

                    </div>

                   <div class ="field-control" id="test_" style="display:none">
                    <div class="row" style="margin: 5px;">
                      <div class="col-md-6 lbl">
                        <label for="">Indication of Strong Controls  </label>
                        <div class="form-group">
                           <textarea  class="form-control strong" name="strong[]" id="strong_"></textarea>
                        </div>
                      </div>
                      <div class="col-md-6 lbl">
                        <label for="" style="margin-left: 15px;">Indication of Weak Controls </label>
                        <div class="form-group ">
                          <textarea  class="form-control weak" name="weak[]" id="weak"></textarea>
                        </div>
                      </div>
                      
                    </div>

                    <div class="row" >
                      <div class="col-md-6 lbl" style="margin-top: 35px;">
                        <div class="col-md-4">
                           <label for="">Assessment Result </label>
                        </div>
                        
                        <div class=" col-md-8 form-group ">
                             <label class="checkbox-inline ">
                               <input type="checkbox"  name = "checklist[]" value="1">Strong
                             </label>
                             <label class="checkbox-inline">
                               <input type="checkbox"  name = "checklist[]"  value="2">Moderate
                             </label>
                             <label class="checkbox-inline">
                               <input type="checkbox"   name = "checklist[]" value="3">Weak
                             </label>
                        </div>
                      </div>

                      <div class="col-md-6 lbl">
                        <label for="">Recommendation for improvement  </label>
                        <div class="form-group" >
                           <textarea  class="form-control improvement1 txt" name="improvement[]" id="improvement" style="width: 490px;height: 120px;"></textarea>
                        </div>
                      </div>
                      
                    </div>
                      <input type="hidden" name="save_assesment" value="save" >
                     </div>
                   
                  </div>

               