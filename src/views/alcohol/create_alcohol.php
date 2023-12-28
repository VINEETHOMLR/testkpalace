<?php 
use inc\Root;
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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Alcohol/List/">Alcohol</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                  
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info" >
                                <div class="row"  >
                                    <div class="col-md-11 mt-4 mb-4 box1 rw1"  id="html_content"  >
                                            <div class="col-12 col-md-6 form-group">
                                                <label>FOC</label>
                                                <input type="checkbox" name="foc1" id="foc1" <?php if(!empty($alcohol)){echo ($alcohol[0]['foc'] == 1) ? 'checked' : '';}?>>
                                            </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6 form-group">
                                                <div class="form-group">
                                                    <label>Item Date<span style="color:red;">*</span></label>
                                                    <input type="date"  name="item_date1" id="item_date1" value="<?php if(!empty($alcohol)){echo $alcohol[0]['item_date'];}?>" class="form-control item_date">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Product Name<span style="color:red;">*</span></label>
                                                <input type="text" name="name1" id="name1" value="<?php if(!empty($alcohol)){echo htmlspecialchars_decode($alcohol[0]['name']);}?>" class="form-control" placeholder="Enter Name">                           
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Supplier</label><br>
                                                <select class="form-control custom-select langSel2" name="supplier1" id="supplier1">
                                                    <option value="">Select Supplier</option>
                                                        <?php  foreach ($supplierArray as $supplier) {
                                                                 $checked = ($supplier['id']== $alcohol[0]['supplier_id']) ? 'selected' : '';
                                                            echo '<option value="'.$supplier['id'].'" '.$checked.'>'.html_entity_decode(html_entity_decode($supplier['supplier_name'])).'</option>';
                                                        } ?>
                                                </select> 
                                            </div>

                                           <div class="col-12 col-md-6 form-group">
                                                <label> Category<span style="color:red;">*</span></label>
                                                <select class="form-control custom-select langSel" name="category1" id="category1">
                                                    <option value="">Select Category</option>
                                                    <?php  foreach ($categoryArray as $category) {
                                                             $checked = ($category['id']== $alcohol[0]['category_id']) ? 'selected' : '';
                                                        echo '<option value="'.$category['id'].'" '.$checked.'>'.html_entity_decode(html_entity_decode($category['name'])).'</option>';
                                                    } ?>
                                                </select>                                  
                                            </div>
                                            
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Brand<span style="color:red;">*</span></label><br>
                                                <input id="brand1" name="brand1" class="form-control" type="text" placeholder="Brand" value="<?php if(!empty($alcohol)){ echo $alcohol[0]['brand']; }?>">
                                            </div>
                                           
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Vintage<span style="color:red;">*</span></label><br>
                                                <input id="vintage1" name="vintage1" class="form-control" type="text" placeholder="Vintage" value="<?php if(!empty($alcohol)){ echo $alcohol[0]['vintage']; }?>">
                                            </div>
 
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Country</label><br>
                                                <input type="text" id="country1" name="country1" class="form-control " placeholder="Country" value="<?php if(!empty($alcohol)){ echo $alcohol[0]['country']; }?>">
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Volume</label><span style="color:red;">*</span><br>
                                                <input id="volume1" name="volume1" class="form-control" type="text" placeholder="Volume" value="<?php if(!empty($alcohol)){ echo $alcohol[0]['volume']; }?>">
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Alcohol Percent</label><br>
                                                <input type="text" id="percent1" name="percent1" class="form-control" placeholder="Alcohol Percent" value="<?php if(!empty($alcohol)){ echo $alcohol[0]['alcohol_percent']; }?>">
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Quantity<span style="color:red;">*</span></label><br>
                                                <input type="number" id="quantity1" name="quantity1" class="form-control" placeholder="Quantity" value="<?php if(!empty($alcohol)){ echo $alcohol[0]['quantity']; }?>">
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Unit Price</label><br>
                                                <input id="unit_price1" name="unit_price1" class="form-control" type="text" placeholder="Unit Price" value="<?php if(!empty($alcohol)){ echo $alcohol[0]['unit_price']; }?>">
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Selling Price</label><br>
                                                <input id="price1" name="price1" class="form-control" type="text" placeholder="Selling Price" value="<?php if(!empty($alcohol)){ echo $alcohol[0]['price']; }?>">
                                            </div>

                                            <?php if(empty($alcohol)) : ?>
                                              <div class="card-block col-12 col-md-5" style="margin-left: 20px"> 
                                                  <div class="form-group">
                                                     <label>File Type (Image)<span style="color:red;">*</span></label><br>
                                                     <input type="file" name="filename1"  class="" id="filename1" accept="image/*" onchange="loadFile(1,event)">
                                                     <img id="preview1" width="100%" height="500px" class="file_preview" style="margin-top:15px;display: none; "/>
                                                  </div>
                                              </div>

                                            <?php endif;?>

                                            <?php foreach ($alcohol as $key => $value) { $alcoholCount = $key + 1; ?>
                                               <div class="card-block col-12 col-md-5" style="margin-left: 20px"> 
                                                  <div class="form-group">
                                                     <label>File Type (Image)</label><br>
                                                     <input type="file" name="filename1"  class="" id="filename1" accept="image/*" onchange="loadFile(<?=$alcoholCount?>,event)">
                                                     <div class="col-12" id="gallery">
                                                     <img id="preview<?=$alcoholCount?>" width="100%" height="500px" style="margin-top:15px;display: none; "/>
                                                    <?php 
                                                      if(!empty($value['image'])){
                                                            $img_src = in_array(pathinfo($value['image'], PATHINFO_EXTENSION),['pdf']) ? FrontEnd.'web/images/announcement-icon/pdf.png' : FRONTEND.'web/upload/inventory/'.$value['image'];

                                                            echo '<div class="col-12 removePics'.$alcoholCount.'" style="margin-top:15px ">
                                                                 <span class="custom-file-container__image-multi-preview__single-image-clear" onclick="removePics(\''.$alcoholCount.'\',event)" style="position:absolute;right: 7px !important;left:auto">
                                                                        <span class="custom-file-container__image-multi-preview__single-image-clear__icon">x</span></span>
                                                                 <a href="'.FRONTEND.'web/upload/inventory/'.$value['image'].'" target="_blank"><img src="'.$img_src.'" width="100%" height="500px"></a>
                                                             </div>';
                                                      }
                                                    ?>
                                                      </div>
                                                  </div>
                                              </div>
                                          <?php } ?>
                                        <br>
                                        </div>
                                            <?php if(empty($log)){?>
                                                <div class="row">
                                                    <div class="col-12 col-md-4 form-group">

                                                        <button class="btn btn-info addbtn" type="button" style="margin-top: 35px;" onclick="addrow()">+</button>                               
                                                    </div>
                                                </div>
                                            <?php
                                          }
                                          ?> 
                                        </div>
                                        <input type="hidden" name="countID" id="countID" value="1">
                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                <button class="btn btn-primary proceedToPayment col-12 col-md-5" id="save" type="button"><?=Root::t('announcement','submit')?></button>
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

