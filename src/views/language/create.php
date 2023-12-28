<?php 
use inc\Root;
$this->mainTitle = 'Language';
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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Language/">Language</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Language Key*</label>
                                                <?php
                                               if($this->admin_role != 1 && ($language['lang_key'])){
                                                $read = "readonly";
                                               } else{
                                                $read =  "";
                                               }
                                                ?>
                                                <input type="text" name="lang_key" id="lang_key" value="<?php if(!empty($language)){echo $language['lang_key'];}?>" class="form-control" placeholder="Enter Language Key" <?=$read?>>                                 
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Page</label>
                                                <input type="text" name="page" id="page" value="<?php if(!empty($language)){echo $language['page'];}?>" class="form-control" placeholder="Enter Page Name">                                 
                                            </div>

                                            <?php foreach ($LanguageArray as $key => $value) { $Count = $key + 1; ?>
                                            <div class="col-12 col-md-6 form-group">
                                                <label><?=$value['lang_name'].' ('.$value['lang_code'].')'?></label>
                                                <textarea name="<?=$value['lang_code']?>" id="<?=$value['lang_code']?>"  class="form-control" placeholder="Enter Page Name" ><?php if(!empty($language)){echo $language[$value['lang_code']];}?></textarea>                                
                                            </div>
                                               
											<?php } ?>

                                            
                                            <br>
                                            
                                           
                                          
                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                <button class="btn btn-primary proceedToPayment col-12 col-md-5" id="save" type="button"><?=Root::t('announcement','submit')?></button>
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
    $('#langmenu').attr("data-active","true");


  $('#save').click(function(){

    data = new FormData();

    data.append('lang_key'	, $('#lang_key').val());
    data.append('page'		, $('#page').val());
    data.append('editID'	, "<?=$id?>");
  
    <?php foreach ($LanguageArray as $key => $value) { ?>
		data.append('<?=$value['lang_code']?>', $('#<?=$value['lang_code']?>').val());
	<?php }?>

      
 
    loadingoverlay('info',"<?=Root::t('announcement','load1_txt');?>","<?=Root::t('announcement','load2_txt');?>");

    $.ajax({
        url: '<?=BASEURL;?>Language/Add/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
        // alert(response);
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
            {
                 openSuccess(newResp['response'],'<?=BASEURL;?>Language/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 
});

var language       = <?=json_encode($LanguageArray)?>;
var selectedLang   = [];
var removedID      = [];

 

</script>
