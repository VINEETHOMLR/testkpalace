<?php

use inc\Root;
$this->paymentmodeArray = ['1'=>'Cash','2'=>'Card'];
$this->focStatusArray = ['0'=>'No','1'=>'Yes'];
//echo "<pre>"; print_r($details);
?>

<style>
.flatpickr-calendar {
  z-index: 9999 !important;
}
</style>
<form id="approvalReqForm" class="form-horizontal" role="form" method="post">

    <div id="paymentmodewrapper" >
        <label>Payment Mode</label>
        <select type="option" name="update_payment_mode" class="form-control select" id="update_payment_mode">
            <option value="">Select Payment Mode</option>
            <?php
            foreach ($this->paymentmodeArray as $key => $value) {
                echo '<option value="'.$key.'">'.$value.'</option>';
            }
            ?>
        </select>

    </div>  

    <label>FOC</label>
    <select type="option" name="update_foc" class="form-control select" id="update_foc" onchange="changeFoc($(this).val())">
        <?php
        foreach ($this->focStatusArray as $key => $value) {
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
        ?>
    </select>

    <div id="focremarklistwrapper" style="display:none;">
        <label>FOC Remark List</label>
        <select type="option" name="update_foc_remark_id" class="form-control select" id="update_foc_remark_id">
            <option value="">Select FOC Remark</option>
            <?php
            foreach ($focList as $key => $value) {
                echo '<option value="'.$value['id'].'">'.$value['remark'].'</option>';
            }
            ?>
        </select>
    </div>


    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
        <thead>
            <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >No</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Unit</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Unit Price</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Expire at</th>
        </thead>
        <tbody id="det">
            <?php $i=1;foreach($details as $key=>$value){?>
                <tr role="row" class="odd">
                    <td><?= $i ?></td>
                    <td><input type='hidden' name='purchase_id[]' value="<?= $value['id'] ?>"/><?= $value['item_name'] ?></td>
                    <td><?= $value['unit'] ?></td>
                    <td><?= $value['unit_price'] ?></td>
                    <td>
                        <?php for($index=1;$index<=$value['unit'];$index++){?>
                            <input type="text" id="expire_at_<?= $value['id'].'_'.$index?>" class="form-control expire_at" placeholder="Expire Date" name="expire_at_<?= $value['id']?>[]"><br>
                        <?php }?>    

                    </td>
                </tr>
            <?php $i++;} ?>
            
        </tbody>
    </table>
</form>


    
<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>

<script type="text/javascript">

var f1 = flatpickr(document.getElementsByClassName('expire_at'),{
    dateFormat:"d-m-Y",
    minDate: "01-01-1899"
});

function changeFoc(focstatus){
    $('#focremarklistwrapper').hide();
    $('#paymentmodewrapper').show();
    if(focstatus == '1') {
        $('#focremarklistwrapper').show(); 
        $('#paymentmodewrapper').hide(); 
    }
}

</script>