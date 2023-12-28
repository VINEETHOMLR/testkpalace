<?php

use inc\Root;


if($user_data['status']=="0"){
    $statusField = '<label class="switch s-primary mb-0 pull-right"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$user_data['user_id'].','.$user_data['status'].');"></span></label>';
}else{
    $statusField = '<label class="switch s-primary mb-0 pull-right"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$user_data['user_id'].','.$user_data['status'].');"></span></label>';
}


$active1 = $active2 = $active3 = $active4 = $active5 = $active6 = $active7 = $active8 = '';
$show1 = $show2 = $show3 = $show4 = $show5 = $show6 = $show7 = $show8 =  '';
$flag = 0;
$genderArr = ['1'=>'Male','2'=>'Female'];

 if( ! empty($activeTab)){
    if($activeTab == '2fa'){
        $active6 = 'active';
        $flag=1; 
        $show6="show";
    }

    if($activeTab == 'meta'){
        $active7 = 'active';
        $flag=1; 
        $show7="show";
    }
    if($activeTab == 'leavebalance'){
        $active8 = 'active';
        $flag=1; 
        $show8="show";
    }
 }
$user_id        = !empty($user_data) ? $user_data['user_id'] : '';
$first_name     = !empty($user_data) ? $user_data['first_name'] : '';
$last_name      = !empty($user_data) ? $user_data['last_name'] : '';
$username       = !empty($user_data) ? $user_data['username'] : '';
$email          = !empty($user_data) ? $user_data['email'] : '';
$transpin       = !empty($user_data) ? $user_data['transpin'] : '';
$mobileno       = !empty($user_data) ? $user_data['mobileno'] : '';
$gender         = !empty($user_data) ? $user_data['gender'] : '';
$position       = !empty($user_data) ? $user_data['position'] : '';
$dob            = !empty($user_data) ? date('Y-m-d',$user_data['dob']) : '';
$nationality    = !empty($user_data) ? $user_data['nationality'] : '';
$user_permissions = !empty($user_data) ? explode(',',$user_data['permission']) : '';
$genderArr = ['1'=>'Male','2'=>'Female'];
 //var_dump(date($user_data['dob']));exit; 

$role = $user_data['role_id'];
$display = $role == '6' ? 'style=display:none;':'';
$roomstyle = $role == '6' ? '':'style=display:none;';

 

?>

