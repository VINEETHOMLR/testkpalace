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

<div class="modal-body" >
<form id="expiryReqForm" class="form-horizontal" role="form" method="post">

    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
        <thead>
            <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >No</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Unit</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Unit Price</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Expire at</th>
        </thead>
        <tbody id="det">
            <?php $showUpdateButton = false;
            $i=1;$j=1;foreach($details as $key=>$value){?>
                <tr role="row" class="odd">
                    <td><?= $i ?></td>
                    <td><input type='hidden' name='purchase_id[]' value="<?= $value['purchase_id'] ?>"/><?= $value['item_name'] ?></td>
                    <td><?= $value['unit'] ?></td>
                    <td><?= $value['unit_price'] ?></td>
                    
                    <!-- <td><?php for($index=1;$index<=$value['unit'];$index++){ ?><input type="text" id="expire_at" class="form-control expire_at" placeholder="Expire Date" name="expire_at_<?= $j ?>[]" value=""><br><?php $j++; }?></td> -->
                    <td>
                        <?php foreach($value['expiry_date'] as $k=>$v){
                            if(empty($v['expiry_date'])) {

                                $showUpdateButton = true;

                            }

                        ?>
                            <input type="text" id="expire_at" class="form-control expire_at" placeholder="Expire Date" name="expire_at_<?= $value['purchase_id'].'_'.$v['id'] ?>" value="<?= !empty($v['expiry_date']) ? $v['expiry_date'] : '' ?>"><br>

                        <?php } ?>
                    </td>
                    

                </tr>
            <?php $i++;} ?>
            
        </tbody>
    </table>
</form>
</div>

<div class="modal-footer">
    <?php if($showUpdateButton){?>
    <button type="button" class="btn btn-success" onclick="addExpiry()">Add Expiry</button> 
    <?php }?>
    <button type="button" class="btn btn-danger" onclick="addclosemodal('orderDetailsModal')">Cancel</button>
</div>


    
<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>

<script type="text/javascript">

var f1 = flatpickr(document.getElementsByClassName('expire_at'),{
    dateFormat:"d-m-Y",
    minDate: "01-01-1899"
});

</script>