<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>


<script type="text/javascript">

  $('#accordionExample').find('li a').attr("data-active","false");
   $('#AlcoholMenu').attr("data-active","true");
  $('#AlcoholNav').addClass('show');
  $('#al_list').addClass('active');

  var id=2;
  function addrow() {
    
     var al_name_html = $(".langSel").html();
     var supplier_name_html = $(".langSel2").html();

     //console.log(al_name_html);
     $(".box1").append('<div class="row"><div class="col-12 col-md-6 form-group"><label>FOC</label><input type="checkbox" name="foc'+id+'" id="foc'+id+'"></div><div class="col-12 col-md-6 form-group"><label> Item Date</label><input type="text" name="item_date'+id+'" id="item_date'+id+'" value="" class="form-control item_date" placeholder="Enter Item date"></div><div class="col-12 col-md-6 form-group"><label> Name</label><input type="text" name="name'+id+'" id="name'+id+'" value="" class="form-control" placeholder="Enter Name"></div> <div class="col-12 col-md-6 form-group"><label> Supplier</label><select class="form-control custom-select langSel2" name="supplier'+id+'" id="supplier'+id+'">'+supplier_name_html+'</select></div> <div class="col-12 col-md-6 form-group"><label> Category</label><select class="form-control custom-select langSel" name="category'+id+'" id="category'+id+'">'+al_name_html+'</select></div><div class="col-12 col-md-6 form-group"><label>Brand</label><br><input id="brand'+id+'" name="brand'+id+'" class="form-control" type="text" placeholder="Brand" value=""></div><div class="col-12 col-md-6 form-group"><label>Vintage</label><br><input id="vintage'+id+'" name="vintage'+id+'" class="form-control" type="text" placeholder="Vintage" value=""></div><div class="col-12 col-md-6 form-group"><label>Country</label><br><input type="text" id="country'+id+'" name="country'+id+'" class="form-control " placeholder="Country" value=""></div><div class="col-12 col-md-6 form-group"><label>Volume</label><br><input id="volume'+id+'" name="volume'+id+'" class="form-control" type="text" placeholder="Volume" value=""></div><div class="col-12 col-md-6 form-group"><label>Alcohol Percent</label><br><input type="text" id="percent'+id+'" name="percent'+id+'" class="form-control" placeholder="Alcohol Percent" value=""></div><div class="col-12 col-md-6 form-group"><label>Quantity</label><br><input type="number" id="quantity'+id+'" name="quantity'+id+'" class="form-control" placeholder="Quantity" value="" min="0"></div><div class="col-12 col-md-6 form-group"><label>Unit Price</label><br><input id="unit_price'+id+'" name="unit_price'+id+'" class="form-control" type="text" placeholder="Unit Price" value=""></div><div class="col-12 col-md-6 form-group"><label>Selling Price</label><br><input id="price'+id+'" name="price'+id+'" class="form-control" type="text" placeholder="Price" value=""></div><div class="card-block col-12 col-md-5" style="margin-left: 20px"> <div class="form-group"><label>File Type (Image)</label><br><input type="file" name="filename'+id+'"  class="" id="filename'+id+'" accept="image/*" onchange="loadFile('+id+',event)"><img id="preview'+id+'" width="100%" height="500px" class="file_preview" style="margin-top:15px;display: none; "/></div></div></div></div></div><div class="row"><div class="col-12 col-md-4 form-group"><button class="btn btn-info addbtn rows'+id+'" type="button" style="margin-top: 35px;" onclick="addrow()">+</button><br></div>');

     flatpickr(document.getElementById('item_date' + id), {
        dateFormat: "d-m-Y",
        altInput: true,
        altFormat: "d-m-Y"
    });

     id++;

  }

  $('#save').click(function(){

    //data = $('#formValidation').serializeArray();
    //data.push({ name: "filename", value: $('#filename')[0].files[0] });

    data = new FormData();
    var length = id-1;
    createDivcount = id-1;

    if('<?=$log?>'!=''){
      length         = 1;
      createDivcount = 1;
    }

    for(i=1;i<=length;i++){
    data.append('totalCount', createDivcount);
    data.append('foc' + i, $('#foc' + i).is(':checked') ? '1' : '0');
    data.append('item_date'+i, $('#item_date'+i).val());
    data.append('name'+i, $('#name'+i).val());
    data.append('supplier'+i, $('#supplier'+i).val());
    data.append('cat'+i, $('#category'+i).val());
    data.append('brand'+i, $('#brand'+i).val());
    data.append('vintage'+i, $('#vintage'+i).val());
    data.append('country'+i, $('#country'+i).val());
    data.append('volume'+i, $('#volume'+i).val());
    data.append('percent'+i, $('#percent'+i).val());
    data.append('price'+i, $('#price'+i).val());
    data.append('quantity'+i, $('#quantity'+i).val());
    data.append('editID', "<?=$log?>");
    data.append('filename'+i, $('#filename'+i)[0].files[0]);
  }
  
    loadingoverlay('info',"Please Wait...","Loading....");

    $.ajax({
        url: '<?=BASEURL;?>Alcohol/AddAlcohol/', 
       
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
                 openSuccess(newResp['response'],'<?=BASEURL;?>Alcohol/List/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 
});

var createDivcount = <?=$divCount?>;
var selectedLang   = [];
var removedID      = [];


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


flatpickr(document.getElementsByClassName('item_date'), {
    dateFormat: "d-m-Y",
    altInput: true,
    altFormat: "d-m-Y"
});

  
</script>
