<?php

use inc\Root;
$this->paymentmodeArray = ['1'=>'Cash','2'=>'Card'];
$this->focStatusArray = ['0'=>'No','1'=>'Yes'];
?>

<style>
.flatpickr-calendar {
  z-index: 9999 !important;
}

.modal-dialog {
  max-width: 700px;
}

</style>
<form id="expiryReqForm" class="form-horizontal" role="form" method="post">
    <div class="row">
    <div class="col-md-6">
        <table class="table table-bordered no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
            <thead class="thead-dark">
                <tr role="row">
                    <th class="sorting_disabled" rowspan="1" colspan="1">Percentage</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1">Available Bottles</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>100%</td>
                    <td><?= $details['volume_100'] ?></td>
                </tr>
                <tr>
                    <td>75%</td>
                    <td><?= $details['volume_75'] ?></td>
                </tr>
                <tr>
                    <td>50%</td>
                    <td><?= $details['volume_50'] ?></td>
                </tr>
                <tr>
                    <td>25%</td>
                    <td><?= $details['volume_25'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-bordered no-footer" style="width: 100%;">
            <thead class="thead-dark">
                <tr role="row">
                    <th class="sorting_disabled" rowspan="1" colspan="1">Bottle Count</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Available</td>
                    <td><?= $details['volume_100'] + $details['volume_75'] + $details['volume_50'] + $details['volume_25'] ?></td>
                </tr>
                <tr>
                    <td>Total Finished</td>
                    <td><?= $details['volume_0'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
        <thead>
            <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >Sl.No.</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Volume</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Updated date</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Updated BY</th>
            <th class="sorting_disabled" rowspan="1" colspan="1">Signature</th>
        </thead>
        <tbody id="det">
            <?php
            $i=1;foreach($details['data'] as $key=>$value){?>
                <tr role="row" class="odd">
                    <td><?= $i ?></td>
                    <td><?= $value['item_name'] ?></td>
                    <td><?=$value['volume'] ?></td>
                    <td><?=!empty($value['updated_time']) ? date('d-m-Y',$value['updated_time']) : '' ?></td>
                    <td><?=$value['updated_name'] ?></td>
                    <td>
                    <?php
                    if (!empty($value['signature'])) {
                        echo '<img src="' . $value['signature'] . '" width="100" height="50" alt="Signature">';
                    }
                    ?>
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

</script>