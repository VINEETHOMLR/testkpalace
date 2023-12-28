<?php 
use inc\Root;
$this->mainTitle = 'Purchase';
$this->subTitle  = 'Create Purchase';
$productArray = json_encode($inventorylist);

?>


<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                  <nav class="breadcrumb-two" aria-label="breadcrumb">
                      <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="<?=BASEURL;?>Purchase/List/">Customer Purchase</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                       </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form" method="post">
                            <div class="info">
                                <div class="row">

                                  <div class="col-12 col-md-6 form-group">
                                    <label>Select Customer Username/ID*</label>
                                    
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select User Id</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                  </div>

                                  <div class="col-12 col-md-6 form-group">
                                        <label>Select Type*</label>
                                        <div><input type='radio' name="room_table" value='1' checked="checked" onclick="changeType(this);"/> Room &nbsp;&nbsp;&nbsp;<input type='radio' name="room_table" value="2" onclick="changeType(this);"/> Table</div>

                                  </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6 form-group">
                                        <label class="type_label">Select Room*</label>
                                        
                                        <select type="option" name="room_id" class="form-control custom-select" id="room_id">
                                            <option value="">Select Room</option>
                                            <?php if(!empty($roomList)){
                                                foreach($roomList as $key=>$value){?>
                                                  <option value="<?=$value['id']?>"><?=$value['room_no']?></option>
                                                <?php }
                                            }?>
                                        </select>
                                    </div>
                                  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text"  name="description" id="description"  class="form-control">
                                        </div>
                                    </div>


                                </div>
                                      <div id="box">
                                        
                                          <div class="row dynamic_input_div">

                                            <div class="col-12 col-md-4 form-group">
                                                <label>Product Name*</label>
                                                <select class="form-control custom-select alcohol_list custom-select" name="name[]" id="alcohol_id" onfocusout ="getSelectValues(this.value , '0')" data-row="1">
                                                    <option value="">Select Product</option>
                                                    <?php
                                                        foreach ($inventorylist as $key => $value) {
                                                          echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                                                        }
                                                        ?>
                                                </select>                                    
                                            </div>

                                            <div class="col-12 col-md-2 form-group">
                                                <label>Unit*</label>
                                                <input type="number" name="unit[]" id="unit" value="" class="form-control" placeholder="Enter unit" oninput="validateUnit(this)" min="0">
                                                <span class="error-message" id="unit-error" style="color:red;"></span>
                                 
                                            </div>

                                            <div class="col-12 col-md-2 form-group">
                                                <label>Unit Price*</label>
                                                <input type="text" name="unit_price[]" id="unit_price" value="" class="form-control unit_price" data-uprice_cnt = '1' placeholder="Enter unit Price" readonly>                                 
                                            </div>

                                            <div class="col-12 col-md-2 form-group">
                                                <label>Expiry Date*</label>
                                                <input type="text" name="expire_at[]" id="expire_at" value="" class="form-control expire_at flatpickr-input active" placeholder="Enter Expiry Date" readonly="readonly">                                 
                                            </div>

                                              <div class="col-12 col-md-1 form-group">
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

<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>


<script type="text/javascript">
  $('#accordionExample').find('li a').attr("data-active","false");
  $('#PurchaseMenu').attr("data-active","true");
  $('#PurchaseNav').addClass('show');
  $('#create_purchase').addClass('active');
  
id=2; 

function productHtml(){
  
  var productArray = '<?= $productArray ?>';
  productArray = JSON.parse(productArray);
  var html = '<option value="">Select Product</option>';
  $.each(productArray, function (key, val) {

      html += '<option value='+val.id+'>'+val.name+'</option>';  
        
  });

  return html;

}
function AddRow(){

    //var alcohol_list = $(".alcohol_list").html();

    var alcohol_list = productHtml();

     
    $('#box').append('<div class="row dynamic_input_div" id="row'+id+'"><div class="col-12 col-md-4 form-group"><label>Select Product</label> <select class="form-control custom-select alcohol_list" name="name[]" id="alcohol_id'+id+'" onfocusout ="getSelectValues(this.value , '+id+')" data-row="'+id+'">'+alcohol_list+'</select></div><div class="col-12 col-md-2 form-group"> <label>Unit</label> <input type="number" name="unit[]" id="unit" value="" class="form-control" placeholder="Enter unit" oninput="validateUnit_dynamic(this)" min="0"><span class="error-message" style="color:red;"></div></span><div class="col-12 col-md-2 form-group"><label>Unit Price</label> <input type="text" name="unit_price[]" id="unit_price" value="" class="form-control unit_price" data-uprice_cnt="'+id+'" placeholder="Enter unit Price" readonly> </div><div class="col-12 col-md-2 form-group"><label>Expiry Date*</label><input type="text" name="expire_at[]" id="expire_at'+id+'" value="" class="form-control expire_at flatpickr-input active" placeholder="Enter Expiry Date" readonly="readonly"></div> <div class="col-12 col-md-1 form-group"><button class="btn btn-info addbtn" type="button" style="margin-top: 35px;" onclick="AddRow()">+</button> </div><div class="col-12 col-md-1 form-group"> <button class="btn btn-danger minusbtn" type="button" style="margin-top: 35px;" onclick="DeletRow('+id+')">-</button></div> </div> ');
    
    $('#alcohol_id'+id).select2();

    flatpickr(document.getElementById('expire_at'+id),{
        dateFormat:"d-m-Y",
        minDate: "today"
    });

      id++;

}







