<?php

use inc\Root;


?>
<style>
.modal-dialog {
  max-width: 835px;
  margin: 1.75rem auto;
}
</style>
 <table class="table table-striped">
    
    <tbody>
      <tr>
        <td>Supplier</td>
        <td><?= $details['supplier_name'] ?></td>
        
      </tr>
      <tr>
        <td>Invoice No.</td>
        <td><?= $details['invoice_no'] ?></td>
        
      </tr>
      <tr>
        <td>Invoice Date</td>
        <td><?= $details['invoice_date'] ?></td>
        
      </tr>

      <tr>
        <td>Delivery Date</td>
        <td><?= $details['delivery_date'] ?></td>
        
      </tr>
      
    </tbody>
  </table>

<?php //echo "<pre>"; print_r($details['item']); ?>
<table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
    <thead>
        <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >No</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Brand</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Category</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Vintage</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Country</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Volume</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Alcohol %</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Unit</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Unit Price</th>
    </thead>
    <tbody id="det">
      <?php $i = 1;foreach($details['item'] as $key=>$val){?>
        <tr role="row" class="odd">
            <td><?= $i ?></td>
            <td><?= $val['item_name'] ?></td>
            <td><?= $val['brand'] ?></td>
            <td><?= $val['category'] ?></td>
            <td><?= $val['vintage'] ?></td>
            <td><?= $val['country'] ?></td>
            <td><?= $val['volume'] ?></td>
            <td><?= $val['alcohol'] ?></td>
            <td><?= $val['quantity'] ?></td>
            <td><?= $val['price'] ?></td>
        </tr>
      <?php $i++; } ?>
    </tbody>
</table>
