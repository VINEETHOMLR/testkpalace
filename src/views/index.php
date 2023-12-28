<?php
use inc\Root;

$this->mainTitle = 'Dashboard'; 
$this->subTitle  = ''; 

if(empty($site_status)){
    $status_txt1  = "Site is running";
    $status_txt2  = "Turn ON Maintanace";
    $statusChange = 1;
}else{
    $status_txt1  = "Site is under maintanace";
    $status_txt2  = "Turn OFF Maintanace";
    $statusChange = 0;
}


?>
<link href="<?=WEB_PATH?>assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css">
<script src="<?=WEB_PATH?>assets/js/chart.min.js"></script>



<style>
    .widget{
        padding: 20px;
    }
    .td-content{
        text-align: left !important;
    }
    .table{
   
   width: 100% !important;
    max-height: 300px;
     

 }

     .max{
    display: none;
}
 
   
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
               

            <?php if( in_array(7, $this->admin_services) || ($this->admin_role==1)){ ?>
            
                <div class="col-12 layout-spacing layout-top-spacing">
                    <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content row col-12 col-md-12">
                                    <div class="w-info col-12 col-md-4">
                                        <p class=""><?=$status_txt1?></p>
                                    </div>
                                    <div class="w-info col-12 col-md-8">
                                        <button class="btn btn-primary btn-sm col-12 col-md-4 pull-right" onclick="updateSiteStatus(<?=$statusChange?>)"><?=$status_txt2?></button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

            <?php }  ?>
 <div class="col-6 layout-spacing layout-top-spacing">
                    <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content row col-12 col-md-12">
                                   
                                      <canvas id="myChart" style="width:100%;max-width:1000px"></canvas>
                                   
                                   
                                </div>
                            </div>
                    </div>

                    

                </div>
                <div class="col-6 layout-spacing layout-top-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content row col-12 col-md-12">
                                    <canvas id="customerSalesChart" style="width:100%;max-width:1000px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
 
<div class="col-6 col-md-6 layout-spacing layout-top-spacing ">
                        <div class="widget widget-card-four">
                            <div class="widget-content row col-12 col-md-12">
                                <div class="w-content "style="width:100%;max-width:1000px">
                                   <div class="card col-12">
                   <div class="card-header">
                    <h6>Out of Stock Soon</h6>
                   </div>
                  <div class="card-body table-responsive " >

                    <table class="table table-striped ">
                    <thead >
                       <tr>
                           <th>Sl No</th>
                           <th>Products</th>
                           <th>Quantity</th>
                 
                      </tr>
                    </thead>
                    <tbody >
                   <?php if(!empty($outofstock['data'])){
                      $count = 1; ?>
                   
                     <?php  foreach($outofstock['data'] as $val)  { ?>
                       <?php if(!empty($val['name'])){?>
                       <tr>
                           <td><?php echo $count; ?></td>
                           <td><?= $val['name']; ?></td>
                           <td><?= $val['quantity']; ?></td>
                 
                       </tr>
                   <?php }?>
                    <?php
                     $count+=1;
                      } 
                   }

                 ?>
                        
             
                    </tbody>
                    </table>
                 </div>
              </div>
               
                                </div>
                            </div>
                        </div>
                    </div>

<div class="col-6 col-md-6 layout-spacing layout-top-spacing ">
    <div class="widget widget-card-four">
        <div class="widget-content row col-12 col-md-12">
            <div class="w-content "style="width:100%;max-width:1000px">
                <div class="card col-12">
                    <div class="card-header">
                    <h6>Today's Approved Leave</h6>
                    </div>
                    <div class="card-body table-responsive " >

                    <table class="table table-striped ">
                    <thead >
                       <tr>
                           <th>Sl No</th>
                           <th>Staff ID</th>
                           <th>User Name</th>
                           <th>Leave Type</th>
                 
                      </tr>
                    </thead>
                    <tbody >
                   <?php if(!empty($leave_data['data'])){
                      $count = 1; ?>
                   
                     <?php  foreach($leave_data['data'] as $val)  { ?>
                       <?php if(!empty($val['user_id'])){?>
                       <tr>
                           <td><?php echo $count; ?></td>
                           <td><?= $val['staff_id']; ?></td>
                           <td><?= $val['user_id']; ?></td>
                           <td><?= $val['leave_type']; ?></td>
                 
                       </tr>
                      <?php }?>
                     <?php
                     $count+=1;
                      } 
                      }

                    ?>
                        
             
                    </tbody>
                    </table>
                </div>
            </div>
               
        </div>
    </div>
   </div>
