<?php 
use inc\Raise;
$this->mainTitle = 'Promotion';
$promotion_date  = empty($promotion[date]) ? '' : date("d-m-Y", strtotime($promotion[date]));
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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Promotion/Index/">Promotion</a></li>
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
                                                   <label>Title*</label>
                                                   <input type="text"  name="title" id="title"  class="form-control" value="<?=(isset($promotion['title'])) ? $promotion['title'] : ''?> ">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Date*</label>
                                                   <input type="text"  name="date" id="date"  class="form-control" value="<?=(isset($promotion['date'])) ? $promotion_date: ''?> ">
                                                </div>
                                            </div>
                                            <div class="col-12 form-group">
                                                <label>Description</label> 
                                                <textarea name="message" id="message" rows="1" class="form-control"></textarea>
                                                 <div id = "myDiv" style="display:none"><?=(isset($promotion['description'])) ? $promotion['description'] : ''?></div>

                                            </div>

                                             <?php if(!empty($promotion['id'])){
                                                $select1 = empty($promotion['status']) ? 'selected' : '';
                                                $select2 = $promotion['status']==1 ? 'selected' : '';
                                              }else{
                                                $select1 = $select2 = '';
                                              }
                                            ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Status</label>      
                                                    <select class="form-control custom-select" id="status" name="status">
                                                        <option  value="0" <?=$select1?> >Hidden</option>
                                                        <option value="1" <?=$select2?> >Published</option>
                                                    </select>
                                                </div>
                                            </div>
                                          
                                            <?php if(empty($promotion['image'])) { ?>
                                                 <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>File Upload (Image)*</label>
                                                   <input type="file" name="filename" accept="image/*" class="form-control" id="filename" onchange="loadFile(event)">
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
                                                      if(!empty($promotion['image'])){

                                                         $img_src = FRONTEND.'web/upload/promotions/'.$promotion['image'];

                                                       echo '<div class="col-12 removePics" style="margin-top:15px ">
                                                                 <span class="custom-file-container__image-multi-preview__single-image-clear" onclick="removePics(event)" style="position:absolute;right: 7px !important;left:auto">
                                                                        <span class="custom-file-container__image-multi-preview__single-image-clear__icon">x</span></span>
                                                                 <a href="'.$img_src.'" target="_blank"><img src="'.$img_src.'" width="100%" height="500px"></a>
                                                             </div>';
                                                      }
                                                    ?>
                                                      </div>
                                                  </div>

                                                  

                                              </div>    

                                              <?php
                                            }
                                              ?>        
                                         

                                           
                                           
                                          
                                            <input type="hidden" id="editID" value="<?=isset($promotion['id']) ? $promotion['id'] : ''?>">

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
    $('#promotion').addClass('active');
$(function () {

      CKEDITOR.replace("message");
     

      CKEDITOR.instances['message'].setData($("#myDiv").html());
});

     
$('#save').click(function(){
   
 data = new FormData();
 data.append('title', $('#title').val());
 data.append('date', $('#date').val());
 data.append('editID', $('#editID').val());
 data.append('status', $('#status').val());
 data.append('message', CKEDITOR.instances['message'].getData());
 data.append('filename', $('#filename')[0].files[0]);
  
$.ajax({
        url: '<?=BASEURL;?>Promotion/Add/', 
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
               openSuccess(newResp['response'],'<?=BASEURL;?>Promotion/Index/')  
            }else{
               loadingoverlay('error','error',newResp['response']);
            }
           return false;
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
    if (t == "jpeg"|| t == "jpg" || t == "png" || t == "gif") {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
           URL.revokeObjectURL(output.src) // free memory
        }
        $('#preview').show()
    }else{
        $('#preview').hide()
    }
  };


$( function() {

    var f1 = flatpickr(document.getElementById('date'),{
      dateFormat:"d-m-Y",
    });

     });

</script>
