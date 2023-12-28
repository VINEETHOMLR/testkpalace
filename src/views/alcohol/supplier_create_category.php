<?php 
use inc\Root;
$row_cnt = !empty($contact_no) ? count(json_decode($contact_no)) : '1';
?>

<style type="text/css">
  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }
  .form-control{
    height: 50px;
  }
  
@media only screen and (max-width: 900px) {
  .info-icon {
     margin-top: -8px !important;
     margin-left: 32%;
  }
}
.card-body{
  width: 70%;margin: 0 auto;
}
 @media only screen and (min-width: 600px) and (max-width: 1000px)  {
  .card-body{
    width: 100%;margin: 25px;
  }
}
.breadcrumb-two .breadcrumb li a::before {
    content: none;
  }

  .breadcrumb-two{
        top: 0;
        position: absolute;
        left: 0;
  }
  .card-block{
      border: 1px solid #0ab910;
      padding: 20px;
      border-radius: 5px;
      margin: 10px;
  }
  #thisAdd{
      height: 100px;
      margin-left: 30px;
      margin-top: 10px
  }
  .remove{
      position: absolute;
      top: 0px;
      right: 5px;
      font-weight: bold;
      color: red;
  }
</style>
<link href="<?=WEB_PATH?>assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />
  <link href="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
  <link href="<?=WEB_PATH?>plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet">

<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=BASEURL;?>Alcohol/SupplierList/">Inventory Supplier</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                        </ol>
                    </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                        <div class="info">
                            <div class="row col-md-11 mt-4 mb-4">
                                <div class="col-5 col-md-5 form-group">
                                    <label>Company Name<span style="color:red;">*</span></label>
                                    <input type="text" name="name" id="name" value="<?php if(!empty($supplier_name)){echo htmlspecialchars_decode($supplier_name);}?>" class="form-control" placeholder="Enter Company Name">
                                </div>

                                <div class="col-5 col-md-5 form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" id="email" value="<?php if(!empty($email)){echo htmlspecialchars_decode($email);}?>" class="form-control" placeholder="Enter Email">
                                </div>
                                <div id="box" class="col-12 col-md-12 form-group">
                                        <?php
                                            if (!empty($contact_no)) {
                                                foreach (json_decode($contact_no) as $key => $val) {
                                                     $c_name = json_decode($contact_person);
                                                    $c_no = json_decode($contact_no);
                                                    ?>
                                                <div class="row dynamic_input_div" id="row<?= $key+1 ?>">
                                                    <div class="col-12 col-md-5 form-group">
                                                        <label>Contact Person<span style="color:red;">*</span></label>
                                                        <input type="text" name="contact_person[]" id="contact_person" value="<?= $c_name[$key] ?>" class="form-control" placeholder="Enter Contact Person">
                                                    </div>

                                                    <div class="col-12 col-md-5 form-group">
                                                        <label>Contact Number<span style="color:red;">*</span></label>
                                                        <input type="text" name="contact_no[]" id="contact_no1" value="<?= $c_no[$key] ?>" class="form-control" placeholder="Enter Contact Number">
                                                    </div>

                                                    <div class="col-12 col-md-2 form-group">
                                                        <button class="btn btn-info addbtn" type="button" style="margin-top: 35px;" onclick="AddRow()">+</button>
                                                        <?php if ($key > 0) { ?>
                                                            <button class="btn btn-danger minusbtn" type="button" style="margin-top: 35px;" onclick="DeletRow(<?= $key+1 ?>)">-</button>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php }
                                            } else { // Display HTML for the first row when $contact_no is empty ?>
                                            <div class="row dynamic_input_div" id="row1">
                                                <div class="col-12 col-md-5 form-group">
                                                    <label>Contact Person<span style="color:red;">*</span></label>
                                                    <input type="text" name="contact_person[]" id="contact_person" value="" class="form-control" placeholder="Enter Contact Person">
                                                </div>

                                                <div class="col-12 col-md-5 form-group">
                                                    <label>Contact Number<span style="color:red;">*</span></label>
                                                    <input type="text" name="contact_no[]" id="contact_no1" value="" class="form-control" placeholder="Enter Contact Number">
                                                </div>

                                                <div class="col-12 col-md-2 form-group">
                                                    <button class="btn btn-info addbtn" type="button" style="margin-top: 35px;" onclick="AddRow()">+</button>
                                                </div>
                                            </div>
                                            <?php } ?>

                                </div>
                                        

                                <input type="hidden" name="countID" id="countID" value="1">
                                <input type="hidden" name="editID" id="editID" value="<?=$id?>">
                              
                                <div class="col-md-12  text-center" style="margin-top: 2%;">
                                    <button class="btn btn-primary proceedToPayment col-2 col-md-2" id="save" type="button">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>


<script type="text/javascript">

//$('#BrandMenu').attr("data-active","true");
$('#accordionExample').find('li a').attr("data-active","false");
$('#AlcoholMenu').attr("data-active","true");
$('#AlcoholNav').addClass('show');
$('#SupplierList').addClass('active');

$('#save').click(function(){
    var data = $('#formValidation').serialize();
    loadingoverlay('info',"Please Wait...","Loading....");
    $.ajax({
        url: '<?=BASEURL;?>Alcohol/AddSupplier/', 
        dataType: 'text',  
        cache: false,
        //contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
                 {
                 openSuccess(newResp['response'],'<?=BASEURL;?>Alcohol/SupplierList/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 
});

var id = <?=$row_cnt?>;

function AddRow() {
    if (id < 3) {
        id++;
    } else {
        loadingoverlay('error', 'error', 'You have exceeded the maximum number of contact details');
        return false;
    }

    $('#box').append('<div class="row dynamic_input_div" id="row' + id + '">\
        <div class="col-12 col-md-5 form-group">\
            <label>Contact Person<span style="color:red;">*</span></label>\
            <input type="text" name="contact_person[]" id="contact_person' + id + '" value="" class="form-control" placeholder="Enter Contact Person">\
        </div>\
        <div class="col-12 col-md-5 form-group">\
            <label>Contact Number<span style="color:red;">*</span></label>\
            <input type="text" name="contact_no[]" id="contact_no' + id + '" value="" class="form-control" placeholder="Enter Contact Number">\
        </div>\
        <div class="col-12 col-md-1 form-group">\
            <button class="btn btn-info addbtn" type="button" style="margin-top: 35px;" onclick="AddRow()">+</button>\
        </div>\
        <div class="col-12 col-md-1 form-group">\
            <button class="btn btn-danger minusbtn" type="button" style="margin-top: 35px;" onclick="DeletRow(' + id + ')">-</button>\
        </div>\
    </div>');
}

function DeletRow(deleteId) {
    $('#row' + deleteId).remove();
    id = id - 1;
}



</script>
