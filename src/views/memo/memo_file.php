<?php 
use inc\Root;
$this->mainTitle = 'Memo';
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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Memo/">Memo</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Memo Name</label>
                                                <input type="text" name="slug" id="slug" value="<?php if(!empty($memo)){echo $memo[0]['slug'];}?>" class="form-control" placeholder="Enter Memo Name">                                 
                                            </div>

                                            <div class="col-12 col-md-6 form-group" >
                                                <label>Position</label>
                                                <input type="text" name="position" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"  id="position" value="<?php if(!empty($memo)){echo $memo[0]['position'];}?>" class="form-control" placeholder="Enter Position">

                                                <!-- <label class="new-control new-checkbox checkbox-success" style="color:#515365;"><input type="checkbox" class="new-control-input form-control" name="current_memo" id="current_memo" value="" <?php if(!empty($memo) && $memo[0]['is_current_memo']==1){echo 'checked';}?> >
                                                <span class="new-control-indicator"></span>&nbsp;Show As Current Memo</label>  -->
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Open Time</label><br>
                                                <input id="fromdate" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Open Date" value="<?php if(!empty($memo)){ echo date("d-m-Y H:i",$memo[0]['from_date']); }?>">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Close Time</label><br>
                                                <input type="text" id="todate" name="todate" class="form-control flatpickr flatpickr-input active" placeholder="Select Close Date" value="<?php if(!empty($memo)){ echo date("d-m-Y H:i",$memo[0]['to_date']); }?>">
                                            </div>

                                            <div class="form-group col-12 col-md-6">
                                                  <label>Include User</label>
                                                  <select class="form-control tagging" multiple="multiple" name="include_userr[]" id="include_userr">
                                                    <?=$include_userr?>
                                                  </select>
                                            </div>

                                            <div class="form-group col-12 col-md-6">
                                                  <label>Exclude User</label>
                                                  <select class="form-control tagging" name="exclude_userr[]" multiple="multiple" id="exclude_userr">
                                                    <?=$exclude_userr?>
                                                  </select>
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

                                            <?php if(empty($memo)) : ?>

                                              <div class="card-block col-12 col-md-5" style="margin-left: 20px"> 

                                                  <div class="form-group">
                                                     <label>File Type (Image)</label><br>
                                                     <input type="file" name="filename1"  class="" id="filename1" accept="image/*" onchange="loadFile(1,event)">
                                                     <img id="preview1" width="100%" height="200px" style="margin-top:15px;display: none; "/>
                                                  </div>

                                                  <div class="form-group">
                                                      <label> Language*</label>
                                                      <select class="form-control custom-select langSel" name="language1" id="language1">
                                                         <option value="">Select Language</option>
                                                            <?php  foreach ($LanguageArray as $language) {
                                                                      $checked = ($checked_lang==$language['lang_code']) ? 'selected' : '';
                                                                echo '<option value="'.$language['lang_code'].'" '.$checked.'>'.$language['lang_name'].'</option>';
                                                            } ?>
                                                      </select> 
                                                  </div>

                                              </div>

                                            <?php endif;?>

                                            <?php foreach ($memo as $key => $value) { $memoCount = $key + 1; ?>
                                            
                                               <div class="card-block col-12 col-md-5" style="margin-left: 20px"> 
                                                
                                                  <div class="form-group">
                                                     <label>File Type (Image)</label><br>
                                                     <input type="file" name="filename<?=$memoCount?>"  class="" id="filename<?=$memoCount?>" accept="image/*" onchange="loadFile(<?=$memoCount?>,event)">
                                                     <div class="col-12" id="gallery">
                                                     <img id="preview<?=$memoCount?>" width="100%" height="200px" style="margin-top:15px;display: none; "/>
                                                    <?php 
                                                      if(!empty($value['filename'])){

                                                         $img_src = in_array(pathinfo($value['filename'], PATHINFO_EXTENSION),['pdf']) ? FrontEnd.'web/images/announcement-icon/pdf.png' : FRONTEND.'web/upload/memo/'.$value['filename'];

                                                       echo '<div class="col-12 removePics'.$memoCount.'" style="margin-top:15px ">
                                                                 <span class="custom-file-container__image-multi-preview__single-image-clear" onclick="removePics(\''.$memoCount.'\',event)" style="position:absolute;right: 7px !important;left:auto">
                                                                        <span class="custom-file-container__image-multi-preview__single-image-clear__icon">x</span></span>
                                                                 <a href="'.FRONTEND.'web/upload/memo/'.$value['filename'].'" target="_blank"><img src="'.$img_src.'" width="100%" height="200px"></a>
                                                             </div>';
                                                      }
                                                    ?>
                                                      </div>
                                                  </div>

                                                  <div class="form-group">
                                                      <label>Language*</label>
                                                      <select class="form-control custom-select langSel" name="language<?=$memoCount?>" id="language<?=$memoCount?>">
                                                         <option value="">Select Language</option>
                                                            <?php  foreach ($LanguageArray as $language) {
                                                                      $checked = ($value['lang_code']==$language['lang_code']) ? 'selected' : '';
                                                                echo '<option value="'.$language['lang_code'].'" '.$checked.'>'.$language['lang_name'].'</option>';
                                                            } ?>
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

                                              <div class="col-12">
                                                <label>Status</label>     
                                                <div class="row">
													                         <?php  foreach ($this->statusArry as $key => $status) {?>
                                                    <div class="form-check mb-2">
                                                        <div class="custom-control custom-radio classic-radio-info">
                                                            <input type="radio" id="hRadio<?=$key?>" name="c_status" class="custom-control-input" value="<?=$key?>" <?php if(!empty($memo) && $memo[0]['status'] ==$key){ echo "checked";}else{}  ?> >
                                                            <label class="custom-control-label" for="hRadio<?=$key?>"><?=$status?></label>
                                                        </div>
                                                    </div>
													                         <?php }?>
                                                     
                                                </div>   
                                             </div>
                                            
                                          </div>
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
    $('#settingsMenu').attr("data-active","true");
    $('#settingsNav').addClass('show');
    $('#memoNav').addClass('active');
<?php if(isset($_GET['id'])){?>
  var include_downline           = '<?=$include_downline?>';
  var excludeusr_include_downline     = '<?=$excludeusr_include_downline?>';
  
  if(excludeusr_include_downline==1){
     $('#excludeusr_include_downline').prop('checked',true);
  }
  if(include_downline==1){
     $('#include_downline').prop('checked',true);
  }
  
<?php }?>


  $('#save').click(function(){

    data = new FormData();

    data.append('totalCount', createDivcount);
    data.append('status', $('input[name="c_status"]:checked').val());
    data.append('slug', $('#slug').val());
    data.append('position', $('#position').val());
    data.append('editID', "<?=$log?>");
    data.append('fromdate', $('#fromdate').val());
    data.append('todate', $('#todate').val());
    data.append('include_userr', $('#include_userr').val());
    data.append('exclude_userr', $('#exclude_userr').val());
    data.append('include_country', $('#include_country').val());
    data.append('exclude_country', $('#exclude_country').val());
    if ($('#include_downline').is(":checked"))
    {
      data.append('include_downline', 1);
    }else{
      data.append('include_downline', 0);
    }
    if ($('#excludeusr_include_downline').is(":checked"))
    {
      data.append('excludeusr_include_downline', 1);
    }else{
      data.append('excludeusr_include_downline', 0);
    }

    data.append('removedID', removedID);

    /*let current_memo = ($('#current_memo').prop('checked')==false) ? 0 : 1;
    data.append('current_memo', current_memo);*/

    for (var i = 1; i <= createDivcount; i++) {

      if ($('#language'+i).length){

        data.append('language'+i, $('#language'+i).val());
        data.append('filename'+i, $('#filename'+i)[0].files[0]);
      }
    }
 
    loadingoverlay('info',"Please Wait...","Loading....");

    $.ajax({
        url: '<?=BASEURL;?>Memo/Add/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
                 {
                 openSuccess(newResp['response'],'<?=BASEURL;?>Memo/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 
});

var createDivcount = <?=$divCount?>;
var language       = <?=json_encode($LanguageArray)?>;
var selectedLang   = [];
var removedID      = [];

  function createHtml(){

     langUpdate();

     let lang        = '';

     jQuery.each(language, function(index, item) {
        lang += '<option value="'+item['lang_code']+'">'+item['lang_name']+'</option>'
     })   
      
     createDivcount ++;

     var html ='<div class="card-block col-12 col-md-5" style="margin-left: 20px" id="memoDiv'+createDivcount+'">'+ 
                  '<button type="button" class="close remove" onclick=remove("'+createDivcount+'")>&times;</button>'+
                  '<div class="form-group">'+
                      '<label>File Type (Image)</label><br>'+
                      '<input type="file" name="filename'+createDivcount+'"  class="" id="filename'+createDivcount+'" accept="image/*" onchange="loadFile('+createDivcount+',event)">'+
                      '<img id="preview'+createDivcount+'" width="100%" height="200px" style="margin-top:15px;display: none; "/>'+
                  '</div>'+
                  '<div class="form-group">'+
                      '<label>Language*</label>'+
                      '<select class="form-control custom-select langSel" name="language'+createDivcount+'" id="language'+createDivcount+'">'+
                          '<option value="">Select Language</option>'+lang+
                      '</select>'+ 
                  '</div>'+
                '</div>';

      $('#thisAdd').before(html);

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
     $('#memoDiv'+id).remove();
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
  
  $( function() {
  var f3 = flatpickr($('#fromdate,#todate'), {
           enableTime: true,
           dateFormat: "d-m-Y H:i",
  });
  });

   $(".tagging").select2({
      tags: false,
      placeholder: 'Exclude Users',
    });
 
    function clearData(){ 
  
     $("#user_id").val(null).trigger("change"); 
     $("#sub").val(null).trigger("change"); 
     $("#sub").prop( "disabled", true );
   } 
  

</script>
