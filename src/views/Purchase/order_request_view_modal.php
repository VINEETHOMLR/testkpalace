<?php

use inc\Root;


?>
 <table class="table table-striped">
    
    <tbody>
      <tr>
        <td>Customer</td>
        <td><?= $details['customer_name'] ?></td>
        
      </tr>
      <tr>
        <td>Created By</td>
        <td><?= $details['created_by'] ?></td>
        
      </tr>
      <tr>
        <td>Total Amount</td>
        <td><?= $details['total_amount'] ?></td>
        
      </tr>

      <tr>
        <td>Payment Mode</td>
        <td><?= $details['payment_mode'] ?></td>
        
      </tr>

      <tr>
        <td>Foc</td>
        <td><?= $details['foc'] ?></td>
        
      </tr>
      <tr>
        <td>Foc Remark</td>
        <td><?= $details['foc_remark'] ?></td>
        
      </tr>
      <tr>
        <td>Status</td>
        <td><?= $details['status'] ?></td>
        
      </tr>
      <tr>
        <td>Order Date</td>
        <td><?= $details['createtime'] ?></td>
        
      </tr>
    </tbody>
  </table>


<table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
    <thead>
        <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >No</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Item Name</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Unit</th>
        <th class="sorting_disabled" rowspan="1" colspan="1">Unit Price</th>
    </thead>
    <tbody id="det">
        <tr role="row" class="odd">
            <td><?= $details['customer_name'] ?></td>
            <td><?= $details['customer_name'] ?></td>
            <td><?= $details['customer_name'] ?></td>
            <td><?= $details['customer_name'] ?></td>
        </tr>
    </tbody>
</table>
