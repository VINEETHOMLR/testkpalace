<?php 
use inc\Root;
$this->mainTitle = 'Purchase';
$this->subTitle  = 'FOC Report ';

$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

$this->statusArray = ['0'=>'Approved','1'=>'Rejected','2'=>'Pending'];
$this->paymentmodeArray = ['1'=>'Cash','2'=>'Card'];
$this->focStatusArray = ['0'=>'No','1'=>'Yes'];
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
                            <form id="purchaseform" class="row col-md-12" method="post" action="<?=BASEURL;?>OrderRequest/getFocReport">

                              <div class="form-group col-sm-3">
                                    <select type="option" name="customer_id" class="form-control custom-select" id="customer_id">
                                        <option value="">Select Customer</option>
                                        <?php if(! empty($customer_name)):?>
                                          <option value="<?=$customer_id?>" selected><?=$customer_name?></option>
                                        <?php endif;?>
                                    </select>

                                     
                                  </div>
                              <div class="form-group col-sm-4"> 
                                  <select type="option" name="createid" class="form-control custom-select" id="createid">
                                        <option value="">FOC Given by</option>
                                        <?php if(! empty($createid)):?>
                                          <option value="<?=$customer_id?>" selected><?=$admin_name?></option>
                                        <?php endif;?>
                                    </select>
                              </div>
                              <div class="form-group col-sm-4">
                                        <input type="text" id="item_name" class="form-control" placeholder="Item Name" name="item_name">
                                  </div>
                                <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date">
                                  </div>     

                                <div class="form-group col-sm-2">
                                  <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="Search">


                                </div>


                             
                            </form>
                        </div>

                        <div class="row col-md-12 col-lg-12">
                           
                            <div class="col-md-5 col-lg-5">
                                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;">Yesterday</a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;">Today </a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;">Last 7 days</a> </span>
                                <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>
                                      
                            </div>
                            
                        </div>
                      
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Order ID</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Quantity</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Description</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Staff ID</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Staff Name</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Customer ID</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Customer Name</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Room</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Sales Date</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Sales Approved On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                              if(!empty($data['data'])){
                                                // print_r($data);die();
                                                 foreach($data['data'] as $key => $val){ ?>                                

                                                   <tr role="row" class="odd">
                                                       <td><?=$val['id']?></td>
                                                       <td><a class="badge outline-badge-primary" href="<?=BASEURL;?>Alcohol/List?inventory_id=<?=$val['inventory_id'];?>" target="_blank"><?=$val['item_name']?></a></td>
                                                       <td><?=$val['quantity']?></td>
                                                       <td><?=$val['description']?></td>
                                                       <td><?=$val['staff_id']?></td>
                                                       <td><?=$val['staff_name']?></td>
                                                       <td><?=$val['uniqueid']?></td>
                                                       <td><?=$val['customer_name']?></td>
                                                       <td><?=$val['room_no']?></td>
                                                       <td><?=$val['createtime']?></td> 
                                                       <td><?=$val['updated_at']?></td> 
                                                                       
                                                   </tr>

                                          <?php }
                                              }else{
                                                 echo '<tr><td colspan="8" class="text-center">No Data Found</td></tr>';
                                              }   
                                          ?>
                                        </tbody>
                                    </table>
                                </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="purchase_pagination" method="post" action="<?=BASEURL;?>OrderRequest/getFocReport">
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


<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Order Details</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="addclosemodal('viewModal')" style="margin-top:3px"  aria-label="Close"> x </button>

            </div>
            <div class="modal-body" id="viewModalbody">
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
                <div class="row">
                    <div class="col-md-6">
                    <h5 class="modal-title" style="line-height: 2.5;"><b>Payment Mode</b>&nbsp;&nbsp;:</h5></div>                
                    <div class="col-md-6"><h6 class="modal-title" style="line-height: 2.5;" id="order_payment_mode"></h6></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                    <h5 class="modal-title" style="line-height: 2.5;"><b>FOC</b>&nbsp;&nbsp;:</h5></div>                
                    <div class="col-md-6"><h6 class="modal-title" style="line-height: 2.5;" id="is_foc"></h6></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <h5 class="modal-title" style="line-height: 2.5;"><b>Foc Remark</b>&nbsp;&nbsp;:</h5></div>                
                    <div class="col-md-6"><h6 class="modal-title" style="line-height: 2.5;" id="foc_remark"></h6></div>
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

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="addclosemodal('viewModal')">Close</button>
            </div>
          
        </div>
    </div>
</div>

<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>


<script>


var status     = '<?=$status?>';
var payment_mode     = '<?=$payment_mode?>';
var datefrom   = '<?=$from_date?>';
var dateto     = '<?=$to_date?>';
var customer_id     = '<?=$customer_id?>';
var createid     = '<?=$createid?>';
var foc          = '<?=$foc?>';
var item_name          = '<?=$item_name?>';
var selectedId = '';
 
$(function () { 

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#PurchaseMenu').attr("data-active","true");
    $('#PurchaseNav').addClass('show');
    $('#FocReport').addClass('active');

    // $('#userID').val(userID);
   
    $('#status').val(status);
    $('#payment_mode').val(payment_mode);
    $('#dateto').val(dateto);
    $('#datefrom').val(datefrom);
    $('#foc').val(foc);
    $('#item_name').val(item_name);
        
    $('[data-toggle="tooltip"]').tooltip(); 
    
    $('#search').click(function(){
        $('#purchaseform').submit();
    })
});

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

$('#createid').select2({
    placeholder: 'FOC Given By',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>User/GetUsers',
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

function pageHistory(status,customer_id,createid,datefrom,dateto,payment_mode,foc,item_name,page){

 
    
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="customer_id" value="'+customer_id+'" style="display:none;">');
    $('.pagination').append('<input name="createid" value="'+createid+'" style="display:none;">');
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="payment_mode" value="'+payment_mode+'" style="display:none;">');
    $('.pagination').append('<input name="foc" value="'+foc+'" style="display:none;">');
    $('.pagination').append('<input name="item_name" value="'+item_name+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#purchase_pagination').submit();
}



 $("#pageJumpBtn").click(function(){

    pageHistory(status,customer_id,createid,datefrom,dateto,payment_mode,foc,item_name,$("#pageJump").val());

 });


$('#pageJump').val("<?=$data['curPage'];?>");


function addclosemodal(modalid){
     
    $('#'+modalid).modal('toggle');
}

function exportReport(){

    loadingoverlay('info',"Loading","Please Wait");
    $.post('<?=BASEURL?>OrderRequest/FocExport',{'status':status,'customer_id':customer_id,'createid':createid,'dateto':dateto,'payment_mode':payment_mode,'foc':foc,'item_name':item_name},
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
