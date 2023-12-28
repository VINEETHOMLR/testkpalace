<?php

use inc\Root;
$this->paymentmodeArray = ['1'=>'Cash','2'=>'Card'];
$this->focStatusArray = ['0'=>'No','1'=>'Yes'];
//echo "<pre>";print_r($details['orderList']);
?>

<style>
.flatpickr-calendar {
  z-index: 9999 !important;
}
.modal-dialog {
  max-width: 750px;
}
.blnc_span {
  float: left;
  margin-left: 6px;
  margin-bottom: 6px;
  width: 50px;
}
#balance {
  width: 85px;
}
</style>
<form id="order_details_form" class="form-horizontal" role="form" method="post">

    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
        <thead>
            <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >Sl.No.</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Unit</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Unit Price</th>
            <!-- <th class="sorting_disabled" rowspan="1" colspan="1">Expiry date</th> -->
            <th class="sorting_disabled" rowspan="1" colspan="1">Balance</th>
        </thead>
        <tbody id="det">
            <?php
                $i = 1;
                foreach ($details['orderList'] as $key => $value) {
                    ?>
                    <tr role="row" class="odd">
                        <td><?= $i ?></td>
                        <td><input type='hidden' name='purchase_id[]' value="<?= $value['id'] ?>" /><?= $value['item_name'] ?></td>
                        <td><?= $value['unit'] ?></td>
                        <td><?= $value['unit_price'] ?></td>

                        <td style="width: 140px;">
                            <?php
                            // Create an associative array to store counts for each volume
                            $volumeCounts = [];

                            // Loop through the balance array and count occurrences of each volume
                            foreach ($value['balance'] as $k => $balance) {
                                $volume = $balance['volume'];
                                if (isset($volumeCounts[$volume])) {
                                    $volumeCounts[$volume]++;
                                } else {
                                    $volumeCounts[$volume] = 1;
                                }
                            }
                            $j=0;
                            // Display counts for each volume in one row
                            foreach ($volumeCounts as $volume => $count) {
                            ?>
                                <input type="hidden" name="details_id[<?= $j ?>][]" id="details_id" value="<?= $balance['details_id'] ?>" />
                                <input type="hidden" name="volume[<?= $j ?>][]" id="volume" value="<?= $volume ?>" />
                                <input type="hidden" name="old_balance[<?= $j ?>][]" id="old_balance" value="<?= $count ?>" />
                                <p>
                                    <span class="blnc_span"><?= $volume ?>%</span>
                                    <span class="blnc_span">
                                        <input type="text" id="balance" class="form-control balance" placeholder="Balance" name="balance[<?= $value['id'] ?>][<?= $volume ?>]" value="<?= $count ?>">
                                    </span>
                                </p>
                            <?php
                            $j++;
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            
        </tbody>
    </table>

    <div class="row" style="margin-top: -50px;">
        <div class="col-md-6">
        <h5 class="modal-title" style="line-height: 2.5;"><b>Total Amount</b>&nbsp;&nbsp;:</h5></div>                
        <div class="col-md-3"><h6 class="modal-title" style="line-height: 2.5;" id="total"> <?=$details['order_total'] ?></h6></div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="UpdateOrderDetails(<?=$details['orderList'][0]['order_id']?>)">Update Order</button> 
        <button type="button" class="btn btn-danger" onclick="addclosemodal('orderModal')">Cancel</button>
    </div>
</form>


    
<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>

<script type="text/javascript">

var f1 = flatpickr(document.getElementsByClassName('expire_at'),{
    dateFormat:"d-m-Y",
    minDate: "01-01-1899"
});

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
            var postdata = $('#order_details_form').serializeArray();
            postdata.push({ name: 'id', value: selectedId});

            //console.log(postdata); return false;
            $.post('<?=BASEURL;?>Purchase/UpdateOrderBalExp/',postdata,function(response){
                  hideoverlay();
                  //console.log(response);
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

</script>