</div>

<div class="col-6">
    <div class="card">
        <div class="card-header">
            <h6>Inventory - Overview</h6>
        </div>
        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-4">
                    <label>Total Inventory</label>
                    <p style="color: green; font-weight: bold; font-size: 20px;"><?= $inv_overview['total_purchase'] ?></p>
                </div>
            </div>

            <div class="row">
                <?php
                foreach ($inv_overview['total_stock'] as $key => $value) {
                ?>
                    <div class="col-4">
                        <p><?= $value['category_name']; ?></p>
                        <p><?= $value['purchase_quantity']; ?></p>
                    </div>
                <?php } ?>
            </div>

            <!-- Reduced Canvas Size -->
            <canvas id="inventoryPieChart" width="5" height="5"></canvas>

        </div>
    </div>
</div>





<!-- <div class="col-6 col-md-4 layout-spacing layout-top-spacing ">
    <div class="widget widget-card-four">
        <div class="widget-content row col-12 col-md-12">
            <div class="w-content "style="width:100%;max-width:1000px">
                <div class="card col-12">
                    <div class="card-header">
                        <h6>Inventory - In Stock</h6>
                    </div>
                    <div class="card-body table-responsive " >
                        <label>Stock Inventory </label>
                        <p><?= $inv_instock['purchase_total'] ?></p>
                    </div>
                </div> 
            </div>
        </div>
   </div>
</div> -->


<div class="col-6">
    <div class="card">
        <div class="card-header">
            <h6>Inventory - In Stock</h6>
        </div>
        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-4">
                    <label>Stock Inventory</label>
                    <p style="color: green; font-weight: bold; font-size: 20px;"><?= $inv_instock['total_avail_stock']."/".$inv_instock['purchase_total'] ?></p>
                </div>

                <!-- Canvas for Pie Chart to the right -->
                <div class="col-8 text-center" style="margin-top: -4%;">
                    <canvas id="inventoryStockPieChart" width="100" height="100"></canvas>
                </div>
            </div>

            <div class="row">
                <?php
                foreach ($inv_instock['total_stock'] as $key => $value) {
                ?>
                    <div class="col-4">
                        <p><?= $value['category_name']; ?></p>
                        <p><?= $value['stock_quantity'] . ' / ' . $value['purchase_quantity']; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>




<!-- <div class="col-6 col-md-4 layout-spacing layout-top-spacing ">
    <div class="widget widget-card-four">
        <div class="widget-content row col-12 col-md-12">
            <div class="w-content "style="width:100%;max-width:1000px">
                <div class="card col-12">
                    <div class="card-header">
                        <h6>Inventory - Customer Cellar</h6>
                    </div>
                    <div class="card-body table-responsive" >
                        <label>Customer Cellar </label>
                        <p><?= $inv_cust_cellar['sales_total'] ?></p>
                    </div>
                </div> 
            </div>
        </div>
   </div>
</div> -->


<div class="col-6">
    <div class="card">
        <div class="card-header">
            <h6>Inventory - Customer Cellar</h6>
        </div>
        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-4">
                    <label>Customer Cellar</label>
                    <p style="color: green; font-weight: bold; font-size: 20px;"><?= $inv_cust_cellar['sales_total']."/".$inv_cust_cellar['purchase_total'] ?></p>
                </div>
                <!-- Canvas for Pie Chart to the right -->
                    <div class="col-8 text-center" style="margin-top: -4%;">
                        <canvas id="inventorycellerPieChart" width="100" height="100"></canvas>
                    </div>
            </div>

            <div class="row">
                <?php
                foreach ($inv_cust_cellar['total_stock'] as $key => $value) {
                ?>
                    <div class="col-4">
                        <p><?= $value['category_name']; ?></p>
                        <p><?= $value['sales_quantity'] . ' / ' . $value['purchase_quantity']; ?></p>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
</div>

           
 </div>
 </div>
 </div>
<script>
    $('#dashboard').attr("data-active","true");

    function updateSiteStatus(status){

            if(status==1){
                var btn  = "Turn ON"; var txt = "Turn ON Maintanane ?"
            }else{
                var btn  = "Turn OFF";  var txt = "Turn OFF Maintanane ?"
            }

            swal({
                 title: btn,
                 text: txt,
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonText: btn,
                 padding: '2em'
                }).then(function(result) {
                    if (result.value) {
                       loadingoverlay('info',"Please wait..","loading...");
                       $.post('<?= BASEURL; ?>index/UpdateSite/', {'status':status}, function(response) {
                            hideoverlay();
                            newResp = JSON.parse(response);
                            if (newResp['status'] == 'success') {
                                openSuccess(newResp['response']);
                            } else {
                                loadingoverlay("error", "Error", newResp['response']);
                            }
                        });
                        return false;
                    }
                })
    }

    

</script>      
       
<script type="text/javascript">
var xValues = <?php echo json_encode($name);?>;
var yValues = <?php echo json_encode($quantity);?>;
var barColors = <?php echo json_encode(array_values($color_array));?>;


new Chart("myChart", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "in-Stock"
    }
  }
});

