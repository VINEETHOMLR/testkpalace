<?php

use inc\Root;

if($info['status']=="0"){
    $statusField = '<label class="switch s-primary mb-0 pull-right"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$info['id'].','.$info['status'].');"></span></label>';
}else{
    $statusField = '<label class="switch s-primary mb-0 pull-right"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$info['id'].','.$info['status'].');"></span></label>';
}

$active1 = $active2 = $active3 = $active4 = $active5 = $active6 = $active7 = '';
$show1 = $show2 = $show3 = $show4 = $show5 = $show6 = $show7 = '';
$flag = 0;

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
 }

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
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
          <div class="row layout-top-spacing">
            <div class="user-profile layout-spacing col-12">
                <div class="widget-content widget-content-area">
                     <div class="d-flex">
                          <div class="dot"></div> <h4><b> <?=ucwords($info['uniqueid'])?></b></h4>
                      </div>
                      <div class="user-info-list padding30">

                          <div class="col-sm-12 col-md-12 row">
                              <div class="col-md-4 col-sm-12">
                                <label class="marginLeft"><?= Root::t('subadmin','user_text'); ?></label> : <label><?=$info['username'];?></label>
                                <br>
                                <label><?= Root::t('subadmin','name_text'); ?></label> : <label><?=ucwords($extra['given_name']);?></label>                               
                            </div>
                              <div class="col-md-4 col-sm-12">
                                <label><?= Root::t('user','user_status'); ?></label> : <label><?=($info['status']==0) ? 'Active' : 'Inactive';?></label>
                              </div>
                          </div> 
                      </div>
                </div>
            </div>
            
            <div class="col-lg-12 col-12 layout-spacing">
                    <div class="widget-content widget-content-area vertical-line-pill">
                            <nav class="breadcrumb-two" aria-label="breadcrumb">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="<?=BASEURL;?>Customer/">User list</a></li>
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
                                        //if(empty($flag)){ $active2 = "active" ; $flag=1; $show2="show"; } ?>
                                    <!-- <a class="nav-link mb-3 <?=$active2?> text-center" id="v-line-pills-profile-tab" data-toggle="pill" href="#v-line-pills-profile" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Update Password</a> -->

                                    <?php } 
                                    if (in_array(11, $this->admin_services) || ($this->admin_role==1)){  
                                        if(empty($flag)){ $active3 = "active" ; $flag=1; $show3="show"; } ?>
                                    <a class="nav-link mb-3 <?=$active3?> text-center" data-toggle="pill" href="#walletTab" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Wallet Management</a>
                                    <?php } ?>

                                </div>
                              </div>
                                
                              <div class="col-sm-9 col-12">
                                    <!---TAB STARTING ----PROFILE----->
                                    <div class="tab-content" id="v-line-pills-tabContent">

                                    <?php if( in_array(14, $this->admin_services) || ($this->admin_role==1)){ ?>
                                        <div class="tab-pane fade <?=$show1?> <?=$active1?>" id="v-line-pills-home" role="tabpanel" aria-labelledby="v-line-pills-home-tab">

                                          <div class="widget-content widget-content-area">
                                             
                                            <form id="formValidation" class="form-horizontal" role="form">
                                                <div class="row col-12"><h4>Update Profile</h4></div>
                                                
                                                <div class="row col-md-12">
                                                    <div class="form-group col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Username</label>
                                                            <input type="text"  name="name" id="name" value="<?=ucwords($info['username']);?>" class="form-control" placeholder="Username" disabled>       
                                                        </div>
                                                    </div>
                                                      <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Surname</label>
                                                            <input type="text" name="Surname" value="<?=$extra['surname']?>" class="form-control" id="Surname" placeholder="Enter Surname">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                
                                                <div class="row col-md-12"> 
                                                     <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Nickname</label>
                                                            <input type="text" name="Nickname" value="<?=$extra['nickname']?>" class="form-control" id="Nickname" placeholder="Enter Nickname">
                                                        </div>
                                                    </div>

                                                    
                                                     <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Given Name</label>
                                                            <input type="text" name="Gname" value="<?=$extra['given_name']?>" class="form-control" id="name" placeholder="Enter Given Name">
                                                        </div>
                                                    </div>
                                                    
                                                   
                                                   <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Email ID</label>
                                                            <input type="email" data-validation="email"  name="Email" value="<?=$info['email']?>" class="form-control" id="Email" placeholder="Email ID">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="col-md-12">
                                                            <label><?=Root::t('user','user_gender');?></label><br>
                                                            <div class="n-chk">
                                                                <label class="new-control new-radio new-radio-text radio-classic-primary">
                                                                    <input type="radio" class="gender new-control-input" id="radio-1" name="gender" value="0" <?php if($extra['gender']==0){echo 'checked';}?>>
                                                                    <span class="new-control-indicator"></span><span class="new-radio-content">Male</span>
                                                                </label>
                                                                <label class="new-control new-radio new-radio-text radio-classic-primary">
                                                                    <input type="radio" class="gender new-control-input" id="radio-1" name="gender" value="1" <?php if($extra['gender']==1){echo 'checked';}?>>
                                                                    <span class="new-control-indicator"></span><span class="new-radio-content">Female</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                     <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Country Code</label>
                                                             <select class="form-control custom-select langSel" name="Countrycode" id="Countrycode">
                                                                
                                                                <option value="">Select Country</option>
                                                         <?php
                                                         foreach ($countrycode as $key => $value) {
                                                          $selected= ($value['id'] == $info['countrycode']) ? "selected" : "";
                                                          echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['nicename'].' - '.$value['iso'].'</option>';
                                                         }
                                                         ?>
                                                        
                                                        
                                                            

                                                      </select>     
                                                        </div>
                                                    </div>

                                                      <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Mobile Code</label>
                                                             <select class="form-control custom-select langSel" name="Mobilecode" id="Mobilecode">
                                                                <option value="">Select Mobilecode</option>
                                                         <?php
                                                         foreach ($countrycode as $key => $value) {

                                                          $selected= ($value['id'] == $extra['mobile_code']) ? "selected" : "";
                                                          echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['phonecode'].' - '.$value['nicename'].'</option>';
                                                         }
                                                         ?>
                                                        
                                                        
                                                            

                                                      </select>     
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Mobile No</label>
                                                            <input type="text" name="Mobile" value="<?=$extra['mobile']?>" class="form-control" id="Mobile" placeholder="Mobile No">
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Date Of Birth</label>
                                                            <input type="text" name="Date1" value="<?=$extra['dob']?>" class="form-control" id="Date1" placeholder="Enter Date Of Birth">
                                                        </div>
                                                    </div>
                                                      <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Language</label>
                                                             <select class="form-control custom-select langSel" name="Language" id="Language">
                                                                <option value="">Select Language</option>
                                                         <?php
                                                         foreach ($langcode as $key => $value) {
                                                          $selected = ($value['id'] == $extra['language']) ? "selected" : "";
                                                          echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['lang_name'].' </option>';
                                                         }
                                                         ?>
                                                        
                                                        
                                                            

                                                      </select>     
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Allergies</label>
                                                            <select class="form-control custom-select" id="allergies" name="allergies[]" multiple="">
                                                              <option>Select</option>
                                                              <?php $exploded = explode(',',$extra['allergies']);foreach($allergies as $key=>$value){

                                                                $selected = in_array($value['id'],$exploded) ? 'selected':'';
                                                                   
                                                              ?>
                                                                <option value="<?= $value['id']?>" <?= $selected  ?>><?= $value['name']?></option>
                                                              <?php }?>
                                                  
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Assistant Name</label>
                                                            <input type="text" name="Assname" value="<?=$extra['assistant_name']?>" class="form-control" id="Assname" placeholder="Enter Assistant Name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Assistant Mobile</label>
                                                            <input type="text" name="Assmobile" value="<?=$extra['assistant_mobile']?>" class="form-control" id="Assmobile" placeholder="Enter Assistant Mobile">
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Assistant Email</label>
                                                            <input type="email" name="Assemail" value="<?=$extra['assistant_email']?>" class="form-control" id="Assemail" placeholder="Enter Assistant Email">
                                                        </div>
                                                    </div>
                                                     <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Account Manager</label>
                                                            <input type="email" name="Accman" value="<?=$extra['account_manager']?>" class="form-control" id="Accman" placeholder="Enter Account Manager">
                                                        </div>
                                                    </div>
                                                     <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Referral</label>
                                                            <input type="email" name="Referal" value="<?=$extra['referral']?>" class="form-control" id="Referal" placeholder="Referral Id" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <div class="col-md-12">
                                                            <label>Remarks</label>
                                                            <input type="text" name="Remarks" value="<?=$extra['remarks']?>" class="form-control" id="Remarks" placeholder="Enter Remarks">
                                                        </div>
                                                    </div>


                                                </div>
                                              
                                       <input type="hidden" name="editId" value="<?=$info['id']?>"> 
                                                <div class="col-md-12  text-center">
                                                    <button class="btn btn-primary col-md-4 col-12 proceedToUpdate" type="button">Update</button>
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
                                                    <input type="hidden" name="editId" value="<?=$info['id']?>">       
                                                
                                                    <div class="col-md-12 text-center">
                                                            <button class="btn btn-primary btn-sm  edit-text-shw  updatePass" id="updatePass" type="button"><span class="fa fa-save"></span>&nbsp;<?=Root::t('subadmin','update_pass')?></button>
                                                    </div>
                                                

                                               </div>
                                             </form>
                                    
                                         </div>
                                    </div>

                                <?php } if(in_array(11, $this->admin_services)|| ($this->admin_role==1)) {?>

                                    <div class="tab-pane fade  <?=$show3?> <?=$active3?>" id="walletTab" role="tabpanel" aria-labelledby="walletTab">
                                          <div class="widget-content widget-content-area">
                                                <div class="row" style="margin-bottom: 20px;">
                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
                                                        <h4><b>Wallet Management</b></h4>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    
                                                    <div class="col-md-12 col-xl-6">
                                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                           <h5><b>Wallet Info <i class="fa fa-refresh" onclick="refreshDiv()" aria-hidden="true" style="font-size: 20px;color:blue;margin-left: 20px;cursor: pointer;"></i></b></h5> 

                                                       </div>
                                                        <div id="appendData">
                                                        <?php
                                                            foreach ($this->wallets  as $key => $value) {

                                                               if($value['is_hidden'] == 1)
                                                                   continue;

                                                               echo '<div class="row col-12">
                                                                        <div class="col-5" style="float:left;"><label>'.$value['label'].'</label></div>
                                                                        <div class="" style="float:left;">-</div>
                                                                        <div class="col-5" style="float:left;"><label>'.number_format($wallet[$value['table_column_name']],$value['decimal_limit']).'</label></div>
                                                                     </div>';
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-xl-6">
                                                        <h5><b>Credit/Debit Amount</b></h5>
                                                        <form method="post" id="walletForm"> 
                                                        <div class="form-group">
                                                           <label>Select Wallet</label><br>
                                                           <select class="form-control custom-select" name="walletType" id="walletType" onchange="checkProperty()">
                                                                <?php
                                                                    foreach ($this->wallets as $key => $value) {
                                                                       
                                                                       if($value['is_hidden'] == 1 || ($value['is_credit_enabled'] == 0 && $value['is_debit_enabled'] == 0 ))
                                                                            continue;

                                                                       echo '<option value="'.$key.'" credit_status="'.$value['is_credit_enabled'].'" debit_status="'.$value['is_debit_enabled'].'">'.$value['label'].'</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                     
                                                        <div class="form-group">
                                                            <label>Credit/Debit</label><br>
                                                            <select class="form-control custom-select" name="creditType" id="creditType" >
                                                               <option value="0">Credit</option>
                                                               <option value="1">Debit</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Amount </label><br>
                                                            <input type="text" class="form-control" id="amount" name="amount" value="" placeholder="Enter Amount">
                                                        </div>
                                                        <div class="form-group">
                                                             <label>Remarks </label><br>
                                                            <input type="text" class="form-control" id="remarks" name="remarks" value="" placeholder="Enter Remarks">
                                                            
                                                        </div>
                                                        <input type="hidden" name="user" id="user" value="<?=$info['id']?>">
                                                        <div class="col-md-12  text-center">
                                                            <button class="btn btn-primary col-6" id="subtransfer" onclick="transferAmt()" type="button">Submit</button>
                                                        </div>
                                                      </form>
                                                    </div>
                                                   
                                                 </div>

                                          </div>        
                                             
                                    </div>
                                <?php } ?> 
                               
                        
                      </div>
                </div>
            </div>
        </div>

<script>

$( function() {

      $('#accordionExample').find('li a').attr("data-active","false");
      $('#custMenu').attr("data-active","true");
      $('#custNav').addClass('show');
      $('#userList').addClass('active');

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
                openSuccess(newResp['response'], '<?=BASEURL ?>Customer/Account/?user=<?=$info['id']?>')
            }
            else
            {
               loadingoverlay('error','Error',newResp['response']);
            }
             return false;
        });
        return false;
    });

      var f1 = flatpickr(document.getElementById('Date1'),{
            dateFormat:"d-m-Y",
            minDate: "01-01-1899",
             maxDate: "today"
          });
      
    $('#updatePass').click(function(){
        

        postdata = $('#pwdForm').serializeArray();
 
        $.post('<?= BASEURL ?>Customer/Resetpass/',postdata,function(response){ 
    
          newResp = JSON.parse(response);
            
            if(newResp['status'] == 'success'){
                openSuccess(newResp['response'], '<?=BASEURL ?>Customer/Account/?user=<?=$info['id']?>')
            }else{ 
                loadingoverlay('error','Error',newResp['response']);
            }
        });
        return false;
    });

});
    
