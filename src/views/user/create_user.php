
<?php 
use inc\Root;
$this->mainTitle = 'User Management';
$this->subTitle  = 'Create User';

if($user_data != ''){ 
    $button_text= 'Update'; 
}else{
    $button_text = 'Create';
}
$user_id        = !empty($user_data) ? $user_data['user_id'] : '';
$first_name     = !empty($user_data) ? $user_data['first_name'] : '';
$last_name      = !empty($user_data) ? $user_data['last_name'] : '';
$username       = !empty($user_data) ? $user_data['username'] : '';
$email          = !empty($user_data) ? $user_data['email'] : '';
$transpin       = !empty($user_data) ? $user_data['transpin'] : '';
$mobileno       = !empty($user_data) ? $user_data['mobileno'] : '';
$gender         = !empty($user_data) ? $user_data['gender'] : '';
$dob            = !empty($user_data) ? date('d-m-Y',$user_data['dob']) : '';
$nationality    = !empty($user_data) ? $user_data['nationality'] : '';
$user_permissions = !empty($user_data) ? explode(',',$user_data['permission']) : '';
$position       = !empty($user_data) ? $user_data['position'] : '';

$genderArr = ['1'=>'Male','2'=>'Female'];




?>

<style type="text/css">
  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }
  .form-control{
    height: 40px;
  }
  .t-group-item {
    color: #fff;
    background-color: #888ea8;
    border-color: transparent;
    box-shadow: 0 1px 15px 1px rgba(52, 40, 104, 0.15);
    border: 1px solid #e0e6ed;
    padding: 10px 12px;
}
  .info-icon {
    border-radius: 50%;
    background: #888ea8;
    display: inline-block;
    padding: 15px;
    margin-bottom: 20px;
    margin-top:-1.5%;
    margin-left: 42%;
}
.info-icon svg {
    width: 50px;
    height: 50px;
    stroke-width: 1px;
    color: #d3d3d3;
}
svg {
    overflow: hidden;
    vertical-align: middle;
}

.card-body {
    width: 98% !important;
}
.panel-body .box-shadow {
  margin: 0px auto;
}

.sub_permission {
  margin-left: 40px;
}

.col-md-3.col-sm-12.statbox.widget.box.box-shadow {
  overflow: auto;
  height: 559px;
}

