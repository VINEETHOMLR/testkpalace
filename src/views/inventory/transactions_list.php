<?php

use inc\Raise;
use inc\Root;

$this->mainTitle =  "Inventory Transactions";

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
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <form id="history_form" class="row col-md-12" method="post" action="<?=BASEURL?>Inventory/TransactionsList/">
                                <div class="form-group col-sm-3">
                                    <select type="option" name="item_id" class="form-control custom-select" id="item_id">
                                        <option value="">Select Item name</option>
                                        <?php if(! empty($item_id)):?>
                                          <option value="<?=$item_id?>" selected><?=$item_name?></option>
                                        <?php endif;?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-2">
                                    <input type="text" id="datefrom" class="form-control" placeholder="From date" value="" name="datefrom">
                                </div>
                                <div class="form-group col-sm-3">
                                    <select type="option" name="foc" class="form-control custom-select" id="foc">
                                        <option value="">Select FOC </option>
                                        <option value="0">No FOC </option>
                                        <option value="1">FOC</option>
                                        
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date">
                                </div>
                                <div class="form-group col-sm-2">
                                    <select type="option" name="creditType" class="form-control custom-select" id="creditType">
                                        <option value="">Credit Type</option>
                                        <?php
                                            foreach ($this->creditType as $key1 => $value1) {
                                              echo '<option value="'.$key1.'">'.$value1.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <select type="option" name="transType" class="form-control custom-select" id="transType">
                                        <option value="">Transaction Type</option>
                                        <?php
                                            foreach ($this->transType as $key1 => $value1) {
                                              echo '<option value="'.$key1.'">'.$value1.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-5 col-lg-5">
                                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;">Yesterday</a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;">Today</a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;">Last 7 days</a> </span>

                                <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export Transactions</a> </span>

                            </div>
                            <div class="col-md-7 col-lg-7">
                                <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="Search">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                       <thead>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Transaction ID</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Item Name</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Category</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Brand</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Vintage</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Country</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Volume</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Alcohol %</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Price</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Amount">Quantity</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="FOC">FOC</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Credit Type">Credit Type</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Transaction Type">Transaction Type</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Transaction Date">Transaction Date</th>
                                          <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Updated By</th>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        if(!empty($data['data'])){
                                           foreach($data['data'] as $key => $val):?>
                                                <tr role="row" class="odd">
                                                    <td><?=$val['id']?></td>
                                                    <td><?=$val['name']?></td>
                                                    <td><?=$val['category']?></td>
                                                    <td><?=$val['brand']?></td>
                                                    <td><?=$val['vintage']?></td>
                                                    <td><?=$val['country']?></td>
                                                    <td><?=$val['volume']?></td>
                                                    <td><?=$val['alcohol']?></td>
                                                    <td><?=$val['price']?></td>
                                                    <td><?=$val['quantity']?></td>
                                                    <td><?=$val['foc']?></td>
                                                    <td><?=$val['credit_type']?></td>
                                                    <td><?=$val['trans_type']?></td>
                                                    <td><?=$val['date']?></td>
                                                    <td><?=$val['updated_by']?></td>
                                                </tr>
                                            <?php endforeach;
                                        }else{
                                             echo '<tr><td colspan="15" class="text-center">No data Found</td></tr>';
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row text-center">
                                <form class="col-md-12" id="btc_pagination" method="post" action="<?=BASEURL?>Inventory/TransactionsList/">
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
</div>
<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>
<style>
table {
  display: block;
  overflow-x: auto;
  white-space: nowrap;
}


</style>
<script>
 
$(function () { 

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#AlcoholMenu').attr("data-active","true");
  $('#AlcoholNav').addClass('show');
  $('#inventory_transactions').addClass('active');


 

  $('#datefrom').val("<?=$datefrom?>");
  $('#dateto').val("<?=$dateto?>");
  $('#item_id').val("<?=$item_id?>");
  $('#creditType').val("<?=$creditType?>");
  $('#transType').val("<?=$transType?>");
  $('#foc').val("<?=$foc?>");

  });


$("#pageJumpBtn").click(function(){ 
    var date_from   = "<?=$datefrom?>";
    var date_to     = "<?=$dateto?>";
    var item_id     = "<?=$item_id?>";
    var creditType  = "<?=$creditType?>";
    var transType   = "<?=$transType?>";
    var foc         = "<?=$foc?>";
    var page        =$("#pageJump").val();

    pageHistory(item_id, datefrom, dateto, transType, creditType,  page);
    });

    $('#pageJump').val("<?=$data['curPage'];?>");

function pageHistory(item_id, datefrom, dateto, transType,foc, creditType,  page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="item_id" value="'+item_id+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="transType" value="'+transType+'" style="display:none;">');
    $('.pagination').append('<input name="foc" value="'+foc+'" style="display:none;">');
    $('.pagination').append('<input name="creditType" value="'+creditType+'" style="display:none;">');
    $('#btc_pagination').submit();
}

$( function() {
    $('#search').click(function(){
        $('#history_form').submit();
    })
});

function exportReport(){
    var date_from   = "<?=$datefrom?>";
    var date_to     = "<?=$dateto?>";
    var item_id     = "<?=$item_id?>";
    var creditType  = "<?=$creditType?>";
    var transType   = "<?=$transType?>";
    var foc         = "<?=$foc?>";
    
    loadingoverlay('info',"Loading","Please Wait");
    $.post('<?=BASEURL?>Inventory/ExportInvTransactions',{'date_from':date_from,'date_to':date_to,'item_id':item_id,'creditType':creditType ,'foc':foc,'transType':transType},
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


</script>
