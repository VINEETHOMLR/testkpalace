
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

.contact_list {
  border: 1px solid #a8a8a8;
  border-radius: 10px;
  margin: 1px;
  padding: 3px;
  display: inline-block;
}

</style>
<?php ?>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row">
                        <form id="btc_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Stock/">
                            <div class="row col-md-12 col-xs-12">
                                <div class="form-group col-sm-3">
                                    <select type="option" name="supplier_id" class="form-control custom-select" id="supplier_id">
                                        <option value="">Select Supplier</option>
                                        <?php 
                                            foreach($data['supplierList'] as $key=>$val){?>
                                            <option value="<?=$val['id']?>"><?=$val['supplier_name']?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="contact_person" class="form-control select" id="contact_person" placeholder="Contact Person">
                                </div>

                                <div class="form-group col-sm-2">
                                    <input type="text" name="contact_no" class="form-control select" id="contact_no" placeholder="Contact No.">
                                </div>

                                <div class="form-group col-sm-2">
                                    <input type="text" name="order_no" class="form-control select" id="order_no" placeholder="Order No.">
                                </div>

                                <div class="form-group col-sm-2">
                                    <input type="date" name="invoice_date" class="form-control select" id="invoice_date" placeholder="Invoice Date">
                                </div>

                                <div class="form-group col-sm-2">
                                    <input type="date" name="delivery_date" class="form-control select" id="delivery_date" placeholder="Delivery Date">
                                </div>
                               <div class="form-group col-sm-3">
                                  <input type="submit" class="btn btn-success" id="search" name="Search" value="Search">
                                </div> 
                            </div>  
                        </form>                 
                    </div>
                    <div class="row">
                        <div class="col-md-5 col-lg-5">
                               
                                <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export Stock</a> </span>
                                      
                            </div>
                        <div class="col-md-7 col-lg-7">
                            <a href="<?=BASEURL;?>Stock/UpdateStock/" class="full_width div_float">
                                <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add Stock</button>
                            </a>
                        </div>
                        
                    </div>

                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                        <thead>
                                            <tr>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Sl.No</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Supplier Name</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Contact Details</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Invoice Date</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Items</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Description</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Delivery Order No.</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Delivery Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if(!empty($data['data'])){
                                                $i=1;
                                                foreach($data['data'] as $key => $val){
                                                    ?>
                                                    <tr role="row" class="odd">
                                                        <td><?=htmlspecialchars_decode($i)?></td>
                                                        <td><?=htmlspecialchars_decode($val['supplier_name'])?></td>
                                                        <td><?=$val['contact_details']?></td>
                                                        <td><?=date('d-m-Y',$val['invoice_date'])?></td>
                                                        <td><?=$val['action']?></td>
                                                        <td><?=htmlspecialchars_decode($val['description'])?></td>
                                                        <td><?=htmlspecialchars_decode($val['invoice_number'])?></td>
                                                        <td><?=date('d-m-Y',$val['delivery_date'])?></td>
                                                    </tr>
                                                <?php $i++; };
                                            }else{
                                                echo '<tr><td colspan="9" class="text-center">No Data Found</td></tr>';
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row text-center">
                                <form class="col-md-12" id="deposit_pagination" method="post" action="<?=BASEURL;?>Stock/">
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

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Stock Details</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="closemodal('viewModal')" style="margin-top:3px"  aria-label="Close"> x </button>

            </div>
            <div class="modal-body" id="viewModalbody">
                

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="closemodal('viewModal')">Close</button>
            </div>
          
        </div>
    </div>
</div>



<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>

<script>

  $('#supplier_id').select2({});


  
   

 
$(function () { 

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#AlcoholMenu').attr("data-active","true");
  $('#AlcoholNav').addClass('show');
  $('#inventory_stock').addClass('active');

  $('#search').click(function(){
      $('#btc_form').submit();
  })

  $('#supplier_id').val("<?=$filter['supplier_id']?>");
  $('#order_no').val("<?=$filter['order_no']?>");
  $('#invoice_date').val("<?=$filter['invoice_date']?>");
  $('#delivery_date').val("<?=$filter['delivery_date']?>");

});

function pageHistory(supplier_id, order_no, invoice_date, delivery_date, page){
    $('.pagination').append('<input name="supplier_id" value="'+supplier_id+'" style="display:none;">');
    $('.pagination').append('<input name="order_no" value="'+order_no+'" style="display:none;">');
    $('.pagination').append('<input name="invoice_date" value="'+invoice_date+'" style="display:none;">');
    $('.pagination').append('<input name="delivery_date" value="'+delivery_date+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
   
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
          text: "Delete This Supplier Category !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Alcohol/SupplierDelete/',{getId:val},function(response){ 
    
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
    var supplier_id     = "<?=$filter['supplier_id']?>";
    var order_no        = "<?=$filter['order_no']?>";
    var invoice_date    = "<?=$filter['invoice_date']?>";
    var delivery_date   = "<?=$filter['delivery_date']?>";
    var page            = $("#pageJump").val();


    pageHistory(supplier_id, order_no, invoice_date, delivery_date, page);
 });

 $('#pageJump').val("<?=$data['curPage'];?>");

 var f1 = flatpickr(document.getElementById('invoice_date'),{
    dateFormat:"d-m-Y",
    minDate: "01-01-1899"
  });

var f2 = flatpickr(document.getElementById('delivery_date'),{
    dateFormat:"d-m-Y",
    minDate: "01-01-1899"
  });


function exportReport(){
      
    var supplier_id     = "<?=$filter['supplier_id']?>";
    var order_no        = "<?=$filter['order_no']?>";
    var invoice_date    = "<?=$filter['invoice_date']?>";
    var delivery_date   = "<?=$filter['delivery_date']?>";
    
    loadingoverlay('info',"Loading","Please Wait");
    $.post('<?=BASEURL?>Stock/Export',{'supplier_id':supplier_id,'order_no':order_no,'invoice_date':invoice_date,'delivery_date':delivery_date},
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

function showViewModal(id){
    $('#viewModal').modal('toggle');
    $('#viewModalbody').load('<?=BASEURL?>Stock/getStockDetails?id='+id);
}


function closemodal(modalid){ 
    $('#'+modalid).modal('toggle');
}


</script>
