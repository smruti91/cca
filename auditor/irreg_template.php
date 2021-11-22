                  
                  <div class="after-add-more subdiv" style="display:  <?php echo $row_cnt > 0 ? 'none' : ''  ?>">
                    <div class="col-md-3">
                      <label class="control-label">Irregularities noticed </label>
                    </div>
                     
                    <div class="row" style="margin: 5px;">

                      <div class="col-md-12">
                        
                        <div class="form-group  ">
                          <textarea  class="form-control irreg_notice" name="irreg_notice" id="irreg_notice"></textarea>
                        </div>
                      </div>
                      
                       <input type="hidden" name="action" value="save_irreg" >
                    </div>
                   
                  </div>

               