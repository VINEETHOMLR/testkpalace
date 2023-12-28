<?php 
use inc\Root;
$this->mainTitle = 'Purchase';
$this->subTitle  = 'Customer Alcohol';
?>
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
                        <div class="row col-md-12 col-xs-12">
                            <form id="btc_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Purchase/CustomerAlcohol/"> 
                                <div class="form-group col-sm-3">
                                    <select type="option" name="user_id" class="form-control custom-select" id="customer_id">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <input type="text" class="form-control" max="" id="order_id" name="order_id" value="" placeholder="Enter Order ID ">
                                </div>
                                <div class="form-group col-sm-3">
                                    <select type="option" name="volume" class="form-control custom-select" id="volume">
                                        <option value="">Select Alcohol %</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="75">75</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div class="orm-group col-sm-2 ">
                                    <input type="submit" class="btn btn-success  div_float" id="search" name="Search" value="Search">
                                </div>
                            </form>
                            
                        </div>
                                      
                    </div>
                      
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                    <tr>
                                        <th class="sorting_disabled" rowspan="1" colspan="1">User ID</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1">Order ID</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1">Expiry Date</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1">Alcohol</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1">Volume(%)</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1">Date</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                  if(!empty($data['data'])){
                                   foreach($data['data'] as $key => $val):?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['uniqueid']?></td>
                                      <td><?=$val['customer_name']?></td>
                                      <td><?=$val['order_id']?></td>
                                      <td><?=$val['expiry_date'];?></td>  
                                      <td><?=htmlspecialchars_decode($val['item_name']);?></td>
                                      <td><?=$val['volume']?></td>
                                      <td><?=$val['time']?></td>
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
                            <form class="col-md-12" id="deposit_pagination" method="post" action="<?=BASEURL;?>Purchase/CustomerAlcohol/">
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
var order_id    = '<?=$order_id?>';
var expiry_date = '<?=$expiry_date?>';

$("#volume").val(volume);
$('#order_id').val(order_id);
$('#expiry_date').val(expiry_date);


 
$(function () { 

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#PurchaseMenu').attr("data-active","true");
  $('#PurchaseNav').addClass('show');
  $('#customer_alcohol').addClass('active');

  $('#search').click(function(){
      $('#btc_form').submit();
  })
});

function pageHistory(page){

    $('.pagination').append('<input name="user_id" value="'+userId+'" style="display:none;">');
    $('.pagination').append('<input name="volume" value="'+volume+'" style="display:none;">');
    $('.pagination').append('<input name="order_id" value="'+order_id+'" style="display:none;">');
    $('.pagination').append('<input name="expiry_date" value="'+expiry_date+'" style="display:none;">');
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
               $.post('<?= BASEURL ?>Customer/CustDelete/',{getId:val},function(response){ 
    
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
    var order_id       = '<?=$order_id?>';
    var expiry_date    = '<?=$expiry_date?>';
    

    var pge=$("#pageJump").val(); 
    pageHistory(pge)
 });

 $('#pageJump').val("<?=$data['curPage'];?>");

  function exportReport(){
        

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>Customer/ExportCustomerAlcohol',{'user_id':userId,'volume':volume},
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

$('#customer_id').select2({
    placeholder: 'Customer Username/ID',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>Customer/getCustomers',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        //var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});


</script>
