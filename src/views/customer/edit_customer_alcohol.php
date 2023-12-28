<?php 
use inc\Root;
$this->mainTitle = 'Edit';
$this->subTitle  = 'Edit';

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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Customer/CustomerAlcoholList/">Customer Alcohol</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form" method="post">
                            <div class="info">
                                
                                      <div id="box">
                                        
                                          <div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Alcohol</label>
                                                <select class="form-control custom-select langSel" name="name" id="name">
                                                         <option value="">Select Alcohol</option>
                                                         <?php
                                                         foreach ($inventory as $key => $value) {
                                                          $selected= ($value['id'] == $data['inventory_id']) ? "selected" : "";
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
                                                        <?php 
                                                        $selected1 = ($key == $data['inventory_id']) ? "selected" : "";
                                                        ?>
                                                         <option value="">Select Volume</option>
                                                         <option value="25">25%</option>
                                                         <option value="50">50%</option>
                                                         <option value="75">75%</option>
                                                         <option value="100">100%</option>
                                                            
                                                </select>                                    
                                            </div>

                                             
                                          </div>
                                          <!--<div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Quantity</label>
                                                <input type="number" name="quantity" id="quantity" value="<?=$data['quantity'];?>" class="form-control" placeholder="Enter Quantity">                                 
                                            </div>

                                             
                                          </div>-->
                                          <div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Expiry Date</label>
                                                <input type="text" name="expiry" id="date1" class="form-control" placeholder="Enter Expiry" value="<?=date("d-m-Y",strtotime($data['expiry_date']));?>" >                                 
                                            </div>
                                            
                                           
                                             
                                          </div>
                                          <div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Balance</label>
                                                <input type="number" name="balance" id="balance"  class="form-control" placeholder="Enter Balance" value="<?=$data['balance'];?>" >                                 
                                            </div>

                                             
                                          </div>
                                          
                                          </div>
                                          <div class="row">
                                           <input type="hidden" name="edit_id" id="edit_id" value="<?=$data['id'];?>">
                                          
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
  $('#custMenu').attr("data-active","true");
  $('#custNav').addClass('show');
  $('#alcohol').addClass('active');
   //$('#BrandMenu').attr("data-active","true");
  var f1 = flatpickr(document.getElementById('date1'),{
      dateFormat:"d-m-Y",
      minDate: "today"
    });

$("#volume > [value=" + '<?=$data['volume_percent'];?>' + "]").attr("selected", "true");
  
$('#save').click(function(){
   
        var postdata = $('#formValidation').serialize();

        $.ajax({
            url: '<?=BASEURL;?>Customer/AlcoholUpdateCustomer/', 
            data: postdata,                         
            type: 'post',
            success: function(response){ 
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
                 {
                 openSuccess(newResp['response'],'<?=BASEURL;?>Customer/CustomerAlcoholList/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
        });  

        return false; 
});




</script>
