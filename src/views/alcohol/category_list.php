
<style type="text/css">

  .select2-container--default .select2-selection--multiple{
     padding: 3px 10px !important;
   }
  
  .div_float{
     float: right;
     margin-right: 10px;
  }

@media(max-width:767px){
    .div_float {
        
        padding: 5px;
        margin-right: 0px;
    }
    .full_width{
     width: 100%;
   }
} 
.form-control{
  height: 40px;
}
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                       <form id="btc_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Alcohol/ListCategory/">
                        <div class="row col-md-12 col-xs-12">
                           
                                 
                                  
                                 <!-- <div class="form-group col-sm-2">
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Customer</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                  </div>-->
                                  <div class="form-group col-sm-3">
                                    <input type="text" name="name" class="form-control select" id="name" placeholder="Category Name">
                                  </div>
                                   
                                  </form>
                               <div class="form-group col-sm-4">
                                  <input type="submit" class="btn btn-success" id="search" name="Search" value="Search">
                              </div>    
                            
                        </div>
                                      
                    </div>

                  

                    <div class="row">
                       <div class="col-md-12 col-lg-12">
                              
                  <a href="<?=BASEURL;?>Alcohol/Update/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add Category</button>
                </a>
                                    
             
                    </div>
                  </div>
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Name-Chinese</th>
                                  <!--<th class="sorting_disabled" rowspan="1" colspan="1">Status</th>-->
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                            
                                 
                                  </tr>
                                </thead>
                                <tbody>
                                <?php 
                                  if(!empty($data['data'])){
                                   foreach($data['data'] as $key => $val):?>
                                    <tr role="row" class="odd">
                                      <td><?=htmlspecialchars_decode($val['name'])?></td>
                                      <td><?=htmlspecialchars_decode($val['name_ch'])?></td>
                                     <!-- <td><?=$val['status']?></td>-->
                                      <td><?=$val['action']?></td>
                                   

                                    </tr>
                                <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="9" class="text-center">No Data Found</td></tr>';
                                  }   
                                ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="deposit_pagination" method="post" action="<?=BASEURL;?>Alcohol/ListCategory/">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination;?>
                                    </ul>
                                </div>
                                <input name="sub" id="subs" value="" style="display: none;">
                            </form>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
    </div>
</div>

<script>

  var name    = '<?=$name?>';
  var status    = '<?=$status?>';
  $('#name').val(name);
   

 
$(function () { 

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#AlcoholMenu').attr("data-active","true");
  $('#AlcoholNav').addClass('show');
  $('#cat').addClass('active');

  $('#search').click(function(){
      $('#btc_form').submit();
  })
});

function pageHistory(name,status,page){
    $('.pagination').append('<input name="name" value="'+name+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
   
   
    $('#deposit_pagination').submit();
}

 function switchStatus(id,status){
      var changedToStatus = (status == 1) ? 0 : 1 ;
      $.post('<?=BASEURL;?>Alcohol/UpdateAlcoholCategoryStatus/',{'id':id,'status':changedToStatus},function(response){
            if(response){
              openSuccess('Status Updated Successfully');
            }
      });
  }

  function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Category !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Alcohol/Delete/',{getId:val},function(response){ 
    
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        openSuccess(newResp['response'])
                    }else{ 
                        loadingoverlay('error','Error',newResp['response']);
                    }
                });
                return false;
            }
        })
    }
 $("#pageJumpBtn").click(function(){
    var pge=$("#pageJump").val();
    pageHistory(name,status,pge)
 });

 $('#pageJump').val("<?=$data['curPage'];?>");

</script>
