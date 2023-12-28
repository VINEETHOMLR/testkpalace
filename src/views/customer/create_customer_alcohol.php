<?php 
use inc\Root;
$this->mainTitle = 'Create';
$this->subTitle  = 'Create';

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
                                <div class="row">

                                  <div class="col-12 col-md-8 form-group">
                                    <label>Select Username</label>
                                    
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                  </div>
                                </div>
                                      <div id="box">
                                        
                                          <div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Alcohol</label>
                                                <select class="form-control custom-select langSel" name="name[]" id="name">
                                                         <option value="">Select Alcohol</option>
                                                         <?php
                                                         foreach ($inventory as $key => $value) {
                                                          echo '<option value="'.$value['id'].'">'.html_entity_decode(html_entity_decode($value['name'])).'</option>';
                                                         }
                                                         ?>
                                                            
                                                </select>                                    
                                            </div>

                                             
                                          </div>
                                          <div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Volume</label>
                                                <select class="form-control custom-select" name="volume[]" id="volume">
                                                         <option value="">Select Volume</option>
                                                         <option value="25">25%</option>
                                                         <option value="50">50%</option>
                                                         <option value="75">75%</option>
                                                         <option value="100">100%</option>
                                                            
                                                </select>                                    
                                            </div>

                                             
                                          </div>
                                         <!-- <div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Quantity</label>
                                                <input type="number" name="quantity[]" id="quantity" value="" class="form-control" placeholder="Enter Quantity">                                 
                                            </div>

                                             
                                          </div>-->
                                          <div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Expiry Date</label>
                                                <input type="text" name="expiry[]" id="date1" value="" class="form-control" placeholder="Enter Expiry">                                 
                                            </div>
                                            
                                           
                                             
                                          </div>
                                          <div class="row">

                                            <div class="col-12 col-md-8 form-group">
                                                <label>Balance</label>
                                                <input type="number" name="balance[]" id="balance" value="" class="form-control" placeholder="Enter Balance">                                 
                                            </div>

                                             
                                          </div>
                                          <div class="row">
                                              <div class="col-12 col-md-4 form-group">

                                                <button class="btn btn-info addbtn" type="button" style="margin-top: 35px;" onclick="addrow()">+</button>                               
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
  $('#custMenu').attr("data-active","true");
  $('#custNav').addClass('show');
  $('#alcohol').addClass('active');
   //$('#BrandMenu').attr("data-active","true");
  var f1 = flatpickr(document.getElementById('date1'),{
      dateFormat:"d-m-Y",
      minDate: "today"
    });
  id=2;
  function addrow() {

    var inv = '<?=$inventory_more;?>'
    var al_name_html = $(".langSel").html();
    $("#box").append('<div class="row row'+id+'" ><div class="col-12 col-md-8 form-group"><label>Alcohol Name</label> <select class="form-control custom-select langSel" name="name[]" id="name">'+al_name_html+'</select></div></div><div class="row row'+id+'"><div class="col-12 col-md-8 form-group"><label>Volume</label><select class="form-control custom-select" name="volume[]" id="volume"><option value="">Select Volume</option><option value="25">25%</option><option value="50">50%</option><option value="75">75%</option><option value="100">100%</option></select></div></div><!--<div class="row row'+id+'"><div class="col-12 col-md-8 form-group"><label>Quantity</label><input type="text" name="quantity[]" id="quantity" value="" class="form-control" placeholder="Enter Quantity"></div></div>--><div class="row row'+id+'"><div class="col-12 col-md-8 form-group"><label>Expiry Date</label><input type="text" name="expiry[]" id="date'+id+'" value="" class="form-control" placeholder="Enter Expiry"></div> </div><div class="row row'+id+'"><div class="col-12 col-md-8 form-group"><label>Balance</label><input type="number" name="balance[]" id="balance" value="" class="form-control" placeholder="Enter Balance"></div></div><div class="row row'+id+'"><div class="col-12 col-md-4 form-group"><button class="btn btn-info row'+id+'" type="button"  style="margin-top: 35px;" onclick="addrow();">+</button><button class="btn btn-danger row'+id+'" type="button"  style="margin-top: 35px;" onclick="DeletRow('+id+');">-</button></div></div></div>');

     var f2 = flatpickr(document.getElementById('date'+id),{
      dateFormat:"d-m-Y",
       minDate: 0,
    });
     id++;

  }

  function DeletRow(id)

{

 $('.row'+id).remove();
//DeletRow
  
}
$('#save').click(function(){
   
        var postdata = $('#formValidation').serialize();

        

        $.ajax({
            url: '<?=BASEURL;?>Customer/AddInv/', 
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
