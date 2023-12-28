<?php 
use inc\Root;
$this->mainTitle = 'RSVP';
$this->subTitle  = 'Merge/Change Room';

$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];
$action = !empty($action) ? $action : '';

$left_card_display = "style='display:none;'";
$right_card_display = "style='display:none;'";




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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Rsvp/Index/">RSVP</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                  
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info" >
                                <div class="row"  >
                                    <div class="col-md-11 mt-4 mb-4 box1 rw1"  id="html_content"  >
                                        <div class="row">


                                          <div class="col-12 col-md-4 form-group">
                                                <label> Action</label>
                                                <select class="form-control" id="action" onchange="changeAction($(this).val())"> 
                                                    <option value="">Select Merge/Change Room</option>

                                                    <option value="1" <?= $selected = $action=='1' ? 'selected':''?>>Merge</option>
                                                    <option value="2" <?= $selected = $action=='2' ? 'selected':''?>>Change Room</option>
                                                  
                                                </select>                            
                                            </div>

                                            <div class="col-12 col-md-4 form-group">
                                                <label> Room List</label>
                                                <select class="form-control" id="rsvp_id" onchange="changeRoom($(this).val())"> 
                                                    <option value="">Select Room</option>

                                                    <?php foreach($room_list as $key=>$value){ $selected = $value['rsvp_id'] == $rsvp_id ? 'selected' : ''?>
                                                    <option value='<?= $value['rsvp_id']?>' <?= $selected ?>><?= $value['room_no']?></option>
                                                    <?php }?>
                                                  
                                                </select>                            
                                            </div>

                                            <?php $display = $action=='' ? 'style="display:none;"' : '';

                                                  $display_change_div = $action=='' ? 'style="display:none;"' : '';

                                                  if($action == '') {

                                                      $display = 'style="display:none;"' ;

                                                      $display_change_div = 'style="display:none;"';  

                                                  }else if($action == '1'){

                                                      $display = '';

                                                      $display_change_div = 'style="display:none;"';

                                                  }else if($action == '2'){

                                                      

                                                      $display = 'style="display:none;"';
                                                      $display_change_div = '';

                                                  }






                                            ?>
                                            <div class="col-12 col-md-4 form-group merge_select_div" <?= $display ?>>
                                                <label> Merge With</label>
                                                <select class="form-control merge_id" id="merge_id" > 
                                                    <option value="">Merge With</option>

                                                    <?php foreach($available_room_list as $key=>$value){ $selected = $value['rsvp_id']==$merge_room_id ? 'selected':'';?>
                                                    <option value='<?= $value['rsvp_id']?>' <?= $selected ?>><?= $value['room_no']?></option>
                                                    <?php }?>
                                                  
                                                </select>                            
                                            </div>

                                           

                                            <div class="col-12 col-md-4 form-group change_select_div" <?= $display_change_div ?>>
                                                <label> Change With</label>
                                                <select class="form-control merge_id" id="change_merge_id" > 
                                                    <option value="">Change With</option>

                                                    <?php foreach($available_room_list as $key=>$value){ $selected = $value['rsvp_id']==$merge_room_id ? 'selected':'';?>
                                                    <option value='<?= $value['rsvp_id']?>' <?= $selected ?>><?= $value['room_no']?></option>
                                                    <?php }?>
                                                  
                                                </select>                            
                                            </div>

      
                                        </div>

              


                                       
                                            <div class="row">
                                              <div class="col-md-6 left-card" <?= $left_card_display ?>>
                                                <div class="card custom-left-margin"> <!-- Decreased left margin here -->
                                                  <div class="card-body left-card-body">
                                                    <h5 class="card-title">Room Details</h5>
                                                    <p class="card-text">Customer Name : Vineeth</p>
                                                    
                                                  </div>
                                                </div>
                                              </div>
                                              
                                              <div class="col-md-6 right-card" <?= $right_card_display ?>>
                                                <div class="card">
                                                  <div class="card-body right-card-body">
                                                    <h5 class="card-title">Right Card</h5>
                                                    <p class="card-text">This is some sample text for the right card.</p>
                                                    <a href="#" class="btn btn-primary">Right Action</a>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                        
                                          
                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                <button class="btn btn-primary proceedToPayment col-12 col-md-5" id="save" type="button" onclick="mergeRoom()">Proceed</button>
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
  $('#RsvpMenu').attr("data-active","true");
  $('#RsvpNav').addClass('show');
  $('#mergeRoomMenu').addClass('active');
  merge_room_id = '<?= $merge_room_id ?>';

