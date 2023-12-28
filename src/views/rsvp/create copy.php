<?php 
use inc\Root;
$this->mainTitle = 'RSVP';
$this->subTitle  = 'Create RSVP';

$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];
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
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"> -->


<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                  <nav class="breadcrumb-two" aria-label="breadcrumb">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Rsvp/Index/">RSVP</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                  
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info" >
                                <div class="row"  >
                                    <div class="col-md-11 mt-4 mb-4 box1 rw1"  id="html_content"  >
                                        <div class="row">

                                            <div class="col-12 col-md-6 form-group">
                                                <label> Room/Table *</label>
                                                <select class="form-control" id="type" onchange="changeType($(this).val())"> 
                                                    <option value="">Room/Table</option>
                                                    <option value='1'>Room</option>
                                                    <option value='2'>Table</option>
                                                  
                                                </select>                            
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label> Room/Table Number *</label>
                                                <select class="form-control" id="room_id"> 
                                                  <option value="">Room/Table Number</option>
                                                   
                                                </select>                            
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label> RSVP Date *</label>
                                                <input id="booked_date" name="booked_date" type='date' min='1899-01-01' max='2000-13-13' class="input-group form-control" type="text" placeholder="" >                         
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label> Select Hour Type *</label>
                                                <select class="form-control" id="hour_id" onchange="changeTime($(this).val())"> 
                                                  <option value="">Hour Type</option>

                                                  <?php foreach($hourList as $key=>$value){?>
                                                     <option value='<?= $value['id']?>'><?= $value['name']?></option>
                                                  <?php }?>
                                                 
                                                </select>                            
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label class="form-check-label" for="is_custom_time">
                                                  Custom Time  *

                                                </label> 
                                                <input class="" type="checkbox" id="is_custom_time">
                                                
                                                                      
                                            </div>

                                            <div class="col-12 col-md-3 form-group defaultTimeDiv">

                                              
                                                <label> Check In Time  *</label>
                                                <input id="from_time" name="from_time" type='text' class="form-control" type="text" readonly=""> 
                                                                       
                                            </div>

                                            <div class="col-12 col-md-3 form-group defaultTimeDiv">

                                              
                                                <label> Check Out Time  *</label>
                                                <input id="to_time" name="to_time" type='text' class="form-control" type="text" readonly="" > 
                                                                       
                                            </div>


                                            <div class="col-12 col-md-3 form-group customTimeDiv">

                                              
                                                <label> Check In Time  *</label>
                                                <input id="custom_from_time" name="custom_from_time"  class="form-control" type="time" > 
                                                                       
                                            </div>

                                            <div class="col-12 col-md-3 form-group customTimeDiv">

                                              
                                                <label> Check Out Time  *</label>
                                                <input id="custom_to_time" name="custom_to_time"  class="form-control" type="time" > 
                                                                       
                                            </div>

                                            <!-- <div class="col-12 col-md-6 form-group" style="margin-top: 30px;">
                                               <select type="option" name="user_id" class="form-control" id="user_id">
                                                    <option value="">Select Customer</option>
                                                    
                                                </select>

                                            </div> -->

                                            <div class="col-12 col-md-6 form-group">
                                              <label> Customer  * </label>
                                                <select class="customer_id form-control" id="user_id" name="customer_id[]" multiple>
                                                  <?php foreach($customerList as $key=>$value){?>
                                                    <option value="<?= $value['id']?>"><?= $value['text']?></option>
                                                  <?php } ?>
                                                    
                                                </select>


                                            </div>

                                            <div class="col-12 col-md-6 form-group">

                                              
                                                <label> Customer Mobile  *</label>
                                                <textarea class="form-control" id="customer_mobile" name="customer_mobile" readonly="" ></textarea>
                                               
                                            </div>

                                           
                                            



                                            <div class="col-12 col-md-6 form-group">
                                                <label> Mummy Name  *  <button class="btn btn-info addbtn" type="button" onclick="addMummyNameRow()">+</button>
                                                </label>
                                                <input name="mummy_name[]" class="form-control mummy_name" type="text" placeholder="Mummy Name" >
                                                <span id="mummy_name_div">
                                                  
                                                </span>                         
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label> Rsvp Type *</label>
                                                <select class="form-control" id="rsvp_type" > 
                                                    <option value="">Select</option>
                                                    <option value='1'>Walk In</option>
                                                    <option value='2'>Online</option>
                                                  
                                                </select>                            
                                            </div>

                                            <div class="col-12 col-md-6 form-group">

                                              
                                                <label> Receptionist No  *</label>
                                                <input type="text" class="form-control" id="receptionist_no" name="receptionist_no">
                                                
                                               
                                            </div>
                                            <div class="col-12 col-md-6 form-group">

                                              
                                                <label> PR Manager  *</label>
                                                <input type="text" class="form-control" id="pr_manager" name="pr_manager">
                                                
                                               
                                            </div>
                                            <div class="col-12 col-md-6 form-group">

                                              
                                                <label> Brought In By  *</label>
                                                <input type="text" class="form-control" id="brought_in_by" name="brought_in_by">
                                                
                                               
                                            </div>

                                            <div class="col-12 col-md-6 form-group">

                                              
                                                <label> Male Count *</label>
                                                <input type="number" class="form-control" id="male_count" name="male_count">
                                                
                                               
                                            </div>

                                            <div class="col-12 col-md-6 form-group">

                                              
                                                <label> Female Count *</label>
                                                <input type="number" class="form-control" id="female_count" name="female_count">
                                                
                                               
                                            </div>
                                            <div class="col-12 col-md-6 form-group">

                                              
                                                <label>Remark </label>
                                                <textarea id="remarks" name="remarks"  class="form-control" > </textarea>
                                                                       
                                            </div>

                                               
                                           
                                        </div>
                                        
                                          
                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                <button class="btn btn-primary proceedToPayment col-12 col-md-5" id="save" type="button" onclick="bookRsvp()"><?=Root::t('announcement','submit')?></button>
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
  $('#RsvpMenu').attr("data-active","true");
  $('#RsvpNav').addClass('show');
  $('#RsvpList').addClass('active');
  var f1 = flatpickr(document.getElementById('booked_date'),{
    dateFormat:"d-m-Y",
    minDate: "today",
    
});

