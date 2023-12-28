<?php 
use inc\Root;
$this->mainTitle = 'Purchase';
$this->subTitle  = 'Customer Purchase';
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
                            <form id="purchaseform" class="row col-md-12" method="post" action="<?=BASEURL;?>Purchase/List">
                                <div class="form-group col-sm-3">
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom">
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date">
                                </div>                                 
                                 
                                <div class="form-group col-sm-2">
                                    <select type="option" name="room_id" class="form-control" id="room_id">
                                        <option value="">Select Room</option>
                                        <?php foreach($rooms as $key => $val){?>
                                          <option value="<?=$val['id']?>"><?=$val['room_no']?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="submit" class="btn btn-success div_float" id="search" name="Search" value="Search">
                                </div>
                            </form>
                        </div>

                        <div class="row col-md-12 col-lg-12">
                           
                            <div class="col-md-5 col-lg-5">
                                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;">Yesterday</a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;">Today </a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;">Last 7 days</a> </span>
                                <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export Stock</a> </span>
                                      
                            </div>
                            
                        </div>
                      
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                        <thead>
                                            <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >User ID</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Order ID</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Room/Table</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Description</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Sales Date</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Action</th></tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(!empty($data['data'])){
                                            foreach($data['data'] as $key => $val){ ?>
                                                <tr role="row" class="odd">
                                                    <td><?=$val['uniqueid']?></td>                                          
                                                    <td><?=$val['customer_name'];?></td>
                                                    <td><a class="badge outline-badge-primary" href="<?=BASEURL?>Purchase/CustomerAlcohol/?order_id=<?=$val['order_id']?>"><?=$val['order_id'];?></a></td>  
                                                    <td><?=$val['room_no'];?></td>  
                                                    <td><?=$val['description'];?></td>                    
                                                    <td><?=$val['time'];?></td>
                                                    <td><?php echo '<button class="btn btn-outline-primary mb-2" onclick="viewOrder('.$val['order_id'].')">View</button>'; ?>
                                                        <?php echo '<button class="btn btn-outline-primary mb-2" onclick="viewOrderDetails('.$val['order_id'].',0)">Expiry</button>'; ?>
                                                        <?php echo '<button class="btn btn-outline-primary mb-2" onclick="viewUsedHistory('.$val['id'].',0)">Consumption History</button>'; ?>

                                                    </td> 
                                                </tr>

                                            <?php }
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

<!-- <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Balance</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Expiry date</th>
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
</div> -->


<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Order Details</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="addclosemodal('orderModal')" style="margin-top:3px"  aria-label="Close"> x </button>
            </div>
            <div class="modal-body" id="det" ></div>
        </div>
    </div>
</div>


<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Order Details</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="addclosemodal('orderDetailsModal')" style="margin-top:3px"  aria-label="Close"> x </button>
            </div>
            <span id='orderDetailsModalbody'></span>
            
            
        </div>
    </div>



</div>

<div class="modal fade" id="usedHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Consumption History</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="addclosemodal('usedHistoryModal')" style="margin-top:3px"  aria-label="Close"> x </button>
            </div>
            <div class="modal-body" id="usedHistoryModalbody"></div>
        </div>
    </div>
</div>

<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>

<script>

var datefrom   = '<?=$datefrom?>';
var dateto     = '<?=$dateto?>';
 var userId    = '<?=$user_id?>';
var username   = '<?=$username?>';
 
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

    /*$.ajax({
        url: "<?=BASEURL?>Purchase/getCustomerOrderDetails",
        type: "post",
        data: {'orderid':Id} ,
        success: function (response) {
          $('#det').html('');
          for (var i=0; i<response.length; i++) {
           $('#orderid').html(Id);
           $('#date').html(response[i].time);
           
           $('#det').append('<tr role="row" class="odd"><td>'+(i+1)+'</td><td>'+response[i].item_name+'</td><td>'+response[i].unit+'</td><td>'+response[i].unit_price+'</td><td><input type="text" name="balance" value="'+response[i].balance+'" id="balance"/> </td><td>'+response[i].expiry_date+'</td></tr>');
           $('#total').html(response[i].total_amount);
          }
         
           $('#orderModal').modal('show');
        } 
    });*/

    $('#orderModal').modal('toggle');
    $('#det').load('<?=BASEURL?>Purchase/getCustomerOrderDetails?id='+Id);
}

 $('#pageJump').val("<?=$data['curPage'];?>");

 function closemodal(){
  $('#det').html('');
     $('#orderModal').toggle();
  }


function viewOrderDetails(id,action){
    selectedId =  id;
    $('#orderDetailsModal').modal('toggle');
    $('#orderDetailsModalbody').load('<?=BASEURL?>OrderRequest/getOrderExpiryDetails?id='+selectedId);
}

function viewUsedHistory(id){
    selectedId =  id;
    $('#usedHistoryModal').modal('toggle');
    $('#usedHistoryModalbody').load('<?=BASEURL?>Purchase/getUsedHistory?id='+selectedId);
}

function addExpiry(){

    var btn = "Warning";
    var txt = "Are you sure want to update?";
    swal({
        title: btn,
        text: txt,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        padding: '2em'
    }).then(function(result) {
        if (result.value) {
            loadingoverlay('info',"Please wait..","loading...");
            var postdata = $('#expiryReqForm').serializeArray();
            postdata.push({ name: 'id', value: selectedId});
            $.post('<?=BASEURL;?>Purchase/UpdateOrderExpiry/',postdata,function(response){
                  hideoverlay();
                  console.log(response);
                  newResp = JSON.parse(response);
                  if (newResp['status'] == 'success') {
                      openSuccess(newResp['response']);
                  } else {
                      loadingoverlay("error", "Error", newResp['response']);
                  }
              });
              return false;
        }else{
            //location.reload();
        }
  })
  return false;
}



function exportReport(){
    var user_id     = $('#user_id').val();
    var datefrom    = $('#datefrom').val();
    var dateto      = $('#dateto').val();
    var room_id     = $('#room_id').val();

    loadingoverlay('info',"Loading","Please Wait");
    $.post('<?=BASEURL?>Purchase/Export',{'user_id':user_id, 'datefrom':datefrom, 'dateto' : dateto, 'room_id' : room_id},
        function(response){
            hideoverlay();
            newResp = JSON.parse(response); 
            if(newResp['status'] == 'success'){        
                $('#downfile').html(newResp['response']);
                $('#downloadcsv').click();

            }else{
                loadingoverlay("error","Error","Try Again>");
            }

        });
    return false;     
}

//UpdateOrderDetails


function UpdateOrderDetails(selectedId){
    var btn = "Warning";
    var txt = "Are you sure want to update?";
    swal({
        title: btn,
        text: txt,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        padding: '2em'
    }).then(function(result) {
        if (result.value) {
            loadingoverlay('info',"Please wait..","loading...");
            var postdata = $('#expiryReqForm').serializeArray();
            postdata.push({ name: 'id', value: selectedId});
            $.post('<?=BASEURL;?>Purchase/UpdateOrderDetails/',postdata,function(response){
                  hideoverlay();
                  console.log(response);
                  newResp = JSON.parse(response);
                  if (newResp['status'] == 'success') {
                      openSuccess(newResp['response']);
                  } else {
                      loadingoverlay("error", "Error", newResp['response']);
                  }
              });
              return false;
        }else{
            //location.reload();
        }
  })
  return false;
}
function addclosemodal(modalid){
     
    $('#'+modalid).modal('hide');
}


</script>
