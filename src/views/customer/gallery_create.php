<?php 
use inc\Raise;
$this->mainTitle = 'Gallery';
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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Customer/Gallery/">Gallery</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">
                                       
                                            
                                             <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                  <label>Select Username*</label>
                                                   <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($gallery['user_id'])):?>
                                          <option value="<?=$gallery[user_id]?>" selected><?=$gallery['s_username']?></option>
                                        <?php endif;?>
                                    </select>
                                                </div>
                                            </div> -->


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label>Customer Username</label>
                                                   <select type="option" name="user_id" id="user_ids" class="form-control gallery_id" multiple="">
                                                    <?php foreach($customerList as $key=>$value){
                                              
                                                    $selected = !empty($gallery) && in_array($value['id'],explode(',',$gallery['user_id'])) ? 'selected' : '';

                                                ?>
                                                  <option value="<?= $value['id']?>" <?= $selected ?>><?= $value['username']?></option>
                                                <?php }?>
                                                </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label>Select Room</label>
                                                   <select type="option" name="room_id" class="form-control custom-select" id="room_id">
                                                    <option value="">Select Room</option>
                                                        <?php if(!empty($roomList)){
                                                            foreach($roomList as $key=>$value){
                                                            $selected = !empty($roomList) && in_array($value['id'],explode(',',$gallery['room_id'])) ? 'selected' : '';
                                                                ?>
                                                              <option value="<?=$value['id']?>" <?= $selected ?>><?=$value['description']?></option>
                                                            <?php }
                                                        }?>
                                                </select>
                                                </div>
                                            </div>                     
                                           
                                            <div class="col-12 form-group">
                                                <label>Remarks*</label> 
                                                <textarea name="remarks" id="remarks" rows="1" class="form-control"></textarea>
                                                 <div id = "myDiv" style="display:none"><?=(isset($gallery['remarks'])) ? $gallery['remarks'] : ''?></div>

                                            </div>

                                             <?php if(!empty($gallery['id'])){
                                                $select1 = $gallery['status']==0 ? 'selected' : '';
                                                $select2 = $gallery['status']==1 ? 'selected' : '';
                                              }else{
                                                $select1 = $select2 = '';
                                              }
                                            ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Status</label>      
                                                    <select class="form-control custom-select" id="status" name="status">
                                                        <option  value="0" <?=$select1?> >Active</option>
                                                        <option value="1" <?=$select2?> >Hide</option>
                                                    </select>
                                                </div>
                                            </div>  
                                             <?php if(empty($gallery['image'])) { ?>
                                                 <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>File Upload (Image)*</label>
                                                   <input type="file" name="filename"  class="form-control" id="filename" onchange="loadFile(event)">
                                                     <img id="preview" width="100%" height="500px" style="margin-top:15px;display: none; "/>
                                                </div>
                                            </div>


                                              <?php  } else { ?>

                                         <div class="card-block col-12 col-md-5" style="margin-left: 20px"> 
                                                
                                                  <div class="form-group">
                                                     <label>File Type (Image)</label><br>
                                                     <input type="file" name="filename"  class="" id="filename" accept="image/*" onchange="loadFile(event)">
                                                     <div class="col-12" id="gallery">
                                                     <img id="preview" width="100%" height="500px" style="margin-top:15px;display: none; "/>
                                                    <?php 
                                                      if(!empty($gallery['image'])){

                                                         $img_src = FRONTEND.'web/upload/gallery/'.$gallery['image'];

                                                       echo '<div class="col-12 removePics" style="margin-top:15px ">
                                                                 <span class="custom-file-container__image-multi-preview__single-image-clear" onclick="removePics(event)" style="position:absolute;right: 7px !important;left:auto">
                                                                        <span class="custom-file-container__image-multi-preview__single-image-clear__icon">x</span></span>
                                                                 <a href="'.FRONTEND.'web/upload/gallery/'.$value['image'].'" target="_blank"><img src="'.$img_src.'" width="100%" height="500px"></a>
                                                             </div>';
                                                      }
                                                    ?>
                                                      </div>
                                                  </div>

                                                  

                                              </div>    

                                              <?php
                                            }
                                              ?>  
                                  
                                          
                                            <input type="hidden" id="editID" value="<?=isset($gallery['id']) ? $gallery['id'] : ''?>">

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
</div>


<script type="text/javascript">
$('#accordionExample').find('li a').attr("data-active","false");
$('#custMenu').attr("data-active","true");
$('#custNav').addClass('show');
$('#gallery').addClass('active');
$(function () {

      CKEDITOR.replace("remarks");
     

      CKEDITOR.instances['remarks'].setData($("#myDiv").html());
});

     
$('#save').click(function(){
   
 data = new FormData();
 data.append('user_id', $('#user_ids').val());
 data.append('editID', $('#editID').val());
 data.append('status', $('#status').val());
 data.append('room_id', $('#room_id').val());
 data.append('remarks', CKEDITOR.instances['remarks'].getData());
 data.append('filename', $('#filename')[0].files[0]);
  
$.ajax({
        url: '<?=BASEURL;?>Customer/Add/', 
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
               openSuccess(newResp['response'],'<?=BASEURL;?>Customer/Gallery/')  
            }else{
               loadingoverlay('error','error',newResp['response']);
            }
           return false;
        }
    }); 
});

 $(document).ready(function() {
    // Initialize Select2 on the 'gallery_id' select element
    $('.gallery_id').select2({
      placeholder: 'Select Username',
      tags: false,
      minimumInputLength: 1,
      ajax: {
        url: '<?=BASEURL?>Customer/getCustomers',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          // Modify the data object to include the term and any other necessary parameters
          return {
            term: params.term, // search term
          };
        },
        processResults: function(data) {
          // Modify the data structure if needed
          return {
            results: data
          };
        },
        cache: true
      }
    });
  });
function removePics(){
     $('.removePics').remove()
     removedFile = true;
  }

  var loadFile = function(event) {
    $('.removePics').remove()
    var output = document.getElementById('preview');

    var file     = event.target.files[0];
    var t = file.type.split('/').pop().toLowerCase();
    if (t == "jpeg"|| t == "jpg" || t == "png" ) {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
           URL.revokeObjectURL(output.src) // free memory
        }
        $('#preview').show()
    }else{
        $('#preview').hide()
    }
  };



</script>
