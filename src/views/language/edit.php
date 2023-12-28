<?php 
use inc\Root;
$this->mainTitle = '  Announcement';
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
<link href="<?=WEB_PATH?>plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet">
<script src="<?=WEB_PATH?>plugins/file-upload/file-upload-with-preview.min.js"></script>

<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                  <nav class="breadcrumb-two" aria-label="breadcrumb">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Announcement/Index/">Announcement</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">

                                          <div class="form-group col-12 col-md-6">
                                                  <label>Include User</label>
                                                  <select class="form-control tagging" multiple="multiple" name="include_user[]" id="include_user" >
                                                    <?=$include_user?>
                                                  </select>
                                            </div>

                                            <div class="form-group col-12 col-md-6">
                                                  <label>Exclude User</label>
                                                  <select class="form-control tagging" name="exclude_user[]" multiple="multiple" id="exclude_user">
                                                    <?=$exclude_user?>
                                                  </select>
                                            </div>

                                            <div class="form-group col-12">
                                                <div class="n-chk">
                                                    <label class="new-control new-checkbox checkbox-primary" style="color:#515365;">
                                                        <input type="checkbox" name="include_downline" id="include_downline" class="new-control-input" value="1">
                                                        <span class="new-control-indicator"></span>&nbsp;Get Downline?
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group col-12 col-md-6">
                                                  <label>Include Country</label>
                                                  <select class="form-control tagging" multiple="multiple" name="include_country[]" id="include_country" <?=!empty($exclude_country) ? 'disabled' : ''; ?>>
                                                    <?=$include_country?>
                                                  </select>
                                            </div>

                                            <div class="form-group col-12 col-md-6">
                                                  <label>Exclude Country</label>
                                                  <select class="form-control tagging" name="exclude_country[]" multiple="multiple" id="exclude_country" <?=!empty($include_country) ? 'disabled' : ''; ?>>
                                                    <?=$exclude_country?>
                                                  </select>
                                            </div>
                                            
                                          <?php foreach ($announcement as $key => $value) { 
                                                    $divCount = $key +1; 
                                                    $embed    = empty($value['video_embed']) ? '' : 'checked';
                                                    $vdo      = empty($value['filename_video']) ? '' : 'checked';

                                                    $embed_div = empty($value['video_embed']) ? 'display:none' : '';
                                                    $vdo_div   = empty($value['filename_video']) ? 'display:none' : '';

                                                    $fileImage = ($value['file_type'] ==2) ? 'display:none' : '';
                                                    $fileVd    = ($value['file_type'] ==1) ? 'display:none' : '';
                                          ?>
                                            
                                            <div class="card-block col-5" id="annDiv<?=$divCount?>"> 
                                                <?php if($divCount !=1){
                                                    echo '<button type="button" class="close remove" onclick=remove('.$divCount.')>&times;</button>';
                                                }?>
                                                <div class="form-group">
                                                   <label>Subject*</label>
                                                   <input type="text"  name="title<?=$divCount?>" id="title<?=$divCount?>"  class="form-control" value="<?=$value['title']?>">
                                                </div>

                                                <div class="form-group">
                                                   <label>Description*</label>
                                                   <input type="text"  name="discription<?=$divCount?>" id="discription<?=$divCount?>"  class="form-control" value="<?=$value['message']?>">
                                                </div>

                                                <div class="form-group">
                                                   <label>File Type</label><br>
                                                   <select name="file_type1" id="file_type<?=$divCount?>" class="form-control" onchange="showFile($(this).val(),<?=$divCount?>)">
														<?php  foreach ($this->imagetype as $key => $filetype) {
                                                                      
                                                                echo '<option value="'.$key.'" '.($value['file_type'] ==1) ? 'selected' : '' .' >'.$filetype.'</option>';
                                                            } ?>
												   
                                                       <option value="1" <?=($value['file_type'] ==1) ? 'selected' : '';?> >PDF/Image</option>
                                                       <option value="2" <?=($value['file_type'] ==2) ? 'selected' : '';?>>Video</option>
                                                   </select>
                                                </div>
                                                
                                                <div class="form-group fileImage<?=$divCount?>" style="<?=$fileImage?>" >
                                                   <label><?=Root::t('announcement','pdf_upload')?> (PDF,Image) </label><br>
                                                   <input type="file" name="filename<?=$divCount?>"  class="" id="filename<?=$divCount?>" accept="application/pdf, image/*" onchange="loadFile(<?=$divCount?>,event)">
                                                   <div class="col-12" id="gallery">
                                                      <img id="preview<?=$divCount?>" width="100%" height="200px" style="margin-top:15px;display: none; "/>
                                                   <?php 
                                                      if(!empty($value['filename'])){

                                                         $img_src = in_array(pathinfo($value['filename'], PATHINFO_EXTENSION),['pdf']) ? FrontEnd.'web/images/announcement-icon/pdf.png' : BASEURL.'web/upload/announcements/'.$value['filename'];

                                                       echo '<div class="col-12 removePics'.$divCount.'" style="margin-top:15px ">
                                                                 <span class="custom-file-container__image-multi-preview__single-image-clear" onclick="removePics(\''.$divCount.'\',event)" style="position:absolute;right: 7px !important;left:auto">
                                                                        <span class="custom-file-container__image-multi-preview__single-image-clear__icon">x</span></span>
                                                                 <a href="'.BASEURL.'web/upload/announcements/'.$value['filename'].'" target="_blank"><img src="'.$img_src.'" width="100%" height="200px"></a>
                                                             </div>';
                                                      }
                                                    ?>
                                                   </div>
                                                </div>


                                                <div class="custom-control custom-radio custom-control-inline fileVideo<?=$divCount?>" style="margin-bottom: 10px;<?=$fileVd?>">
                                                   <input type="radio" id="e_video<?=$divCount?>" name="customRadioInline<?=$divCount?>" class="custom-control-input radioCheck" value="embed_file<?=$divCount?>" onclick="showThis(1,<?=$divCount?>)" <?=$embed?> >
                                                   <label class="custom-control-label" for="e_video<?=$divCount?>" >Embeded Video</label>
                                                </div>

                                                <div class="custom-control custom-radio custom-control-inline fileVideo<?=$divCount?>" style="margin-bottom: 10px;<?=$fileVd?>">
                                                   <input type="radio" id="v_video<?=$divCount?>" name="customRadioInline<?=$divCount?>" class="custom-control-input radioCheck" value="video_file<?=$divCount?>" onclick="showThis(2,<?=$divCount?>)" <?=$vdo?>>
                                                   <label class="custom-control-label" for="v_video<?=$divCount?>">Video File</label>
                                                </div>
                                                
                                                <div class="form-group embed_file<?=$divCount?> box_file<?=$divCount?>" style="<?=$embed_div?>">
                                                   <label>Embeded Video URL. Eg:(https://www.youtube.com/embed/tgbNymZ7vqY)</label>
                                                   <input type="text" name="video_embed<?=$divCount?>"  class="form-control" id="video_embed<?=$divCount?>" value="<?=$value['video_embed']?>" >                                                  
                                                </div>

                                                <div class="form-group video_file<?=$divCount?> box_file<?=$divCount?>" style="<?=$vdo_div?>">
                                                   <label><?=Root::t('announcement','pdf_upload')?> (mp4,avi,3gp,mov,mpeg) </label><br>
                                                   <input type="file" name="filename_video<?=$divCount?>" id="filename_video<?=$divCount?>" accept="video/mp4,video/avi,video/3gp,video/mov,video/mpeg">
                                                   <input type="hidden" name="temp_filename_video"  class="form-control" id="temp_filename_video" value="" >
                                                </div>

                                                <div class="form-group">
                                                   <label><?=Root::t('announcement','Language')?>*</label>
                                                      <select class="form-control custom-select langSel" name="language<?=$divCount?>" id="language<?=$divCount?>" onchange="getCategory(this.options[this.selectedIndex].value,<?=$divCount?>)">
                                                         <option value=""><?=Root::t('announcement','Language_text');?></option>
                                                            <?php  foreach ($LanguageArray as $language) {
                                                                      $checked = ($value['lang_code']==$language['lang_code']) ? 'selected' : '';
                                                                echo '<option value="'.$language['lang_code'].'" '.$checked.'>'.$language['lang_name'].'</option>';
                                                            } ?>
                                                      </select> 
                                                </div>

                                                <div class="form-group">
                                                   <label><?=Root::t('announcement','category')?>*</label>
                                                   <select class="form-control custom-select" name="category<?=$divCount?>" id="category<?=$divCount?>">
                                                       <option value=""><?=Root::t('announcement','category_text');?></option>
                                                        <?=$value['cat_html']?>
                                                   </select>      
                                                </div>

                                            </div>

                                            <?php } ?>

                                            <div id="thisAdd" class="statbox widget box box-shadow col-12 col-md-2 text-center" onclick="createHtml()">
                                               <a href="javascript:void(0)" class="full_width div_float">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg><br>Add
                                               </a>
                                           </div>

                                            <br>
                                            <div class="row col-12" style="margin-top: 15px">
                                            <div class="col-md-6" id="file_image_thumb">
                                                <div class="form-group">
                                                    <label>Upload Thumbnail Image </label>
                                                    <input type="file" name="thumb_nail"  class="form-control" id="thumb_nail" accept="image/*" onchange="loadNail(event)">
                                                    <img id="nailPreview" width="100%" height="200px" style="margin-top:15px;display: none; "/>
                                                    <div class="col-12 removeNail" style="margin-top:15px ">
                                                      <span class="custom-file-container__image-multi-preview__single-image-clear" onclick="$('.removeNail').remove()" style="position:absolute;right: 7px !important;left:auto">
                                                      <span class="custom-file-container__image-multi-preview__single-image-clear__icon">x</span></span>
                                                      <a href="<?=BASEURL?>web/upload/announcements/thumb/<?=$announcement[0]['thumb_nail']?>" target="_blank"><img src="<?=BASEURL?>web/upload/announcements/thumb/<?=$announcement[0]['thumb_nail']?>" width="100%" height="200px"></a>
                                                    </div>';
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Slug</label>
                                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Enter Slug" value="<?=$announcement[0]['slug']?>">                                 
                                            </div>

                                            <div class="col-12">
                                                <label>Status</label>
												
                                                <div class="row">
													<?php  foreach ($this->statusArry as $key => $status) {?>
                                                    <div class="form-check mb-2">
                                                        <div class="custom-control custom-radio classic-radio-info">
                                                            <input type="radio" id="hRadio<?=$key?>" name="c_status" class="custom-control-input" value="<?=$key?>" <?=($key==$announcement[0]['status'])? 'checked' : '' ?>>
                                                            <label class="custom-control-label" for="hRadio<?=$key?>"><?=$status?></label>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                                     
                                                </div>   
                                            </div>
                                          </div>
                                           <input type="hidden" name="countID" id="countID" value="<?=count($announcement)?>">
                                          
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
<?php

$error_dash = Root::t('app','error_dash');
$success    = Root::t('app','suucess_txt');
$okay       = Root::t('app','okay_btn'); 

?>

<script type="text/javascript">

  var include_downline     = '<?=$include_downline?>';

  if(include_downline==1){
     $('#include_downline').prop('checked',true);
  }

  function showThis(type,count){

     let file_type = (type==1) ? 'embed_file' : 'video_file';

     if(type==1)
        $('#video_embed'+count).val('');
     else
        $('#filename_video'+count).val('');

     $(".box_file"+count).hide();
     $('.'+file_type+count).show();
  }

   
    $('#accordionExample').find('li a').attr("data-active","false");
    $('#AnnouncementMenu').attr("data-active","true");
    $('#AnnouncementNav').addClass('show');
    $('#Announcement').addClass('active');

function getCategory(val,type){

   $.post('<?=BASEURL?>Announcement/getCategories',{'code':val},function(response){
      $('#category'+type).html(response);
      return true;
  });
}
     
$('#save').click(function(){

    data = new FormData();

    data.append('totalCount', createDivcount);
    data.append('status', $('input[name="c_status"]:checked').val());
    data.append('slug', $('#slug').val());
    data.append('removedID', removedID);
    data.append('editID', "<?=$log_id?>");
    data.append('include_user', $('#include_user').val());
    data.append('exclude_user', $('#exclude_user').val());
    data.append('include_country', $('#include_country').val());
    data.append('exclude_country', $('#exclude_country').val());

    if ($('#include_downline').is(":checked"))
    {
      data.append('include_downline', 1);
    }else{
      data.append('include_downline', 0);
    }

    for (var i = 1; i <= createDivcount; i++) {

      if ($('#language'+i).length){

       data.append('category'+i, $('#category'+i).val());
       data.append('language'+i, $('#language'+i).val());
       data.append('title'+i, $('#title'+i).val());
       data.append('description'+i, $('#discription'+i).val());
       data.append('fileType'+i, $('#file_type'+i).val());
       data.append('videoType'+i, $('input[name="customRadioInline'+i+'"]:checked').val());

       data.append('filename'+i, $('#filename'+i)[0].files[0]);
       data.append('filename_video'+i, $('#filename_video'+i)[0].files[0]);
       data.append('video_embed'+i, $('#video_embed'+i).val());

      }
    }
   
    data.append('thumb_nail', $('#thumb_nail')[0].files[0]);
 
    loadingoverlay('info',"<?=Root::t('announcement','load1_txt');?>","<?=Root::t('announcement','load2_txt');?>");
    $.ajax({
        url: '<?=BASEURL;?>Announcement/Add/', 
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
                 openSuccess(newResp['response'],'<?=BASEURL;?>Announcement/Index/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 
});

var createDivcount = <?=count($announcement)?>;
var language       = <?=json_encode($LanguageArray)?>;
var selectedLang   = <?=json_encode(array_column($announcement, 'lang_code'))?>;
var removedID      = [];
var removedFile    = false;

function createHtml(){

     langUpdate();

     let lang        = '';

     jQuery.each(language, function(index, item) {
        lang += '<option value="'+item['lang_code']+'">'+item['lang_name']+'</option>'
     })   
      
     createDivcount ++;

     let radioCount = createDivcount + 1;
     let vdCount    = radioCount + 1;

     var html ='<div class="card-block col-5" id="annDiv'+createDivcount+'">'+ 
                  '<button type="button" class="close remove" onclick=remove("'+createDivcount+'")>&times;</button>'+
                  '<div class="form-group">'+
                      '<label>Subject*</label><input type="text"  name="title'+createDivcount+'" id="title'+createDivcount+'"  class="form-control" value="">'+
                  '</div>'+
                  '<div class="form-group">'+
                      '<label>Description*</label><input type="text"  name="discription'+createDivcount+'" id="discription'+createDivcount+'"  class="form-control" value="">'+
                  '</div>'+
                  '<div class="form-group">'+
                      '<label>File Type</label><br>'+
                      '<select name="file_type'+createDivcount+'" id="file_type'+createDivcount+'" class="form-control" onchange="showFile($(this).val(),'+createDivcount+')">'+
                          '<option value="1">PDF/Image</option><option value="2">Video</option>'+
                      '</select>'+
                  '</div>'+
                  '<div class="form-group fileImage'+createDivcount+'">'+
                      '<label><?=Root::t('announcement','pdf_upload')?> (PDF,Image) </label><br><input type="file" name="filename'+createDivcount+'"  class="" id="filename'+createDivcount+'" accept="application/pdf, image/*" onchange="loadFile('+createDivcount+',event)">'+
                      '<div class="col-12"><img id="preview'+createDivcount+'" width="100%" height="200px" style="margin-top:15px;display: none; "/></div>'+
                  '</div>'+
                  '<div class="custom-control custom-radio custom-control-inline fileVideo'+createDivcount+'" style="display: none;margin-bottom: 10px;">'+
                      '<input type="radio" id="e_video'+createDivcount+'" name="customRadioInline'+createDivcount+'" class="custom-control-input radioCheck" value="embed_file'+createDivcount+'" onclick="showThis(1,'+createDivcount+')">'+
                      '<label class="custom-control-label" for="e_video'+createDivcount+'">Embeded Video</label>'+
                  '</div>'+
                  '<div class="custom-control custom-radio custom-control-inline fileVideo'+createDivcount+'" style="display: none;margin-bottom: 10px;">'+
                      '<input type="radio" id="v_video'+createDivcount+'" name="customRadioInline'+createDivcount+'" class="custom-control-input radioCheck" value="video_file'+createDivcount+'" onclick="showThis(2,'+createDivcount+')">'+
                      '<label class="custom-control-label" for="v_video'+createDivcount+'">Video File</label>'+
                  '</div>'+
                  '<div class="form-group embed_file'+createDivcount+' box_file'+createDivcount+'" style="display:none">'+
                      '<label>Embeded Video URL. Eg:(https://www.youtube.com/embed/tgbNymZ7vqY)</label>'+
                      '<input type="text" name="video_embed'+createDivcount+'"  class="form-control" id="video_embed'+createDivcount+'" value="">'+                                                 
                  '</div>'+
                  '<div class="form-group video_file'+createDivcount+' box_file'+createDivcount+'" style="display:none">'+
                      '<label><?=Root::t('announcement','pdf_upload')?> (mp4,avi,3gp,mov,mpeg) </label><br>'+
                      '<input type="file" name="filename_video'+createDivcount+'" id="filename_video'+createDivcount+'" >'+
                      '<input type="hidden" name="temp_filename_video"  class="form-control" id="temp_filename_video" value="">'+
                  '</div>'+
                  '<div class="form-group">'+
                      '<label><?=Root::t('announcement','Language')?>*</label>'+
                      '<select class="form-control custom-select langSel" name="language'+createDivcount+'" id="language'+createDivcount+'" onchange="getCategory(this.options[this.selectedIndex].value,'+createDivcount+')">'+
                          '<option value=""><?=Root::t('announcement','Language_text');?></option>'+lang+
                      '</select>'+ 
                  '</div>'+
                  '<div class="form-group">'+
                      '<label><?=Root::t('announcement','category')?>*</label>'+
                      '<select class="form-control custom-select" name="category'+createDivcount+'" id="category'+createDivcount+'">'+
                          '<option value=""><?=Root::t('announcement','category_text');?></option>'+
                      '</select>'+      
                  '</div>'+
                '</div>';

      $('#thisAdd').before(html);

      $('#countID').val(createDivcount)

      if (language. length == selectedLang. length+1)
          $('#thisAdd').hide();
  }

  function langUpdate(){

      selectedLang   = [];

      $('.langSel').each(function(){
          selectedLang.push($(this).val());
      });
  }

  function remove(id){
     $('#annDiv'+id).remove();
     $('#thisAdd').show();
     removedID.push(id);
  }

  

  function removePics(id){
     $('.removePics'+id).remove()
     removedFile = true;
  }

  var loadFile = function(divID,event) {
    $('.removePics'+divID).remove()
    var output = document.getElementById('preview'+divID);

    var file     = event.target.files[0];
    var t = file.type.split('/').pop().toLowerCase();
    if (t == "jpeg"|| t == "jpg" || t == "png" || t == "gif") {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
           URL.revokeObjectURL(output.src) // free memory
        }
        $('#preview'+divID).show()
    }else{
        $('#preview'+divID).hide()
    }
  };

  var loadNail = function(event) {
    $('.removeNail').remove()
    var output = document.getElementById('nailPreview');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
    $('#nailPreview').show()
  };

  function showFile(val,ID){

     if(val==1){
        $('.fileImage'+ID).show();
        $('.fileVideo'+ID).hide();
        $('.box_file'+ID).hide();
        $('#video_embed'+ID).val('');
        $('#filename_video'+ID).val('');
        $('#e_video'+ID).prop('checked',false)
        $('#v_video'+ID).prop('checked',false)
     }else{
        $('.fileImage'+ID).hide();
        $('.fileVideo'+ID).show();
        $('#filename'+ID).val('');
     }
  }

</script>