function checkProperty(){

    let is_credit = $('#walletType option:selected').attr("credit_status");
    let is_debit  = $('#walletType option:selected').attr("debit_status");

    $("#creditType").val(0);

    if(is_credit == 1)
        $('select[name*="creditType"] option[value="0"]').show();
    else{
        $('select[name*="creditType"] option[value="0"]').hide();
        $("#creditType").val(1);
    }

    if(is_debit == 1)
        $('select[name*="creditType"] option[value="1"]').show();
    else
        $('select[name*="creditType"] option[value="1"]').hide();
}
    
  
function transferAmt(){ 

    swal.showLoading()

    postdata = $('#walletForm').serializeArray();

    $.post('<?=BASEURL;?>Customer/TransferAmt/',postdata,function(response){ 

            newResp = JSON.parse(response);
            if(newResp['status']=='success')
            {
              swal({
                    title: "Success",
                    text: newResp['response'],
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: 'btn-success waves-effect waves-light',
                    confirmButtonText: 'Okay',
                    closeOnConfirm: false,
                    }).then(function(isConfirm) {
                        refreshDiv();
                        $('#amount').val('');
                        $('#remarks').val('');
                        $('#walletForm').find('select').prop("selectedIndex", 0);
                    });
            }
            else
            { 
               loadingoverlay('error','Error',newResp['response']);
            }
        return false;
    });
  return false;
}

function refreshDiv(){

    swal.showLoading()

    $.post('<?=BASEURL;?>Customer/RefreshWallet/',{id:"<?=$info['id']?>"},function(response){ 
         $('#appendData').html(response);
         swal.close();
     });
}


function switchStatus(id,status){
  var url = 'BlockCustomer';
  if(status == 0){
    var swClass ='';
    changedToStatus = 1 ;
    
  }else{
    var swClass ='';
    changedToStatus = 0 ;
  }

  $.post('<?=BASEURL;?>Customer/'+url,{'uid':id,'status':changedToStatus},function(response){

      newResp = JSON.parse(response);
      openSuccess(newResp['response'])
  });

}

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

</script>

 
