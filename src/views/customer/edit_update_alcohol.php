<?php 
use inc\Root;
$this->mainTitle = 'Edit';
$this->subTitle  = 'Edit';
$volumeArr = ['0'=>'0%','25'=>'25%','50'=>'50%','75'=>'75%','100'=>'100%'];

?>

<style type="text/css">
  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }
  .form-control{
    height: 50px;
  }
  #box{ border-style:solid;border-width: 1px; border-color: green;padding: 10px; }
  
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
                            <li class="breadcrumb-item"><a href="<?=BASEURL;?>Purchase/CustomerAlcohol">Customer Alcohol</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                        </ol>
                    </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form" method="post">
                        <div class="info">
                            <div id="">
                                <div class="row">
                                    <div class="col-12 col-md-8 form-group">
                                        <label>Alcohol</label>
                                        <select class="form-control custom-select langSel" name="name" id="name" disabled>
                                            <option value="">Select Alcohol</option>
                                            <?php
                                            foreach ($inventory as $key => $value) {
                                            $selected= ($value['id'] == $data['inv_id']) ? "selected" : "";
                                            echo '<option value="'.$value['id'].'" '.$selected.'>'.html_entity_decode(html_entity_decode($value['name'])).'</option>';
                                            }
                                            ?>
                                        </select>                                    
                                    </div>
                                </div>
                                  <div class="row">
                                    <div class="col-12 col-md-8 form-group">
                                        <label>Volume</label>
                                        <select class="form-control custom-select" name="volume" id="volume">
                                            <option value="">Select Volume</option>
                                            <?php foreach($volumeArr as $key=>$val){
                                            $selected = $key == $data['volume'] ? 'selected' : '' ?>
                                                <option value="<?=$key?>" <?= $selected ?>><?=$val?></option>
                                            <?php } ?>

                                        </select>                                    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-8 form-group">
                                        <label>Expiry Date</label>
                                        <input type="text" name="expiry" id="date1" class="form-control" placeholder="Enter Expiry" value="<?=$data['expiry_date'];?>" >                                 
                            </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="edit_id" id="edit_id" value="<?=$data['edit_id'];?>">
                                <div class="col-md-12  text-center" style="margin-top: 2%;">
                                    <button class="btn btn-primary proceedToPayment col-12 col-md-5" id="save" type="button">Submit</button>
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
  $('#accordionExample').find('li a').attr("data-active","false");
  $('#PurchaseMenu').attr("data-active","true");
  $('#PurchaseNav').addClass('show');
  $('#customer_alcohol').addClass('active');

flatpickr(document.getElementById('date1'),{
    dateFormat: "d-m-Y",
    altInput: true,
    altFormat: "d-m-Y"

});
  
$('#save').click(function(){
    var postdata = $('#formValidation').serialize();
    $.ajax({
        url: '<?=BASEURL;?>Customer/UpdateAlcohol/', 
        data: postdata,                         
        type: 'post',
        success: function(response){ 
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){
                 openSuccess(newResp['response'],'<?=BASEURL;?>Purchase/CustomerAlcohol')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    });  
    return false; 
});




</script>
