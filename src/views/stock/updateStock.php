<?php 
use inc\Root;
$this->mainTitle = 'Stock';
$this->subTitle  = 'Update Stock';
//echo "<pre>";print_r($supplierList); echo "</pre>"; die;
?>
<style>
.hide {
    display: none;
}

.contact_list.d-block {
    padding: .75rem 1.25rem;
    border-radius: 6px;
    border: 1px solid #bfc9d4;
    height: calc(1.4em + 1.4rem + 2px);
}

.contact_list span {
    border: 1px solid #a8a8a8;
    border-radius: 10px;
    margin: 5px;
    padding: 5px;
}
</style>
<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                  <nav class="breadcrumb-two" aria-label="breadcrumb">
                      <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="<?=BASEURL;?>Stock/"><?=$this->mainTitle?></a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                       </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form" method="post">
                            <div class="info">
                                <div class="row">
                                    <div class="col-6 col-md-6 form-group">
                                        <label>Select Supplier<span style="color:red;">*</span></label>
                                        <select type="option" name="supplier_id" class="form-control custom-select" id="supplier_id">
                                            <option value="">Select Supplier</option>
                                            <?php 
                                                foreach($supplierList as $key=>$val){?>
                                                <option value="<?=$val['id']?>"><?=$val['supplier_name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact List<span style="color:red;">*</span></label>
                                            <p class="contact_list d-block"></p>  

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Invoice Date<span style="color:red;">*</span></label>
                                            <input type="date"  name="invoice_date" id="invoice_date"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <div class="form-group">
                                            <label>Descriptions<span style="color:red;">*</span></label>
                                            <input type="text"  name="description" id="description"  class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Delivery Order No.<span style="color:red;">*</span></label>
                                            <input type="text"  name="invoice_number" id="invoice_number"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <div class="form-group">
                                            <label>Delivery Date<span style="color:red;">*</span></label>
                                            <input type="date"  name="delivery_date" id="delivery_date"  class="form-control">
                                        </div>
                                    </div>

                                </div>


                                
                                <div class="row">
                                    <div class="col-12 col-md-6 form-group">
                                        <input type="radio" name="addtype" checked="checked" value="1" onclick="show1();"/> Import Stock 
                                        <input type="radio" name="addtype" value="2" onclick="show2();"/> Manual
                                    </div>
                                </div>
                                

                                <div class="row import_stock" >
                                    <div class="col-12 col-md-6 form-group">
                                        <div class="form-group">
                                            <label>Import Stock<a href="<?= BASEURL.'web/assets/Sample_excels/Import_Sample_stock.csv'?>" download>Sample File<i class="fa fa-download" aria-hidden="true"></i></a></label>
                                            <input type="file" id="file" name="file" class="form-control" style="height:53px;">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                </div>

                                <div id="box" class="manual_stock" style="display: none;">
                                    <div class="row">
                                        <div class="col-12 col-md-4 form-group">
                                            <label>Product*</label>
                                            <select type="option" name="product_list[]" class="form-control custom-select product_list" id="product_list1" data-row="1">
                                                <option value="">Select Product</option>
                                                <?php if(! empty($productList)):?>
                                                    <option value="<?=$product_id?>" name="product_id[]" id="product_id1"><?=$product_id?></option>
                                                <?php endif;?>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-3 form-group">
                                            <label>Quantity*</label>
                                            <input type="text" name="unit[]" id="unit" value="" class="form-control" placeholder="Enter unit">
                                        </div>
                                        <div class="col-12 col-md-3 form-group">
                                            <label>Unit Price*</label>
                                            <input type="text" name="unit_price[]" id="unit_price" value="" class="form-control" data-uprice_cnt = '1' placeholder="Enter unit">
                                        </div>

                                        <div class="col-12 col-md-2 form-group">
                                            <button class="btn btn-info addbtn" type="button" style="margin-top: 35px;" onclick="AddRow()">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="countID" id="countID" value="1">
                                  
                                    <div class="col-md-12  text-center" style="margin-top: 2%;">
                                        <button class="btn btn-primary proceedToPayment col-12 col-md-5" id="save" type="button">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="excelModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>
                Upload Excel</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" id="statusUpdate">
            <div class="card-block"> 
                <div class="row m-b-30 form-group">
                    
                   
                     <label>Excel File * <a href="<?= BASEURL.'web/assets/Sample_excels/Import_Sample_stock.csv'?>" download>Sample File<i class="fa fa-download" aria-hidden="true"></i></a> </label>     
                     <input type="file" id="file" name="file" class="form-control" style="height:53px;">
                </div>                                                                      
            </div>
           </form> 
        </div>
        <div class="modal-footer">
               <button type="button" class="btn btn-primary mr-3" onclick="upload()">Upload</button> 
               <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
      </div>
    </div>
</div>

<div class="modal left fade" id="ListImportModel" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="max-width:fit-content;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>
                Import Details</h4>
            <button type="button" class="close cancelImport" data-dismiss="modal" value="">&times;</button>
        </div>
        <form method="post" id="importtable">
        <div class="modal-body" id="table_content_import">
          
        </div>
        <div class="modal-footer">
             
               <button type="button" class="btn btn-primary" value="" id="proceedImport">Proceed</button>
               <button type="button" class="btn btn-danger cancelImport" value="" >Cancel</button>
            </div>
        </form>
      </div>
    </div>
</div>



<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>


<script type="text/javascript">
  $('#accordionExample').find('li a').attr("data-active","false");
  $('#AlcoholMenu').attr("data-active","true");
  $('#AlcoholNav').addClass('show');
  $('#inventory_stock').addClass('active'); 


selected_supplier_id    = '';
selected_invoice_date   = '';
selected_description    = '';  
selected_invoice_number = '';  
selected_delivery_date  = '';  

$('#supplier_id').select2({});
var data = [{ id: 0, text: 'enhancement' }, { id: 1, text: 'bug' }, { id: 2, 

    text: 'duplicate' }, { id: 3, text: 'invalid' }, { id: 4, text: 'wontfix' }];

// var list = '<?= json_encode([['id'=>'0','text'=>'enhancement'],['id'=>'1','text'=>'bug'],['id'=>'2','text'=>'duplicate']])?>';

// list = JSON.parse(list);

list =[];

div_cnt_id=$("select[class*='product_list']").length; 

//$('#product_list'+div_cnt_id).select2({});

//$('#product_list'+div_cnt_id).select2().select2('val','1');
//$("#product_list"+div_cnt_id).select2('data', {id: '1', text:'test'}); 

// $('#product_list'+div_cnt_id).append($('<option>', { 
//     value: '1',
//     text : 'test' 
// },{ 
//     value: '2',
//     text : 'test2' 
// })).select2();

$('#product_list'+div_cnt_id).select2({
      data: list
    })


function show1(){
    $(".import_stock").show();
    $(".manual_stock").hide();
}
function show2(){
    $(".manual_stock").show();
    $(".import_stock").hide();
}

function AddRow() {
    div_cnt_id++;
    var product_list = $(".product_list").html();
    console.log(product_list);
    $('#box').append('<div class="row" id="row' + div_cnt_id + '"><div class="col-12 col-md-4 form-group"><label>Select Product</label> <select type="option" class="form-control custom-select product_list" name="product_list[]" id="product_list' + div_cnt_id + '" data-row="'+div_cnt_id+'"></select></div><div class="col-12 col-md-3 form-group"> <label>Unit</label> <input type="text" name="unit[]" id="unit" value="" class="form-control" placeholder="Enter unit"></div><div class="col-12 col-md-3 form-group"><label>Unit Price</label> <input type="text" name="unit_price[]" id="unit_price" value="" class="form-control" data-uprice_cnt=' + div_cnt_id + ' placeholder="Enter unit"> </div> <div class="col-12 col-md-1 form-group"><button class="btn btn-info addbtn" type="button" style="margin-top: 35px;" onclick="AddRow()">+</button> </div><div class="col-12 col-md-1 form-group"> <button class="btn btn-danger minusbtn" type="button" style="margin-top: 35px;" onclick="DeletRow(' + div_cnt_id + ')">-</button></div> </div> ');

    // Set the initial value for the newly created Select2 dropdown
    //$('#product_list' + div_cnt_id).val('1');

    //$("#product_list"+div_cnt_id).select2('data', {id: '2', text:'test2'}); 

    // Initialize Select2 for the newly added select box
    //$('#product_list' + div_cnt_id).select2();

    // Rest of your code...
    console.log(list);
    $('#product_list'+div_cnt_id).select2({
      data: list
    })
}


/*function AddRow() {
    div_cnt_id++;

    var product_list = $(".product_list").html();
    $('#box').append('<div class="row" id="row' + div_cnt_id + '">...</div>');

    // Initialize Select2 for the newly added select box
    $('#product_list' + div_cnt_id).select2();

    // Rest of your code...
}*/



      $('#countID').val(div_cnt_id)



function DeletRow(id){
    $('#row'+id).remove(); 
}

$('#save').click(function(){
    var checked = $('input[name="addtype"]:checked').val();

    if(checked == 1){
        importStock();
    }
    if(checked == 2){
        saveManually();
    }

});

function importStock(){

    var supplier_id     = $('#supplier_id').val();
    var invoice_date    = $('#invoice_date').val();
    var description     = $('#description').val();
    var invoice_number  = $('#invoice_number').val();
    var delivery_date   = $('#delivery_date').val();
    var checked         = $('input[name="addtype"]:checked').val();

    var url = '<?=BASEURL;?>Stock/AddStock/';

    data = new FormData();
    data.append('addtype', checked);
    data.append('supplier_id', supplier_id);
    data.append('invoice_date', invoice_date);
    data.append('description', description);
    data.append('invoice_number', invoice_number);
    data.append('delivery_date', delivery_date);

    data.append('filename', $('#file')[0].files[0]);
    data.append('import', '1');


    loadingoverlay('info',"Please wait..","loading...");
    $.ajax({
              url: url, 
              dataType: 'text',  
              cache: false,
              contentType: false,
              processData: false,
              data: data,                         
              type: 'post',
              success: function(response){
               

                newResp = JSON.parse(response);
                if(newResp['status'] == 'success') {

                    if(newResp['response']['type'] == 'showpopup') {

                        hideoverlay();
                        $('#ListImportModel').modal('toggle');
                        $('#table_content_import').html("");
                        var header = newResp['response']['html']['header'];
                        var valid = newResp['response']['html']['valid'];
                        var invalid = newResp['response']['html']['invalid'];
                        var footer = newResp['response']['html']['footer'];
                        var combinedHTML = header + valid + invalid + footer;
                        $('#table_content_import').html(combinedHTML);
                        $('#proceedImport').val(newResp['response']['bulk_id']);
                        $('.cancelImport').val(newResp['response']['bulk_id']);

                        selected_supplier_id    = newResp['response']['supplier_id'];
                        selected_invoice_date   = newResp['response']['invoice_date'];
                        selected_description    = newResp['response']['description'];  
                        selected_invoice_number = newResp['response']['invoice_number'];  
                        selected_delivery_date  = newResp['response']['delivery_date']; 

                        
   

                    }

                }else{
                    if(newResp['response']['type'] == 'validation') {

                        loadingoverlay('error','Error',newResp['response']['msg']);  

                    }

                    

                }
                //return false;
                //   $('#excelModal').modal('toggle');
                //   newResp = JSON.parse(response);
                //   if(newResp['status'] == 'success')
                //   {
                //             hideoverlay();
                //             $('#ListImportModel').modal('toggle');
                //             $('#table_content_import').html("");
                //             var header = newResp['response']['html']['header'];
                //             var valid = newResp['response']['html']['valid'];
                //             var invalid = newResp['response']['html']['invalid'];
                //             var footer = newResp['response']['html']['footer'];
                //             var combinedHTML = header + valid + invalid + footer;
                //             $('#table_content_import').html(combinedHTML);
                //             $('#proceedImport').val(newResp['response']['bulk_id']);
                //             $('.cancelImport').val(newResp['response']['bulk_id']);

                //       }else
                //            {
                //             if(newResp['response']['showlistpopup']) {
                                
                //                 hideoverlay();
                //                 $('#ErrorListModal').modal('toggle');
                //                 $('#table_content').html("");
                //                 $('#table_content').html(newResp['response']['html']);
                                 
                //             }else{
                //                 loadingoverlay('error','Error',newResp['response']['msg']);  
                //             }

                            

                               
                //            }
                // return false;
              }
          }); 


}

function saveManually(){
   
    var postdata = $('#formValidation').serialize();

    $.ajax({
        url: '<?=BASEURL;?>Stock/AddStockManually/', 
        data: postdata,                         
        type: 'post',
        success: function(response){ 
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){
                openSuccess(newResp['response'],'<?=BASEURL;?>Stock/UpdateStock/')  
            }else{
             loadingoverlay('error','error',newResp['response']);date
            }
            return false;
        }
    });  
    return false; 
}


$("#proceedImport").click(function(){


        loadingoverlay('info',"Loading","Please Wait");
        data = new FormData();
        var bulk_id=$("#proceedImport").val();
        data.append('bulk_id',bulk_id);
        data.append('supplier_id',selected_supplier_id);
        data.append('invoice_date',selected_invoice_date);
        data.append('description',selected_description);
        data.append('invoice_number',selected_invoice_number);
        data.append('delivery_date',selected_delivery_date);
        $.ajax({
              url: '<?=BASEURL;?>Stock/updateStockFromTemp/', 
              dataType: 'text',  
              cache: false,
              contentType: false,
              processData: false,
              data: data,                         
              type: 'post',
              success: function(response){

                
                  $('#ListImportModel').modal('toggle');
                  newResp = JSON.parse(response);
                  if(newResp['status'] == 'success')
                    {
                        hideoverlay();
                        openSuccess(newResp['response'],'<?=BASEURL;?>Stock/Index')  

                    }else
                    {
                        loadingoverlay('error','Error',newResp['response']);  
                    }
                return false;
              }
          }); 


 });



var result = new Array();

$('#supplier_id').on('change',function () {
    var supplier_id = $('#supplier_id').val();
    data = new FormData();
    data.append('supplier_id', supplier_id);
    $.ajax({
        url: '<?=BASEURL;?>Stock/getSupplierProducts/?supplierid='+supplier_id,
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(response){ 

            $('.product_list').html('');
            $('.product_list').html('<option value="">Select Product</option>');
            var newResp = JSON.parse(response);
            $('.contact_list').html(newResp.contact_dtls);

            $.each(newResp, function (index, value) {

                // list.push({'id':value.id},{'text':value.name+'-'+value.price});
                list.push({'id':value.id,'text':value.name+'-'+value.price});

                // list.push({'text':value.name+'-'+value.price});

                $('.product_list').append($("<option></option>").text(value.name+'-'+value.price).val(value.id));
            });
        }
    });
});

var result = new Array();

function getSelectValues(select,id) {
    
    var value = select;
    var id    = id
    const elementExists = result.includes(value);

    if (!elementExists) {

    result.push(value);
    }
    else{

       document.getElementById("product_list"+id).value = '';
       loadingoverlay('error','error','This item already selected');

    }
}



$('#box').on('change', '.product_list', function() {
    var selectedOption = $(this).val();
    var rowNumber = $(this).data('row');
    $.post('<?= BASEURL ?>Purchase/GetItemPrice/',{item_id:selectedOption},function(response){ 
        var unitPriceInput = $(".manual_stock").find("[data-uprice_cnt='" + rowNumber + "']");
        unitPriceInput.val(response);
    });
});


var f1 = flatpickr(document.getElementById('invoice_date'),{
    dateFormat:"d-m-Y",
    minDate: "01-01-1899"
  });

var f2 = flatpickr(document.getElementById('delivery_date'),{
    dateFormat:"d-m-Y",
    minDate: "01-01-1899"
  });




</script>