<link href="<?=WEB_PATH?>assets/css/components/tabs-accordian/custom-tabs.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    .wallet-type{
        padding: 10px;
    }

    .breadcrumb-two .breadcrumb li a::before {
       content: none;
    }
    .breadcrumb-two{
        top: 0;
        position: absolute;
        left: 0;
    }
    .vertical-line-pill .nav-pills {
        width: 100%;
    }
    .dot{
        background: rgb(25, 185, 211);
        color: rgb(231, 81, 90);
        height: 12px;
        width: 12px;
        left: 0px;
        top: 0px;
        border-width: 0px;
        border-color: rgb(255, 255, 255);
        border-radius: 12px;
        float: left;
        margin: 5px;
    }
    .pwdDiv{
        margin-bottom: 30px;
        margin-left: 1px;
    }
    @media only screen and (min-width: 800px) {
      .padding30{
        padding-left: 30px;
       }
    }
    @media only screen and (min-width: 1000px) {
        .pwdDiv{
          margin: 0px 70px;
       }
    }
    .swal2-loading{
        background: none !important;
    }
    #depositTable_length,#depositTable_filter{
       display: none;
    }
    #withdrawTable_length,#withdrawTable_filter{
       display: none;
    }
    .table > thead > tr > th,.multi-table > tbody > tr > td {
      text-align: center;
    }
     .step-2-permission{
    display: none;
}
.bmaster{
    font-weight: bold;
    color:black;
}
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
          <div class="row layout-top-spacing">
            <div class="user-profile layout-spacing col-12">
                <div class="widget-content widget-content-area">
                     <div class="d-flex">
                          <div class="dot"></div> 
                      </div>
                      <div class="user-info-list padding30">

                          <div class="col-sm-12 col-md-12 row">
                              <div class="col-md-4 col-sm-12">
                                <label class="marginLeft"><?= Root::t('subadmin','user_text'); ?></label> : <label><?=$user_data['username'];?></label>
                                <br>
                                <label class="marginLeft">Staff ID</label> : <label><?=$user_data['staff_id'];?></label>                                                           
                            </div>
                              <div class="col-md-4 col-sm-12">
                                <label><?= Root::t('subadmin','name_text'); ?></label> : <label><?=$user_data['first_name']." ".$user_data['last_name'];?></label>
                                <br>
                                <label><?= Root::t('user','user_status'); ?></label> : <label><?=($user_data['status']==0) ? 'Active' : 'Inactive';?></label>
                              </div>
                          </div> 
                      </div>
                </div>
            </div>
                                          
            <div class="col-lg-12 col-12 layout-spacing">
                    <div class="widget-content widget-content-area vertical-line-pill">
                            <nav class="breadcrumb-two" aria-label="breadcrumb">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="<?=BASEURL;?>User/">User list</a></li>
                                  <li class="breadcrumb-item active"><a href="javascript:void(0);">Account Details</a></li>
                              </ol>
                            </nav>
                            <?php if( in_array(13, $this->admin_services) || ($this->admin_role==1)){ 
                                              echo $statusField;
                            }?>
                            <br><br>

                            <div class="row mb-4 mt-3">

                              <div class="col-sm-2 col-12">
                               
                                <div class="nav flex-column nav-pills mb-sm-0 mb-3 text-center mx-auto" id="v-line-pills-tab" role="tablist" aria-orientation="vertical">

                                    <?php if( in_array(14, $this->admin_services) || ($this->admin_role==1)){ if(empty($flag)){ $active1 = "active"; $flag=1; $show1="show"; } ?>
                                    <a class="nav-link <?=$active1?> mb-3" id="v-line-pills-home-tab" data-toggle="pill" href="#v-line-pills-home" role="tab" aria-controls="v-line-pills-home" aria-selected="true">Update Profile</a>

                                   <?php } if( in_array(15, $this->admin_services) || in_array(22, $this->admin_services) || ($this->admin_role==1)){ 
                                        if(empty($flag)){ $active2 = "active" ; $flag=1; $show2="show"; } ?>
                                    <a class="nav-link mb-3 <?=$active2?> text-center" id="v-line-pills-profile-tab" data-toggle="pill" href="#v-line-pills-profile" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Update Password</a>

                                  
                                   
                                    <?php if($user_data['role_id']!='6'){?>    
                                    <a class="nav-link mb-3 <?=$active2?> text-center" id="v-line-pills-profile-tab1" data-toggle="pill" href="#v-line-pills-profile1" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Update Transaction Pin</a>
                                    <?php }}
                                     ?>
                                    
                                    <?php if( (in_array(86, $this->admin_services) || in_array(78, $this->admin_services) || in_array(82, $this->admin_services) || ($this->admin_role==1)) && $user_data['role_id']!='6' ){  ?>
                                    <a class="nav-link mb-3  <?= $active8?> text-center" id="v-line-pills-leavebalance-tab" data-toggle="pill" href="#v-line-pills-leavebalance" role="tab" aria-controls="v-line-pills-leavebalance" aria-selected="false">Leave Balance</a>

                                   <?php }?>



                                </div>
                              </div>
                                
                              <div class="col-sm-9 col-12">
                                    <!---TAB STARTING ----PROFILE----->
                                    <div class="tab-content" id="v-line-pills-tabContent">

                                    <?php if( in_array(14, $this->admin_services) || ($this->admin_role==1)){ ?>
                                        <div class="tab-pane fade <?=$show1?> <?=$active1?>" id="v-line-pills-home" role="tabpanel" aria-labelledby="v-line-pills-home-tab">

                                          <div class="widget-content widget-content-area">
                                             
                                         <form role="form" id="regiseterNewUser" method="post" class="col-md-12">
                                <div class="step-1-user">
                                    <div class="panel-body row">
                                        <div class="col-md-6 col-sm-12 statbox widget box box-shadow">
                                                <input type="hidden" id="user_ids" name="user_id" class="form-control" value="<?=$user_id?>">

                                            <div class="col-sm-12 col-md-12">
                                                <label>User Role<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12">
                                                <?php $selected = '';?>
                                                <select class="form-control custom-select roleSel" name="role_id" id="role_id" onchange="changeRole($(this).val())">
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

                                            <div class="col-sm-12 col-md-12 roomtabletfooter" <?= $roomstyle ?>>
                                                <label>Room <span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 roomtabletfooter" <?= $roomstyle ?>>
                                                <select class="form-control custom-select " name="room_id" id="room_id">
                                                    <option value="">Select Room</option>
                                                    <?php
                                                    foreach ($roomArr as $key =>$value) { 

                                                        $selected = ($value['id']==$user_data['room_id']) ? 'selected' : '';
                                                        
                                                        ?>
                                                        <option value="<?=$value['id']?>" <?= $selected?>><?=$value['room_no']?></option>
                                                    <?php }
                                                    ?>

                                                </select>   
                                            </div>

                                            <div class="col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <label>First Name<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter First Name" value="<?=$user_data['first_name']?>">
                                            </div>

                                            <div class="col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <label>Last Name<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter Last Name" value="<?=$user_data['last_name']?>">
                                            </div>

                                            <div class="col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <label>Nick Name<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <input type="text" id="nick_name" name="nick_name" class="form-control" placeholder="Enter Nick Name" value="<?=$user_data['nick_name']?>">
                                            </div>

                                            <div class="col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <label>Username<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <input type="text"  name="username" class="form-control input-sm" placeholder="Enter Username" value="<?=$user_data['username']?>">
                                            </div>
                                            
                                            <div class="col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <label>Department<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <select class="form-control custom-select " name="department" id="department">
                                                    <option value="">Select Department</option>
                                                    <?php
                                                    foreach ($departments as $key =>$value) { 

                                                        $selected = ($value['id']==$user_data['department']) ? 'selected' : '';
                                                        
                                                        ?>
                                                        <option value="<?=$value['id']?>" <?= $selected?>><?=$value['name']?></option>
                                                    <?php }
                                                    ?>

                                                </select>   
                                            </div>

                                            <div class="col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <label>Position<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <select class="form-control custom-select " name="position" id="position">
                                                    <option value="">Select Position</option>
                                                    <?php
                                                    foreach ($positions as $key =>$value) { 

                                                        $selected = ($value['id']==$user_data['position']) ? 'selected' : '';
                                                        
                                                        ?>
                                                        <option value="<?=$value['id']?>" <?= $selected?>><?=$value['name']?></option>
                                                    <?php }
                                                    ?>

                                                </select>   
                                            </div>

                                          <!--   <div class="col-sm-12 col-md-12">
                                               <label>Password<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="input-group form-group col-md-12" id="show_hide_password">
                                                <input type="password"  name="password" class="form-control input-sm" placeholder="Enter Password">
                                                <div class="input-group-append">
                                                       <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-12 col-md-12">
                                              <label>Confirm Password<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="input-group form-group col-12" id="show_hide_password1">
                                                <input type="password"  name="ConfirmPassword" class="form-control input-sm" placeholder="Enter Confirm Password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                </div>
                                            </div> -->

                                            
                                        </div>  
                                        <div class="col-md-6 col-sm-12 statbox widget box box-shadow normaluser" <?= $display ?>>
                                            <div class="col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <label>NRIC/Passport No./FIN no<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 normaluser" <?= $display ?>>
                                                <input type="text"  name="passport_number" class="form-control input-sm" placeholder="Enter Passport number" value="<?=$user_data['passport_number']?>">
                                            </div>
                                            <div class="col-sm-12 col-md-12">
                                                <label>Email<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" name="email" id="email" class="form-control input-sm" placeholder="Enter Email" value="<?=$user_data['email']?>" >
                                            </div>

                                            <div class="col-sm-12 col-md-12">
                                                <label>Mobile Number<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" name="mobileno" id="mobileno" class="form-control input-sm" placeholder="Enter Mobile number" value="<?=$user_data['mobileno']?>">
                                            </div>
                                            <div class="col-sm-12 col-md-12">
                                                <label>Second Mobile Number<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" name="mobileno2" id="mobileno2" class="form-control input-sm" placeholder="Enter Second Mobile number" value="<?=$user_data['mobileno2']?>">
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
                                            <input id="datefield" name="date1" type='date' class="input-group form-control" type="text" placeholder="" value="<?=date('Y-m-d',$user_data['dob'])?>">

                                            <div class="col-sm-12 col-md-12">
                                                <label>Nationality<span style="color:red;">*</span></label>
                                            </div>
                                            <div class=" input-group form-group col-md-12">

                                                <select class="input-group form-control custom-select langSel" name="countrycode" id="countrycode">
                                                    <option value="">Select Nationality</option>
                                                    <?php
                                                    foreach ($country as $key =>$value) { 
                                                        $selected = ($value['id']==$user_data['nationality']) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?=$value['id']?>" <?=$selected?>><?=$value['nicename']?>-<?=$value['iso']?></option>
                                                    <?php }
                                                    ?>


                                                </select>  
                                            </div>
                                            <?php if(empty($user_data['transpin'])){?>
                                            <div class="col-sm-12 col-md-12">
                                                <label>Transaction Pin<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="input-group form-group col-md-12">
                                                <input type="text" name="transpin" id="transpin" class="form-control input-sm" placeholder="Enter Trans Pin" value=<?=$transpin?> >
                                            </div>
                                        <?php }?>

                                        </div>
                                                     
                                    </div><br><br>
                                    <div class="panel-footer normaluser" <?= $display ?>>
                                        <div class="form-actions text-center">
                                            <button type="button" class="btn btn-info proceedToNext col-12 col-md-3">&nbsp;Next</button>
                                        </div>
                                    </div>

                                    <div class="panel-footer roomtabletfooter" <?= $roomstyle ?>>
                                        <div class="form-actions text-center" onclick="updateRoomTablet()">
                                            <button type="button" class="btn btn-info col-12 col-md-3">&nbsp;Update</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="step-2-permission">
                                    <div class="panel-body row">
                                        <div class="col-md-12 col-sm-12 statbox widget box box-shadow">
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
                                                            <?php $have_all_permission = !empty($have_all_permision) ? 'checked' : '';?>
                                                            <input type="checkbox" class="" id="checkallmem" <?= $have_all_permission ?>>
                                                            &nbsp;<?=Root::t('app','all_txt')?>
                                                        </label>
                                                    </div>
                                                    <br>


                                             <?php $sub_checked ='';


                                                 
                                              foreach ($ServicesAr as $key => $val) {
                                                $all_checked = $val['all_checked'] ? 'checked':'';

                                               ?>
                                                   
                                                    <div class="n-chk col-md-12" style="margin-top: 5px;">
                                                        <div class="new-control new-checkbox checkbox-info" style="color:#515365;">
                                                            <input type="checkbox" name="" class="checkmem" id="mainpermission_<?= $val['id']?>"  value="<?=$val['id'];?>" onclick="changeMainPermission(<?= $val['id']?>)" data-id="<?= $val['id']?>"<?=$all_checked?>>
                                                            &nbsp;<label class="bmaster"><?=$val['master_name'];?></label>
                                                        </div>
                                                    </div><br>
                                                    <div class="n-chk sub_permission col-md-12" style="margin-top: 5px;">

                                                    <?php $checked = ''; 
                                                        foreach ($subService as $k => $v) {

                                                            if($v['master_id'] == $val['id']){
                                                                if(!empty($user_permissions)){
                                                                    if(in_array($v['id'], $user_permissions)){
                                                                        $checked = 'checked';
                                                                    }else{

                                                                        $checked = '';

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
                                           <a href=""> <button type="button" class="btn btn-info  col-12 col-md-3">Back</button></a>
                                       
                                            <button type="button" class="btn btn-info proceedToCreate col-12 col-md-3">Update</button>
                                        </div>

                                        
                                    </div>
                                </div>

                            </form>

                                          </div>
                                          <br>
                                                                  
                                       </div>
                                    <?php }  ?>


                                <!-----TAB ENDING PROFILE------->
                                <!-----TAB UPDATE PASSWORD------>

                                   <?php if( in_array(15, $this->admin_services) || in_array(22, $this->admin_services) || ($this->admin_role==1)){ ?>

                                    <div class="tab-pane fade  <?=$show2?> <?=$active2?>" id="v-line-pills-profile" role="tabpanel" aria-labelledby="v-line-pills-profile-tab">

                                        <div class='panel panel-default row'>
                                          <form method="post" id="pwdForm">
                                            <div class='panel-body statbox widget box box-shadow row col-sm-12 col-md-12 col-xl-12 pwdDiv'>
                                                <?php if( in_array(15, $this->admin_services) || ($this->admin_role==1)){ ?>
                                                <div class="col-md-12 col-xl-12">
                                                    <div class="widget-header">
                                                        <div class="row" style="margin-bottom: 20px;">
                                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
                                                                <h4>Update Password</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row col-md-12'>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px"><?=Root::t('subadmin','new_pass');?> <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-12 col-md-12 col-sm-12 col-12" id="show_hide_password">
                                                                        <input type="password" name="newpass"  class="form-control input-sm" id="newpass" placeholder="<?=Root::t('subadmin','new_pass');?>">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                            </div>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px"><?=Root::t('subadmin','con_pass');?> <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-12 col-md-12 col-sm-12 col-12" id="show_hide_password1">
                                                                        <input type="password" name="confpass"  class="form-control input-sm " id="confpass" placeholder="<?=Root::t('subadmin','con_pass');?>">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                        
                                                            </div>
                                                       
                                                    </div>
                                                </div>
                                            <?php } ?>
                                                    <input type="hidden" name="editId" value="<?=$user_data['user_id']?>">       
                                                
                                                    <div class="col-md-12 text-center">
                                                            <button class="btn btn-primary btn-sm  edit-text-shw  updatePass" id="updatePass" type="button"><span class="fa fa-save"></span>&nbsp;<?=Root::t('subadmin','update_pass')?></button>
                                                    </div>
                                                

                                               </div>
                                             </form>
                                    
                                         </div>
                                    </div>

                                <?php }  ?> 
                               
                                <div class="tab-pane fade  <?=$show2?> <?=$active2?>" id="v-line-pills-profile1" role="tabpanel" aria-labelledby="v-line-pills-profile-tab1">

                                        <div class='panel panel-default row'>
                                          <form method="post" id="pinForm">
                                            <div class='panel-body statbox widget box box-shadow row col-sm-12 col-md-12 col-xl-12 pwdDiv'>
                                                <?php if( in_array(15, $this->admin_services) || ($this->admin_role==1)){ ?>
                                                <div class="col-md-12 col-xl-12">
                                                    <div class="widget-header">
                                                        <div class="row" style="margin-bottom: 20px;">
                                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
                                                                <h4>Update Transaction Pin</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row col-md-12'>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px">New Pin <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-12 col-md-12 col-sm-12 col-12" id="show_hide_pin">
                                                                        <input type="password" name="newpin"  class="form-control input-sm" id="newpin" placeholder="New Transaction Pin">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                            </div>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px">Confirm Transaction Pin<span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-12 col-md-12 col-sm-12 col-12" id="show_hide_pin1">
                                                                        <input type="password" name="confpin"  class="form-control input-sm " id="confpin" placeholder="Confirm pin">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                        
                                                            </div>
                                                       
                                                    </div>
                                                </div>
                                            <?php } ?>
                                                    <input type="hidden" name="editId" value="<?=$user_data['user_id']?>">       
                                                
                                                    <div class="col-md-12 text-center">
                                                            <button class="btn btn-primary btn-sm  edit-text-shw  updatePin" id="updatePin" type="button"><span class="fa fa-save"></span>&nbsp;Update Transaction Pin</button>
                                                    </div>
                                                

                                               </div>
                                             </form>
                                    
                                         </div>
                                    </div>

                                    <?php if( in_array(15, $this->admin_services) || in_array(22, $this->admin_services) || ($this->admin_role==1)){ ?>

                                    <div class="tab-pane fade <?=$show8?> <?=$active8?>" id="v-line-pills-leavebalance" role="tabpanel" aria-labelledby="v-line-pills-leavebalance-tab">

                                    <div class="widget-content widget-content-area">
                                             
                                         <form role="form" id="leaveBalanceForm" method="post" class="col-md-12">
                                   <div class="">
                                    <div class="panel-body row">
                                        <div class="col-md-6 col-sm-12 statbox widget box box-shadow">
                                                

                                            

                                           
                                            <?php foreach($leaveTypes as $key=>$value){?>
                                            <div class="col-sm-12 col-md-12 ">
                                                <label><?= $value['leave_name']?><span style="color:red;"></span></label>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 ">
                                                <input type="text" name="leave_balance[<?= $value['id']?>]" class="form-control" placeholder="Enter the leave balance" value="<?=$value['leave_balance']?>">
                                            </div>
                                            <?php }?>
     
                                            
                                        </div>  
                                        
                                                     
                                    </div>
                                    <div class="panel-footer">
                                        <div class="form-actions text-center">
                                            <button type="button" class="btn btn-info col-12 col-md-3" onclick="updateLeavebalance(<?= $user_id ?>)">&nbsp;Update</button>
                                        </div>
                                    </div>

                                   
                                </div>
                                </form>
                            </div>

                                    </div>


                                <?php }  ?>


                        
                      </div>
                </div>
            </div>
        </div>
<?php

$error_dash = Root::t('app','error_dash');


?>
<script>


// var today = new Date();
// var dd = today.getDate();
// var mm = today.getMonth()+1; //January is 0!
// var yyyy = today.getFullYear();
//  if(dd<10){
//         dd='0'+dd
//     } 
//     if(mm<10){
//         mm='0'+mm
//     } 

// today = yyyy+'-'+mm+'-'+dd;
// document.getElementById("datefield").setAttribute("max", today);

$( function() {

      $('#accordionExample').find('li a').attr("data-active","false");
      $('#userMenu').attr("data-active","true");
      $('#userNav').addClass('show');
      $('#usersList').addClass('active');
      $("#checkallmem").change(function () {
    $(".checkmem").prop('checked', $(this).prop("checked"));
});

      $('#mobile').keyup(function() {
         $(this).val($(this).val().replace(/\D/, ''));
      });
 });

$(document).ready(function () {
     
    
    $('.proceedToUpdate').click(function(){
        var postdata = $('#formValidation').serializeArray();
        $.post('<?=BASEURL;?>Customer/Edit/',postdata,function(response){
           
            newResp = JSON.parse(response);
            if(newResp['status']=='success')
            {
                openSuccess(newResp['response'], '<?=BASEURL ?>User/Account/?user=<?=$user_data['user_id']?>')
            }
            else
            {
               loadingoverlay('error','Error',newResp['response']);
            }
             return false;
        });
        return false;
    });

    
      
   

});

$('#updatePass').click(function(){
        

        postdata = $('#pwdForm').serializeArray();
 
        $.post('<?= BASEURL ?>User/Resetpass/',postdata,function(response){ 
    console.log(response);

          newResp = JSON.parse(response);

            
            if(newResp['status'] == 'success'){
                openSuccess(newResp['response'], '<?=BASEURL ?>User/')
            }else{ 
                loadingoverlay('error','Error',newResp['response']);
            }
        });
        return false;
    });
    

$('#updatePin').click(function(){
        

        postdata = $('#pinForm').serializeArray();
 
        $.post('<?= BASEURL ?>User/Resetpin/',postdata,function(response){ 
        console.log(response);
   
          newResp = JSON.parse(response);

            
            if(newResp['status'] == 'success'){
                openSuccess(newResp['response'], '<?=BASEURL ?>User/')
            }else{ 
                loadingoverlay('error','Error',newResp['response']);
            }
        });
        return false;
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

    
});


$(document).ready(function() {
      $("#show_hide_pin a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_pin input').attr("type") == "text"){
          $('#show_hide_pin input').attr('type', 'password');
          $('#show_hide_pin i').addClass( "fa-eye-slash" );
          $('#show_hide_pin i').removeClass( "fa-eye" );
        }else if($('#show_hide_pin input').attr("type") == "password"){
          $('#show_hide_pin input').attr('type', 'text');
          $('#show_hide_pin i').removeClass( "fa-eye-slash" );
          $('#show_hide_pin i').addClass( "fa-eye" );
        }
      });
      $("#show_hide_pin1 a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_pin1 input').attr("type") == "text"){
          $('#show_hide_pin1 input').attr('type', 'password');
          $('#show_hide_pin1 i').addClass( "fa-eye-slash" );
          $('#show_hide_pin1 i').removeClass( "fa-eye" );
        }else if($('#show_hide_pin1 input').attr("type") == "password"){
          $('#show_hide_pin1 input').attr('type', 'text');
          $('#show_hide_pin1 i').removeClass( "fa-eye-slash" );
          $('#show_hide_pin1 i').addClass( "fa-eye" );
        }
      });

    
});
$(function () {
    $('.proceedToNext').click(function(){
        postdata = $('#regiseterNewUser').serializeArray();
        
        $.post('<?=BASEURL;?>User/ValidateUpdateUser/',postdata,function(response){  
            
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
        $.post('<?=BASEURL;?>User/AddUserUpdation/',postdata,function(response){  
            
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



 function switchStatus(id,status){
             var url = 'BlockCustomer';
            if(status==1){
                var btn  = "Activate"; var txt = "Are you sure want to proceed ?";
                var swClass ='';
                var changedToStatus = 0 ;
            }else{
                var btn  = "Block";  var txt = "Are you sure want to proceed ?";
                var swClass ='';
                var changedToStatus = 1 ;
            }

            swal({
                 title: btn,
                 text: txt,
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonText: btn,
                 padding: '2em'
                }).then(function(result) {
                    if (result.value) {
                       loadingoverlay('info',"Please wait..","loading...");
                      $.post('<?=BASEURL;?>User/'+url,{'uid':id,'status':changedToStatus},function(response){
                            hideoverlay();
                            newResp = JSON.parse(response);
                            if (newResp['status'] == 'success') {
                                openSuccess(newResp['response']);
                            } else {
                                loadingoverlay("error", "Error", newResp['response']);
                            }
                        });
                        return false;
                    }
                    else{
                        location.reload();
                    }
                })
    }


    
</script>
<script type="text/javascript">
    function updateRoomTablet()
    {
        var room_id = $('#room_id').val();
        data = new FormData();
        
      
        data.append('role_id', $('#role_id').val());
        data.append('room_id', room_id);
        data.append('user_id', '<?= $user_data['user_id']?>');
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

    function changeRole()
    {

        if($('#role_id').val() == '6') {

            $('.normaluser').hide();
            $('.roomtabletfooter').show();
            

        

        }else{

            $('.normaluser').show();
            $('.roomtabletfooter').hide();

        }
        


    }

flatpickr(document.getElementById('datefield'), {
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d-m-Y",
    maxDate: "today"
});

function updateLeavebalance(id)
{
        
    postdata = $('#leaveBalanceForm').serializeArray();
    postdata.push({'name':'staff_id','value':id});
    $.post('<?=BASEURL;?>User/UpdateLeaveBalance/',postdata,function(response){  
            

            
        newResp = JSON.parse(response);
        if(newResp['status']=='success'){
            openSuccess(newResp['response'],'<?=BASEURL;?>User/Index/') 
        }else{
            loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
        }
        
        return false;
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



 