$(document).ready(function () {
    $('.customTimeDiv').hide();
    $('.customer_id').select2();
});


function addMummyNameRow()
{
    var html = '<div><br><input name="mummy_name[]" class="form-control mummy_name" type="text" placeholder="Mummy Name" ><i class="fa fa-trash deletebtn" aria-hidden="true"></i></div>';
    $('#mummy_name_div').append(html);

}

function bookRsvp()
{
    
    var mummy_name = $('input[name="mummy_name[]"]').map(function(){ 
                    return this.value; 
                }).get();
    data = new FormData();
    data.append('room_id', $('#room_id').val());
    data.append('booked_date', $('#booked_date').val());
    data.append('hour_id', $('#hour_id').val());
    data.append('user_id', $('#user_id').val());
    data.append('mummy_name', JSON.stringify(mummy_name));
    data.append('remarks', $('#remarks').val());
    data.append('status', '0');
    data.append('rsvp_type', $('#rsvp_type').val());
    data.append('type', $('#type').val()); //1-room,2-table


    if($('#is_custom_time').prop('checked') == true){

        data.append('is_custom_time', '1'); //1-yes
        data.append('custom_from_time', $('#custom_from_time').val());
        data.append('custom_to_time', $('#custom_to_time').val());
        

        
    }else{

        data.append('is_custom_time', '0'); //0-no
        data.append('custom_from_time', '');
        data.append('custom_to_time', '');

       
    }

    data.append('receptionist_no', $('#receptionist_no').val()); 
    data.append('pr_manager', $('#pr_manager').val()); 
    data.append('brought_in_by', $('#brought_in_by').val()); 
    data.append('male_count', $('#male_count').val()); 
    data.append('female_count', $('#female_count').val()); 
       

    loadingoverlay('info',"Loading","Please Wait...");
    $.ajax({
              url: '<?=BASEURL;?>Rsvp/AddBooking/', 
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
                      openSuccess(newResp['response'],'<?=BASEURL;?>Rsvp/Index/')  
                  }else
                  {
                      loadingoverlay('error','Error',newResp['response']);
                  }
                return false;
              }
          });   
    
}

function changeType(type)
{


    
    $.ajax({
        url: "<?=BASEURL?>Rsvp/GetRooms",
        type: "post",
        data: {'type':type} ,
        success: function (response) {

            var newResp = JSON.parse(response);
            $('#room_id').html("").html(newResp);



          
           
        } 
    });  



}  

function changeTime(id)
{


    
    $.ajax({
        url: "<?=BASEURL?>Rsvp/GetTime",
        type: "post",
        data: {'id':id} ,
        success: function (response) {

            var newResp = JSON.parse(response);
            $('#from_time').val("").val(newResp['from_time']);
            $('#to_time').val("").val(newResp['to_time']);
            
 
           
        } 
    });  



}

$('.customer_id').on('change', function() {

    var user_ids = $('.customer_id').val();
    $.ajax({
        url: "<?=BASEURL?>Rsvp/GetMobileNumber",
        type: "post",
        data: {'user_ids':user_ids} ,
        success: function (response) {

            var newResp = JSON.parse(response);
            $('#customer_mobile').val(newResp);
            
 
           
        } 
    });

});


$('#is_custom_time').on('change', function() {
    
    $('#hour_id').val("");
    $('#from_time').val("");
    $('#to_time').val("");
            
    if($(this).prop('checked') == true){
        $('.defaultTimeDiv').hide();
        $('.customTimeDiv').show();
        $('#hour_id').attr('disabled', true);

        
    }else{

        $('.defaultTimeDiv').show();
        $('.customTimeDiv').hide();
        $('#hour_id').attr('disabled', false);

    }

});

// $('#user_id').select2({
//     placeholder: 'Select Customer',
//     tags: false,
//     minimumInputLength:1,
//     ajax: {
//       url: '<?=BASEURL?>User/GetUsers',
//       dataType: 'json',
//       delay: 250,
//       data: function (params) {
//         //var userType = $('#userType').val();
//         return {
//             term: params.term, // search term
//         };
//       },
//       processResults: function (data) {
//         return {
//           results: data
//         };
//       },
//       cache: true
//     }
// });

$(document).on('click','.deletebtn',function(){
    $(this).prev('input').remove();
    $(this).remove();
    
});

 

  
</script>
<style type="text/css">
  .select2{
min-width: 10em!important;
}
</style>
