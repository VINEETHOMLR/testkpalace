
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

<?php  //echo "<pre>";print_r($data['data']); die;?>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                        <form id="btc_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Customer/UpdateAlcoholList/">
                            <div class="row col-md-12 col-xs-12">
                                <div class="form-group col-sm-2">
                                    <select type="option" name="customer_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$customer_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="text" name="alcohol" id="alcohol_name" class="form-control" placeholder="Alcohol Name">
                                </div>
                                <div class="form-group col-sm-1">
                                    <select type="option" name="volume" class="form-control custom-select" id="volume">
                                        <option value="">Select</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="75">75</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="text" id="datefrom" class="form-control flatpickr-input active" placeholder="Expiry Date From" name="datefrom"  readonly="readonly">
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="text" id="dateto" class="form-control flatpickr-input active" placeholder="Expiry Date To" name="dateto"  readonly="readonly">
                                </div>
                        </form>
                        <div class="orm-group col-sm-2 ">
                            <input type="submit" class="btn btn-success  div_float" id="search" name="Search" value="Search">
                        </div>    
                    </div>                  
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>
                        <!-- <a href="<?=BASEURL;?>Customer/AddCustomerAlcohol/" class="full_width div_float">
                        <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add New</button>
                        </a> -->       
                    </div>
                </div>

                  
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Customer ID</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Alcohol</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Volume(%)</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Expiry Date</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Balance</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Create Date</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                 
                                  </tr>
                                </thead>
                                <tbody>
                                <?php 
                                  if(!empty($data['data'])){
                                   foreach($data['data'] as $key => $val):?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['customer_id']?></td>
                                      <td><?=$val['name']?></td>
                                      <td><?=htmlspecialchars_decode($val['item']);?></td>
                                      <td><?=$val['volume']?></td>
                                      <td><?=$val['exp_date']?></td>
                                      <td><?=$val['balance']?></td>
                                      <td><?=$val['create_time']?></td>
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
                            <form class="col-md-12" id="deposit_pagination" method="post" action="<?=BASEURL;?>Customer/UpdateAlcoholList/">
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
<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>

<script>

  var userId    = '<?=$user_id?>';
  var volume    = '<?=$volume?>';
  var alcohol   = '<?=$alcohol?>';
  var datefrom   = '<?=$datefrom?>';
  var dateto   = '<?=$dateto?>';

  $("#volume").val(volume);
  $("#alcohol_name").val(alcohol);
  $("#datefrom").val(datefrom);
  $("#dateto").val(dateto);

 
$(function () { 

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#custMenu').attr("data-active","true");
  $('#custNav').addClass('show');
  $('#alcohol').addClass('active');

  $('#search').click(function(){
      $('#btc_form').submit();
  })
});

function pageHistory(userId,volume,alcohol,datefrom,dateto,page){

    $('.pagination').append('<input name="user_id" value="'+userId+'" style="display:none;">');
    $('.pagination').append('<input name="volume" value="'+volume+'" style="display:none;">');
    $('.pagination').append('<input name="alcohol" value="'+alcohol+'" style="display:none;">');
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
   
    $('#deposit_pagination').submit();
}


 function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Data !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Customer/UpdateAlcoholDelete/',{getId:val},function(response){ 
    
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

    var userId         = "<?=$user_id;?>";
    var volume         = "<?=$volume;?>";
    var alcohol        = "<?=$alcohol;?>";
    var datefrom       = "<?=$datefrom;?>";
    var dateto         = "<?=$dateto;?>";

    var pge=$("#pageJump").val(); 
    pageHistory(userId,volume,alcohol,datefrom,dateto,pge)
 });

 $('#pageJump').val("<?=$data['curPage'];?>");

  function exportReport(){
        

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>Customer/ExportUpdateAlcohol',{'user_id':userId,'alcohol':alcohol,'volume':volume,'datefrom':datefrom,'dateto':dateto},
            function(response){
                hideoverlay();
        newResp = JSON.parse(response);  
        if(newResp['status'] == 'success')
        {        
            $('#downfile').html(newResp['response']);
            $('#downloadcsv').click();

        }else{
            loadingoverlay("error","Error","Try Again>");
        }

    });
    return false;
        
} 



</script>
