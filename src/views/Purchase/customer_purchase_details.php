<?php 
use inc\Root;
$this->mainTitle = 'Purchase';
$this->subTitle  = 'Customer Purchase Details';
?>
<style type="text/css">
  
  .dataTables_filter{
    display: none;
  }
  .m-t-40{
    margin-top: 0px !important;
  }
  .dataTable{ text-align: center; }

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
       height: 42px;
}
.select{
  padding-top: 9px;
}
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <div class="form-group col-sm-12">
                                <h5 class="row col-md-12"><b>Purchase details of : </b><?=$username?></h5>
                            </div>
                            <form id="purchaseform" class="row col-md-12" method="post" action="<?=BASEURL;?>Purchase/getOrderDetails/?id=<?=$order_id?>">
                                
                                <div class="form-group col-sm-2">
                                    <select type="option" name="inventory_id" class="form-control" id="inventory_id">
                                        <option value="">Select Item Name</option>
                                        <?php foreach($order_items as $key => $val){?>
                                          <option value="<?=$val['inventory_id']?>"><?=$val['item_name']?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="submit" class="btn btn-success div_float" id="search" name="Search" value="Search">
                                </div>
                            </form>
                        </div>

                       
                      
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                        <thead>
                                            <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >Sl No</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Unit</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Unit Price</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Total</th>
                                            <!-- <th class="sorting_disabled" rowspan="1" colspan="1">Action</th></tr> -->
                                        </thead>
                                        <tbody>
                                        <?php
                                        //print_r($data);
                                        if(!empty($data)){
                                            $i = 1;
                                            foreach($data as $key => $val){ ?>
                                                <tr role="row" class="odd">
                                                <td><?=$i?></td>                                          
                                                <td><?=$val['item_name']?></td>                                          
                                                <td><?=$val['unit']?></td>                                          
                                                <td><?=$val['unit_price']?></td>                                          
                                                <td><?=$val['unit_total_amount']?></td>                                          
                                                
                                                
                                            <?php $i++; }
                                        }else{
                                            echo '<tr><td colspan="6" class="text-center">No Data Found</td></tr>';
                                        }   
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="purchase_pagination" method="post" action="<?=BASEURL;?>Purchase/List">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination;?>
                                    </ul>
                                </div>
                            </form>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>

</div>

<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Order Details</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="closemodal()" style="margin-top:3px"  aria-label="Close"> x </button>

            </div>
            <div class="modal-body" >
              <div class="row">
                  <div class="col-md-6">
                  <h5 class="modal-title" style="line-height: 1;"><b>Order ID</b>&nbsp;&nbsp;:</h5></div>
                  <div class="col-md-6"><h6 class="modal-title" style="line-height: 1;" id="orderid"></h6></div>
                </div>
              <div class="row">
                  <div class="col-md-6">
                  <h5 class="modal-title" style="line-height: 2.5;"><b>Purchase Date</b>&nbsp;&nbsp;:</h5></div>                
                  <div class="col-md-6"><h6 class="modal-title" style="line-height: 2.5;" id="date"></h6></div>
              </div>
              <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >No</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Unit</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Unit Price</th>
                                </thead>
                                <tbody id="det">

                                 </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>


              <div class="row" style="margin-top: -50px;">
                  <div class="col-md-6">
                  <h5 class="modal-title" style="line-height: 2.5;"><b>Total Amount</b>&nbsp;&nbsp;:</h5></div>                
                  <div class="col-md-6"><h6 class="modal-title" style="line-height: 2.5;" id="total"></h6></div>
              </div>
             </div>
          
        </div>
    </div>
</div>

<script>

var datefrom    = '<?=$datefrom?>';
var dateto      = '<?=$dateto?>';
var userId      = '<?=$user_id?>';
var username    = '<?=$username?>';
 
$(function () { 

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#PurchaseMenu').attr("data-active","true");
    $('#PurchaseNav').addClass('show');
    $('#customer_purchase').addClass('active');

    // $('#userID').val(userID);
    $('#datefrom').val(datefrom);
    $('#dateto').val(dateto);
    $('#username').val(username);
        
    $('[data-toggle="tooltip"]').tooltip(); 
    
    $('#search').click(function(){
        $('#purchaseform').submit();
    })
});

function pageHistory(page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+userId+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    
    $('#purchase_pagination').submit();
}



 $("#pageJumpBtn").click(function(){
    pageHistory($("#pageJump").val());
 });

 function viewOrder(Id){  

    $.ajax({
        url: "<?=BASEURL?>Purchase/getOrderDetails",
        type: "post",
        data: {'orderid':Id} ,
        success: function (response) {
          $('#det').html('');
          for (var i=0; i<response.length; i++) {
           $('#orderid').html(Id);
           $('#date').html(response[i].time);
           
           $('#det').append('<tr role="row" class="odd"><td>'+(i+1)+'</td><td>'+response[i].item_name+'</td><td>'+response[i].unit+'</td><td>'+response[i].unit_price+'</td></tr>');
           $('#total').html(response[i].total_amount);
          }
         
           $('#orderModal').modal('show');
        } 
    });
}

 $('#pageJump').val("<?=$data['curPage'];?>");

 function closemodal(){
  $('#det').html('');
     $('#orderModal').toggle();
  }

</script>