function mergeRoom()
{
    
  
    data = new FormData();
    data.append('rsvp_id', $('#rsvp_id').val());
    data.append('action', $('#action').val());
    if($('#action').val()=='1') {

        data.append('merge_id', $('#merge_id').val());

    }
    if($('#action').val()=='2') {

        data.append('merge_id', $('#change_merge_id').val());

    }
    
    loadingoverlay('info',"Loading","Please Wait...");
    $.ajax({
              url: '<?=BASEURL;?>Rsvp/ProceedMerge/', 
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
                      //openSuccess(newResp['response'])  
                  }else
                  {
                      loadingoverlay('error','Error',newResp['response']);
                  }
                return false;
              }
          });   
    
}

function changeRoom(rsvp_id)
{


    var action = $('#action').val();
    $.ajax({
        url: "<?=BASEURL?>Rsvp/GetMergeAvailableRooms",
        type: "post",
        data: {'rsvp_id':rsvp_id,'action':action} ,
        success: function (response) {

            var newResp = JSON.parse(response);
            $('.merge_id').html("").html(newResp);
            getLeftCardDetails();
  
           
        } 
    });  



}  


function changeAction(action)
{

   $('.merge_id').html("");

   $.ajax({
        url: "<?=BASEURL?>Rsvp/Rooms",
        type: "post",
        data: {'action':action} ,
        success: function (response) {

            var newResp = JSON.parse(response);
            $('#rsvp_id').html("").html(newResp);
            getLeftCardDetails(); 
            $('.right-card').hide();
            if(action == '1') {

                $('.merge_select_div').show();  
                $('.change_select_div').hide(); 
                $('.merge_id').html("<option value=''>Merge With</option>"); 

            }
            else if(action == '2') {

                $('.merge_select_div').hide();  
                $('.change_select_div').show();  
                $('.merge_id').html("<option value=''>Change With</option>"); 

            }else{

                $('.merge_select_div').hide();  
                $('.change_select_div').hide();  

            }


  
           
        } 
    });

       



}

getLeftCardDetails();
function getLeftCardDetails()
{

    var rsvp_id = $('#rsvp_id').val();
    $('.left-card').show();
    if(!rsvp_id) {
        
        $('.left-card').hide();
        return false;

    } 

     //get details
    CreateCardDetails('left-card',rsvp_id,'');

    



}
if(merge_room_id) {
    
    CreateCardDetails('right-card',merge_room_id,'2');

}




$('#change_merge_id').change(function () {

  CreateCardDetails('right-card',$(this).val(),'1');
   
});

$('#merge_id').change(function () {

  CreateCardDetails('right-card',$(this).val(),'2');
   
});

function CreateCardDetails(card_body,id,action)
{
    
    $('.'+card_body).show();
    if(!id) {
        
        $('.'+card_body).hide();
        return false;

    } 

    $.ajax({
        url: "<?=BASEURL?>Rsvp/GetCardDetails",
        type: "post",
        data: {'rsvp_id':id,'action':action} ,
        success: function (response) {

            var newResp = JSON.parse(response);
            $('.'+card_body+'-body').show();
            $('.'+card_body+'-body').html("").html(newResp);
            
           
        } 
    });   

}











 

  
</script>