.step-2-permission{
    display: none;
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

</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-12">
                <div class="card">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=BASEURL;?>User/Index/">User list</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create User</a></li>
                        </ol>
                    </nav>
                    <div class="card-body">
                        <div class="row" >
                            <form role="form" id="regiseterNewUser" method="post" class="col-md-12">
                                <div class="step-1-user">
                                    <div class="panel-body row">
                                        <div class="col-md-5 col-sm-12 statbox widget box box-shadow">
                                                <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?=$user_id?>">

                                            <div class="col-sm-12 col-md-12">
                                                <label>User Role<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12">
                                                <?php $selected = '';?>
                                                <select class="form-control custom-select roleSel" name="role_id" id="role_id">
                                                    <option value="">Select User Role</option>
                                                    <?php
                                                    foreach ($roleArr as $key =>$value) { 
                                                        $selected = ($key==$user_data['role_id']) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                                                    <?php }
                                                    ?>

                                                </select>   
                                            </div>

                                            <div class="col-sm-12 col-md-12 roomtabletfooter" style="display:none;">
                                                <label>Room <span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 roomtabletfooter" style="display:none;">
                                                <select class="form-control custom-select " name="room_id" id="room_id">
                                                    <option value="">Select Room</option>
                                                    <?php
                                                    foreach ($roomArr as $key =>$value) { 
                                                        
                                                        ?>
                                                        <option value="<?=$value['id']?>"><?=$value['room_no']?></option>
                                                    <?php }
                                                    ?>

                                                </select>   
                                            </div>

                                            <div class="col-sm-12 col-md-12 normalusers">
                                                <label>First Name<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normalusers">
                                                <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter First Name" value="<?=$first_name?>">
                                            </div>

                                            <div class="col-sm-12 col-md-12 normalusers">
                                                <label>Last Name<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normalusers">
                                                <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter Last Name" value=<?=$last_name?>>
                                            </div>
                                            <div class="col-sm-12 col-md-12 normalusers">
                                                <label>Nick Name<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normalusers">
                                                <input type="text" id="nick_name" name="nick_name" class="form-control" placeholder="Enter Nick Name" value ="">
                                            </div>

                                            <div class="col-sm-12 col-md-12 normalusers">
                                                <label>Username<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normalusers">
                                                <input type="text"  name="username" class="form-control input-sm" placeholder="Enter Username" value=<?=$username?>>
                                            </div>
                                            <div class="col-sm-12 col-md-12 normalusers">
                                                <label>NRIC/Passport No/FIN No<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normalusers">
                                                <input type="text"  name="passport_number" class="form-control input-sm" placeholder="Enter Passport Number" >
                                            </div>
                                            <div class="col-sm-12 col-md-12 ">
                                               <label>Password<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="input-group form-group col-md-12" id="show_hide_password">
                                                <input type="password"  name="password" class="form-control input-sm" placeholder="Enter Password" id="password">
                                                <div class="input-group-append">
                                                       <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-12 col-md-12 ">
                                              <label>Confirm Password<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="input-group form-group col-12 " id="show_hide_password1">
                                                <input type="password"  name="ConfirmPassword" class="form-control input-sm" placeholder="Enter Confirm Password" id="confirm_password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-5 col-sm-12 statbox widget box box-shadow normalusers">

                                            <div class="col-sm-12 col-md-12 normalusers">
                                                <label>Department<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normalusers">
                                                <select class="form-control custom-select department" name="department" id="department">
                                                    <option value="">Select Department</option>
                                                    <?php
                                                    foreach ($departments as $key =>$value) { 
                                                        ?>
                                                        <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                                    <?php }
                                                    ?>

                                                </select>   
                                            </div>

                                            <div class="col-sm-12 col-md-12 normalusers">
                                                <label>Position<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normalusers">
                                                <select class="form-control custom-select position" name="position" id="position">
                                                    <option value="">Select Position</option>
                                                    

                                                </select>   
                                            </div>
                                            
                                            <div class="col-sm-12 col-md-12">
                                                <label>Email<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" name="email" id="email" class="form-control input-sm" placeholder="Enter Email" value=<?=$email?> >
                                            </div>

                                            <div class="col-sm-12 col-md-12">
                                                <label>Mobile Number 1<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" name="mobileno" id="mobileno" class="form-control input-sm" placeholder="Enter Mobile number" value=<?=$mobileno?> >
                                            </div>
                                            <div class="col-sm-12 col-md-12">
                                                <label>Mobile Number 2<span style="color:red;"></span></label>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" name="mobileno2" id="mobileno2" class="form-control input-sm" placeholder="Enter second Mobile number" value="" >
                                            </div>
                                            
                                            
                                            <div class="col-sm-12 col-md-12">
                                                <label>Gender<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="input-group form-group col-md-12">
                                                <?php $selected = '';?>
                                                <select class="form-control custom-select langSel" name="gender" id="gender">
                                                    <option value="">Select Gender</option>
                                                    <?php
                                                    foreach ($genderArr as $key =>$value) { 
                                                        $selected = ($key==$user_data['gender']) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-12 col-md-12">
                                                <label>Date Of Birth<span style="color:red;">*</span></label>
                                            </div>
                                            <input id="date1" name="date1" type='date' min='1899-01-01' max='2000-13-13' class="input-group form-control" type="text" placeholder="" value=<?=$dob?>>

                                            <div class="col-sm-12 col-md-12">
                                                <label>Nationality<span style="color:red;">*</span></label>
                                            </div>
                                            <div class=" input-group form-group col-md-12">

                                                <select class="input-group form-control custom-select langSel" name="countrycode" id="countrycode">
                                                    <option value="">Select Nationality</option>
                                                    <?php
                                                    foreach ($country as $key =>$value) { 
                                                        $selected = ($key==$user_data['nationality']) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?=$value['id']?>" <?=$selected?>><?=$value['nicename']?>-<?=$value['iso']?></option>
                                                    <?php }
                                                    ?>
                                                </select>  
                                            </div>
                                            <?php if(empty($user_data)){?>
                                            <div class="col-sm-12 col-md-12">
                                                <label>Transaction Pin<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="input-group form-group col-md-12">
                                                <input type="text" name="transpin" id="transpin" class="form-control input-sm" placeholder="Enter Transaction Pin" value=<?=$transpin?> >
                                            </div>
                                        <?php }?>

                                        </div>
                                                     
                                    </div><br><br>
                                    <div class="panel-footer normalusers">
                                        <div class="form-actions text-center">
                                            <button type="button" class="btn btn-info proceedToNext col-12 col-md-3">&nbsp;Next</button>
                                        </div>
                                    </div>

                                    <div class="panel-footer roomtabletfooter" style="display:none;">
                                        <div class="form-actions text-center" onclick="createRoomTablet()">
                                            <button type="button" class="btn btn-info  col-12 col-md-3">&nbsp;Save</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="step-2-permission">
                                    <div class="panel-body row">
                                        <div class="col-md-5 col-sm-12 statbox widget box box-shadow">
                                            <div id="" class="col-xl-12 col-lg-12 layout-spacing">
                                                <div class="t-group-item ">
                                                   <span class="fa fa-cog"></span>&nbsp;Select Service Group
                                                </div>
                                                <div class="info-icon">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                                </div>
                                                <div class="row col-12">
                                                    <div class="n-chk col-12">
                                                        <label class="new-control new-checkbox checkbox-danger" style="color:#515365;">
                                                            <input type="checkbox" class="" id="checkallmem">
                                                            &nbsp;<?=Root::t('app','all_txt')?>
                                                        </label>
                                                    </div>
                                                    <br>
                                                    <?php foreach ($ServicesAr as $key => $val) { ?>
                                                    <div class="n-chk col-md-12" style="margin-top: 5px;">
                                                        <div class="new-control new-checkbox checkbox-info" style="color:#515365;">
                                                            <input type="checkbox" name="" class="checkmem" id="mainpermission_<?= $val['id']?>"  value="<?=$val['id'];?>" onclick="changeMainPermission(<?= $val['id']?>)" data-id="<?= $val['id']?>">
                                                            &nbsp;<?=$val['master_name'];?>
                                                        </div>
                                                    </div><br>
                                                    <div class="n-chk sub_permission col-md-12" style="margin-top: 5px;">

                                                    <?php $checked = ''; 
                                                        foreach ($subService as $k => $v) { 
                                                            if($v['master_id'] == $val['id']){
                                                                if(!empty($user_permissions)){
                                                                    if(in_array($v['id'], $user_permissions)){
                                                                        $checked = 'checked';
                                                                    }
                                                                }
                                                                ?>
                                                                <div class="new-control new-checkbox checkbox-info" style="color:#515365;">
                                                                    <input type="checkbox" name="permission[]" class="checkmem pull-left subpagePermission subpagepermision_<?= $val['id'] ?>" id="subpagepermision_<?= $v['id'] ?>" value="<?=$v['id'];?>" onclick="changePermission(<?= $v['id']?>)" data-id="<?= $v['id']?>" <?=$checked?>></p>
                                                                    &nbsp;<?=$v['service_name'];?>
                                                                </div>
                                                                
                                                            <?php  }
                                                            } ?>
                                                    </div><br>
                                                    <?php  } ?>
                                                </div>
                                            </div>
                                        </div>              
                                    </div><br><br>
                                    <div class="panel-footer">
                                        <div class="form-actions text-center">
                                            <button type="button" class="btn btn-info proceedToCreate col-12 col-md-3">&nbsp;<?=$button_text?></button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>


<?php

$error_dash = Root::t('app','error_dash');
$success    = Root::t('app','suucess_txt');
$okay       = Root::t('app','okay_btn'); 

?>


<script>
$('#accordionExample').find('li a').attr("data-active","false");
$('#userMenu').attr("data-active","true");
$('#userNav').addClass('show');
$('#createuser').addClass('active');
$("#checkallmem").change(function () {
    $(".checkmem").prop('checked', $(this).prop("checked"));
});
var f1 = flatpickr(document.getElementById('date1'),{
    dateFormat:"d-m-Y",
    minDate: "01-01-1899",
    maxDate: 'today'
});

$(function () {
    $('.proceedToNext').click(function(){
        postdata = $('#regiseterNewUser').serializeArray();
        
        $.post('<?=BASEURL;?>User/ValidateUser/',postdata,function(response){  
            console.log(response);
            newResp = JSON.parse(response);
            if(newResp['status']=='success'){
                //openSuccess(newResp['response'],'<?=BASEURL;?>User/Index/') 
                $('.step-1-user').css('display', 'none');
                $('.step-2-permission').css('display', 'block');

            }else{
                loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
            }
           return false;
        });
    });

});


$(function () {

    $('.proceedToCreate').click(function(){
        postdata = $('#regiseterNewUser').serializeArray();
       
        var checked=$('#regiseterNewUser input[type=checkbox]:checked').length; 
        if(checked==0) {
           postdata.push({'name':'permission','value':""});
        }
        $.post('<?=BASEURL;?>User/AddUser/',postdata,function(response){  
            console.log(response);
            newResp = JSON.parse(response);
            if(newResp['status']=='success'){
                openSuccess(newResp['response'],'<?=BASEURL;?>User/Index/') 
            }else{
                loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
            }
        
           return false;
        });
    });

});

$(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass( "fa-eye-slash" );
          $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
    $("#show_hide_password1 a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password1 input').attr("type") == "text"){
            $('#show_hide_password1 input').attr('type', 'password');
            $('#show_hide_password1 i').addClass( "fa-eye-slash" );
            $('#show_hide_password1 i').removeClass( "fa-eye" );
        }else if($('#show_hide_password1 input').attr("type") == "password"){
            $('#show_hide_password1 input').attr('type', 'text');
            $('#show_hide_password1 i').removeClass( "fa-eye-slash" );
            $('#show_hide_password1 i').addClass( "fa-eye" );
        }
    });
})