var chart_data = '<?php echo json_encode($customerSales['datasets']);?>';

var decodedJsonString = chart_data.replace(/&amp;/g, '&');
var jsonArray = JSON.parse(decodedJsonString);

new Chart("customerSalesChart", {
    type: "bar",
    data: {
        labels: [
            "<?=date('d-m-Y')?>",
            "<?=date('d-m-Y',strtotime("-1 days"))?>",
            "<?=date('d-m-Y',strtotime("-2 days"))?>",
            "<?=date('d-m-Y',strtotime("-3 days"))?>",
            "<?=date('d-m-Y',strtotime("-4 days"))?>",
            "<?=date('d-m-Y',strtotime("-5 days"))?>",
            "<?=date('d-m-Y',strtotime("-6 days"))?>",
        ],
        datasets: jsonArray
    },
    options: {
        legend: { display: false },
        title: {
            display: true,
            text: "Customer Sales - Last Week"
        }
    }
});


// JavaScript to create the inventory Pie Chart
var ctxStock = document.getElementById('inventoryStockPieChart').getContext('2d');
var dataStock = {
    labels: ['Total Purchase', 'Total Stock'],
    datasets: [{
            data: [<?= $inv_instock['purchase_total'] ?>, <?= array_sum(array_column($inv_instock['total_stock'], 'stock_quantity')) ?>],
            backgroundColor: ['#3498db', '#2ecc71']
    }]
};

// Responsive Options
var optionsStock = {
    responsive: true,
    maintainAspectRatio: false,
    title: {
            display: true,
            text: ""
        },
    legend: {
            display: false  // Set to false to hide the legend
    }
    };

var inventoryStockPieChart = new Chart(ctxStock, {
        type: 'pie',
        data: dataStock,
        options: optionsStock
    });



// JavaScript to create the celler Pie Chart
var ctxStock = document.getElementById('inventorycellerPieChart').getContext('2d');
var dataStock = {
    labels: ['Total Purchase','Total Cell'],
    datasets: [{
            data: [<?= array_sum(array_column($inv_cust_cellar['total_stock'], 'purchase_quantity')) ?>,<?= $inv_cust_cellar['sales_total'] ?>],
            backgroundColor: ['#3498db','#2ecc71']
    }]
};

// Responsive Options
var optionsStock = {
    responsive: true,
    maintainAspectRatio: false,
    title: {
            display: true,
            text: ""
        },
    legend: {
            display: false  // Set to false to hide the legend
    }
    };

var inventorycellerPieChart = new Chart(ctxStock, {
        type: 'pie',
        data: dataStock,
        options: optionsStock
    });
</script>