$('#box').on('change', '.alcohol_list', function() {


    var selectedOption = $(this).val();
    var rowNumber = $(this).data('row');

    $.post('<?= BASEURL ?>Purchase/GetItemPrice/',{item_id:selectedOption},function(response){ 

            var unitPriceInput = $(".dynamic_input_div").find("[data-uprice_cnt='" + rowNumber + "']");
                unitPriceInput.val(response);
        
    });
});

function DeletRow(id)
{

  $('#row'+id).remove();

  
}

function validateUnit(input) {

    return false;
    
    $('#unit-error').text('');
    var unit = $(input).val();
    var name = $(input).closest('form').find('select[name="name[]"]').val();
    var unit_price = $(input).closest('form').find('select[name="unit_price[]"]').val();
    if(name){
        if(unit==''){
            return false;
        }

        var data = {
            unit: unit,
            name: name,
            unit_price: unit_price
        };
        $.ajax({
            type: 'post',
            url: '<?=BASEURL;?>Purchase/getItemPriceQty/', 
            data: data,
            success: function (response) {
                newResp = JSON.parse(response);
                // Handle the response from the server
                if (newResp['status'] == 'error') {
                    $('#unit-error').text(newResp['response']);
                    return false;
                } else {
                    $('#unit-error').text('');
                }
            },
            error: function () {
                console.log('AJAX request failed');
            }
        });
    }else{
        $('#unit-error').text('Select Product Name');
    }
}

function validateUnit_dynamic(input) {

    return false;
    
    var unit = $(input).val();
    var nameSelect = $(input).closest('.dynamic_input_div').find('select[name="name[]"]');
    var unit_price = $(input).closest('.dynamic_input_div').find('input[name="unit_price[]"]').val();
    var errorSpan = $(input).closest('.dynamic_input_div').find('.error-message');
    errorSpan.text('');
    if (nameSelect.val()) {
        if(unit==''){
         return false;
        }
        // Prepare data for AJAX request
        var data = {
            unit: unit,
            name: nameSelect.val(),
            unit_price: unit_price
        };

        $.ajax({
            type: 'post',
            url: '<?= BASEURL; ?>Purchase/getItemPriceQty/',
            data: data,
            success: function (response) {
                var newResp = JSON.parse(response);
                if (newResp['status'] == 'error') {
                    // Show error message in the same row
                    errorSpan.text(newResp['response']);

                } else {
                    errorSpan.text('');
                }
            },
            error: function () {
                console.log('AJAX request failed');
            }
        });
    } else {
        // Show error message if product is not selected
        $(input).closest('.dynamic_input_div').find('.error-message').text('Select Product Name');
    }
}
$('#save').click(function(){
       $('#unit-error').text('');
        var postdata = $('#formValidation').serialize();

        

        $.ajax({
            url: '<?=BASEURL;?>Purchase/Addpurchase/', 
            data: postdata,                         
            type: 'post',
            success: function(response){ 
           newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
                 {
                 openSuccess(newResp['response'],'<?=BASEURL;?>Purchase/Create/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
        });  

        return false; 
});





var result = new Array();

/*function getSelectValues(select,id) {
    
    var value = select;
    var id    = id
    const elementExists = result.includes(value);

    if (!elementExists) {

    result.push(value);
    }
    else{

       document.getElementById("name"+id).value = '';
       loadingoverlay('error','error','This item already selected');

    }
}*/



function changeType(type){
    if(type.value ==1){
        $('.type_label').text("Select Room");
    }
    if(type.value ==2){
        $('.type_label').text("Select Table");
    }

    data = new FormData();
    data.append('type', type.value);

    $.ajax({
        url: '<?=BASEURL;?>Purchase/getRoomTable', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
            $('#room_id').html(""); 
            $('#room_id').html(response);
            return false;
        }
    }); 

}


var f1 = flatpickr(document.getElementById('expire_at'),{
    dateFormat:"d-m-Y",
    minDate: "today"
});

</script>