function changeMainPermission(id){
    if ($('#mainpermission_'+id).is(':checked')) {
        $('.subpagepermision_'+id).prop('checked', true);    
    } else {
         //$(this).prop('checked',true);
        $('.subpagepermision_'+id).prop('checked', false);
        $('#checkallmem').prop('checked', false); 

    }
}

function changePermission(id){
    var all_permisions_checked = '1'
    $('.subpagepermision_'+id).each(function () {
         if($(this).is(':checked')) {
         }else{
          all_permisions_checked = 0;
         }
    })
    if(all_permisions_checked == '1') {
        $('#mainpermission_'+id).prop('checked', true); 
    }else{
        $('#mainpermission_'+id).prop('checked', false); 
        $('#checkallmem').prop('checked', false);
    }
  }

$("#role_id").change(function () {



    if($('#role_id').val() == '6') {

        $('.normalusers').hide();
        $('.roomtabletfooter').show();
        return false;

        

    }
    $('.normalusers').show();
    $('.roomtabletfooter').hide();
    $('.subpagePermission').prop('checked', false);
    var userType = $('#role_id').val();
    $.post('<?=BASEURL?>User/getRolePermission',{'role_id':userType},function(response) {
        newResp = JSON.parse(response);
        temp = newResp['permissions'].split(",");
        $.each(temp,function(key,value){
        console.log(value);
            $('#subpagepermision_'+value).prop('checked', true);    
        })
    });
});


function createRoomTablet()
{
    var room_id = $('#room_id').val();
    var password = $('#password').val();
    var confirm_password = $('#confirm_password').val();

    data = new FormData();
    
  
    data.append('role_id', $('#role_id').val());
    data.append('room_id', room_id);
    data.append('password', password);
    data.append('confirm_password', confirm_password);


     loadingoverlay('info',"Please Wait...","Loading....");
    $.ajax({
        url: '<?=BASEURL;?>User/AddRoomTablet/', 
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
                 openSuccess(newResp['response'],'<?=BASEURL;?>User/Index/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 


}

$('#department').on('change',function () {
    var dept_id = $('#department').val();

    data = new FormData();
    data.append('dept_id', dept_id);

    $.ajax({
        url: '<?=BASEURL;?>User/getDepartmentPositions?dept_id='+dept_id, 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
            $('#position').html(""); 
            $('#position').html(response);
            return false;
        }
    }); 
});



</script>