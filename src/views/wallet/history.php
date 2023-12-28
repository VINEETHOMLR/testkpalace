<?php

use inc\Raise;
use inc\Root;

$this->mainTitle =  "History";
$this->subTitle  =$pageInfo['title'];
$wallet_decimal_limits = $this->decimal;


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
                            <form id="history_form" class="row col-md-12" method="post" action="<?=BASEURL.$pageInfo['url'];?>">
                             
                                    <div class="form-group col-sm-3">
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($input['user_id'])):?>
                                          <option value="<?=$input['user_id']?>" selected><?=$data['s_username']?></option>
                                        <?php endif;?>
                                    </select>
                                  </div>

                                  <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From date" value="" name="datefrom">
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
                                  <div class="form-group col-sm-2">
                                        <select type="option" name="txn_type" class="form-control custom-select" id="txn_type">
                                            <option value="">Transaction Type</option>
                                            <?php
                                                foreach ($this->transactionArray as $key1 => $value1) {
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
                                  <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>
                                      
                            </div>
                            <div class="col-md-7 col-lg-7">

                                  <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="Search">
                            </div>
                        </div>

                         <div class="row col-md-12 col-lg-12">
                          <div class="col-md-5 col-xl-5 col-sm-12">
                            <div class="badge outline-badge-primary  mr-3 mb-1 col-12 p-3"><?=$pageInfo['total_text']?> :<?=number_format($data['total'],4)?></div>
                            
                          </div>                                      
                        </div>
                    </div>

                  

                  
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                               <thead>
                                  <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User ID">Unique ID</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Username</th>
                                 
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Credit Type">Credit Type</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Transaction Type">Transaction Type</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Amount">Amount</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Before Balance">Before Balance</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="After Balance">After Balance</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Transaction Date">Transaction Date</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Remarks">Remarks</th></tr>
                                </thead>
                                <tbody>
                                <?php 
                                  if(!empty($data['data'])){
                                   foreach($data['data'] as $key => $val):?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['uniqueid']?></td>
                                      <td><?=$val['username']?></td>
                                      <td><?=$val['credit_type']?></td>
                                      <td><?=$val['txn_type']?></td>
                                      <td><?=number_format($val['value'],2)?></td>
                                      <td><?=number_format($val['before_bal'],2)?></td>
                                      <td><?=number_format($val['after_bal'],2)?></td>
                                      <td><?=$val['txn_date']?></td>
                                      <td><?=$val['remarks']?></td>
                                    </tr>
                                <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="9" class="text-center">No data Found</td></tr>';
                                  }   
                                ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="btc_pagination" method="post" action="<?=BASEURL.$pageInfo['url'];?>">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$data['pagination'];?>
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

<script>
 
$(function () { 

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#HistoryMainMenu').attr("data-active","true");
  $('#HistoryNav').addClass('show');
  $('#<?=$pageInfo['menu']?>').addClass('active');


 

  $('#datefrom').val("<?=$input['datefrom'];?>");
  $('#dateto').val("<?=$input['dateto'];?>");
  $('#user_id').val("<?=$input['user_id'];?>");
  $('#creditType').val("<?=$input['creditType'];?>");
  $('#txn_type').val("<?=$input['txn_type'];?>");

  });


$("#pageJumpBtn").click(function(){ 
    var date_from = "<?=$input['datefrom'];?>";
    var date_to   = "<?=$input['dateto'];?>";
    var user_id   = "<?=$input['user_id'];?>";
    var txn_type   = "<?=$input['txn_type'];?>";
    var creditType = "<?=$input['creditType'];?>";
    var pge       =$("#pageJump").val();

    pageHistory(date_from,date_to,user_id,txn_type,creditType ,pge);
    });

    $('#pageJump').val("<?=$data['curPage'];?>");




function pageHistory(datefrom,dateto,user_id,trans,credit,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+user_id+'" style="display:none;">');
    
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="txn_type" value="'+trans+'" style="display:none;">');
    $('.pagination').append('<input name="creditType" value="'+credit+'" style="display:none;">');
    $('#btc_pagination').submit();
}


        
  function exportReport(){
        var txn_type   = "<?=$input['txn_type'];?>";
        var creditType = "<?=$input['creditType'];?>";
        var datefrom   = "<?=$input['datefrom'];?>";
        var dateto     = "<?=$input['dateto'];?>";
        var user_id    = "<?=$input['user_id'];?>";
        var tablename  = '<?=$pageInfo['tablename']  ?>';
        var filename   = '<?=$pageInfo['filename']?>';
        var userType   = '<?=!empty($input['userType']) ? $input['userType'] : '';?>';

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>wallet/Export',{'txn_type':txn_type,'creditType':creditType,'datefrom':datefrom,'dateto':dateto,'user_id':user_id,'tablename':tablename,'filename':filename,'userType':userType},
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

  $( function() {

    $('#search').click(function(){
        $('#history_form').submit();
    })
  });


</script>
