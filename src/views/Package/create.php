<?php 
use inc\Raise;
$this->mainTitle = 'Package';

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
<link href="<?=WEB_PATH?>plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet">
<script src="<?=WEB_PATH?>plugins/file-upload/file-upload-with-preview.min.js"></script>
<script src="<?=WEB_PATH?>ckeditor/ckeditor.js"></script>
<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                  <nav class="breadcrumb-two" aria-label="breadcrumb">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Package/Index/">Package</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">
                                       
                                            
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Amount*</label>
                                                   <input type="text"  name="amount" id="amount"  class="form-control" value="<?=(isset($Package['amount'])) ? $Package['amount'] : ''?> ">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Percentage*</label> 
                                                 <input type="text"  name="percentage" id="percentage"  class="form-control" value="<?=(isset($Package['percentage'])) ? $Package['percentage'] : ''?> ">

                                            </div>
                                            <!-- <div class="col-12 form-group">
                                                <label>Description</label> 
                                                <textarea name="descriptions" id="descriptions" rows="1" class="form-control"></textarea>
                                                 <div id = "myDiv" style="display:none"><?=(isset($Package['descriptions'])) ? $Package['descriptions'] : ''?></div>

                                            </div>-->

                                            <div class="col-6">
                                                <label>Description</label> 
                                                <input type="text"  name="descriptions" id="descriptions"  maxlength="50" class="form-control" value="<?=(isset($Package['descriptions'])) ? $Package['descriptions'] : ''?> ">

                                            </div>

                                             <?php if(!empty($Package['id'])){
                                                $select1 = $Package['status']==0 ? 'selected' : '';
                                                $select2 = $Package['status']==1 ? 'selected' : '';
                                                $select3 = $Package['status']==2 ? 'selected' : '';
                                              }else{
                                                $select1 = $select2 =  $select3 = '';
                                              }
                                            ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Status</label>      
                                                    <select class="form-control custom-select" id="status" name="status">
                                                        <option  value="0" <?=$select1?> >Hidden</option>
                                                        <option value="1" <?=$select2?> >Published</option>
                                                         <option value="2" <?=$select3?> >Sold</option>
                                                    </select>
                                                </div>
                                            </div>
                                          

                                           
                                          
                                            <input type="hidden" id="editID" value="<?=isset($Package['id']) ? $Package['id'] : ''?>">

                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                
                                                <button class="btn btn-primary" id="save" type="button">Submit</button>
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


<script type="text/javascript">
    $('#accordionExample').find('li a').attr("data-active","false");
    $('#settingsMenu').attr("data-active","true");
    $('#settingsNav').addClass('show');
    $('#package').addClass('active');

  
$('#save').click(function(){
   
 data = new FormData();
 data.append('amount', $('#amount').val());
 data.append('percentage', $('#percentage').val());
 data.append('editID', $('#editID').val());
 data.append('status', $('#status').val());
 data.append('descriptions',$('#descriptions').val()); ;

$.ajax({
        url: '<?=BASEURL;?>Package/Add/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
        // alert(response);
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){
               openSuccess(newResp['response'],'<?=BASEURL;?>Package/Index/')  
            }else{
               loadingoverlay('error','error',newResp['response']);
            }
           return false;
        }
    }); 
});


</script>
