<?php 
use inc\Root;
$this->mainTitle = 'Leave Settings';
$this->subTitle  = 'Add Leave Type';

$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

$create_permission  = in_array(69,$this->admin_services) || $this->admin_role == '1' ? true : false;


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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Leave/">Leave Type</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Leave Name</label>
                                                <input type="text" name="name" id="name" value="" class="form-control" placeholder="Enter Leave Name">                                 
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Maximum Leave (In Days)</label>
                                                <input type="text" name="allowed_count" id="allowed_count" value="" class="form-control" placeholder="Enter Leave Count">                                 
                                            </div>

                                           <input type="hidden" name="countID" id="countID" value="1">
                                          
                                            <div class="col-md-4  text-center" style="margin-top: 2%;">
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
</div>

<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>


<script type="text/javascript">

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#LeaveMenu').attr("data-active","true");
  $('#LeaveNav').addClass('show');
  $('#LeaveList').addClass('active');
  $('#save').click(function(){

    data = new FormData();
    
  
    data.append('name', $('#name').val());
    data.append('allowed_count', $('#allowed_count').val());
    data.append('editID', "<?=$id?>");


     loadingoverlay('info',"Please Wait...","Loading....");
    $.ajax({
        url: '<?=BASEURL;?>Leave/AddLeaveType/', 
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
                 openSuccess(newResp['response'],'<?=BASEURL;?>Leave/Index')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 
});





</script>
