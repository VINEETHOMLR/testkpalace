
<?php
 use inc\Root;
  $this->mainTitle = 'User Management';
  $this->subTitle  = 'User Service Group';

  if(!empty($permissions_ids)){
    $style1="display: none";
    $style="";
  }else{
    $style="display: none";
    $style1="";
  }

  if($permissions_ids != ''){
      $service_select_arr = explode(',',$permissions_ids);
 
      $button=Root::t('servicegroup', 'update'); 
  }else{
      $service_select_arr = array();

      if($this->admin_role!=1){
        $service_select_arr1=explode(',',$servicesalready);
      }

      $button=Root::t('servicegroup', 'create_text');
  }
?>

<style type="text/css">
  .dot{
    background: #bae7ff;
    border: 2px solid #2196f3;
    padding: 4px;
    border-radius: 50%;
    margin-right: 5px;
    display: inline-block;
  }
  .table > tbody > tr > td {
    color: #515365;
  }

  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }

  table td:nth-of-type(2) {width:60%; text-align: left;}
</style>


<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
        
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing" id="ServiceListDiv" style="<?=$style1;?>">
                <div class="widget-content widget-content-area br-6">

                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                    <th>Role Name</th>
                                    <th><?= Root::t('servicegroup','service_txt'); ?></th>
                                    <th><?= Root::t('app','action_text'); ?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php 
                                 if(!empty($service_group)){
                                   foreach($service_group as $key => $val):
                                ?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['group_name']?></td>
                                      <td><?=$val['permission_type']?></td>
                                      <td><?=$val['action']?></td>
                                    </tr>
                                <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="3" class="text-center">No Data Found</td></tr>';
                                  }   
                                ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="s_pagination" method="post" action="<?=BASEURL;?>UserService/Index/">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <!-- <?=$data['pagination'];?> -->
                                    </ul>
                                </div>
                            </form>
                          </div>
                        </div>
                    </div>
                </div>
            </div>


             <div class="col-12" id="CreateServiceDiv" style="<?=$style;?>">
                    <div class="col-12">
                        <div class="card"> 
                            <nav class="breadcrumb-two" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="<?=BASEURL;?>UserService/Index/">User Service list</a></li>
                                  <li class="breadcrumb-item active"><a href="javascript:void(0);">Services</a></li>
                                </ol>
                            </nav>
                             <div class="card-body">
                              <div class="col-md-12"></div>
                               <div class="row" style="padding-top: 20px; margin-left: 5px;" >
                                 <form role="form" id="createPrivilegeGrp" class="col-md-12">
                                   <div class="panel-body">
                                     <div class="form-body table-responsive">
                                      <table class="table table-bordered">
                                        <tr class="servicenameDiv has-feedback">
                                          <td ><label style="color:#515365;">Role Name </label></td>
                                          <td><div class="col-md-4"><input type="text" id="state-success" name="servicename" data-divName='servicenameDiv' class="form-control servicename input-sm" value="<?=$permission_name;?>" disabled>
                                           <span class="text-danger errorMsg servicenameMsg"></span></div>

                                          <input type="hidden" name="permission_id" id="permission_id" value="<?=$permission_id;?>">
                                         </td>
                                        </tr>
                                        
                                        <tr style="background: #d6e0e3;margin-top: 12px;padding: 6px;">
                                          <td colspan="2" style="padding-top: 5px;font-weight: bold;padding-left: 18px;"><span class="fa fa-cog"></span>&nbsp;<?= Root::t('servicegroup', 'selservice_dash'); ?></td>
                                        </tr>
                                        <?php 
                                      
              
                                        foreach($service_master as $key=>$value){?>
                                        <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="page<?=$value['id'];?>" onclick="checkList('page<?=$value[id];?>')">
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$value['master_name'];?>
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                          <?php
                                            
                                            foreach ($value['service'] as $key => $memval) {
                                            
                                              $checked= (in_array($memval['id'], $service_select_arr)) ? 'checked' : '';
                                              ?>
                                              <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input page<?=$value['id'];?>" name="user_service_array[]" value="<?=$memval['id'];?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval['service_name'];?>
                                                  </label>

                                                </div>
                                                <?php
                                              } ?>

                                            </td>
                                        </tr>
                                      <?php

                                      }
                                      ?>
                                      </table>

                                    </div>
                                </div>
                                <div class="panel-footer text-center">
                                    <div class="form-actions">
                                        <button type="button" class="btn btn-primary proceedToCreate pull-right">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>&nbsp;<?=$button;?></button>
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
  </div>


<?php
$error_dash = Root::t('app','error_dash');
$success    = Root::t('app','suucess_txt');
$okay       = Root::t('app','okay_btn'); 
?>

<script type="text/javascript">

    $(function () {

      $('#createServBtn').click(function(){
         $('#CreateServiceDiv').show();
         $('#ServiceListDiv').hide();
      });
   });

$('.proceedToCreate').click(function(){

      postdata = $('#createPrivilegeGrp').serializeArray();
      postdata.push({'name':'newPrivilege','value':true});
   
    $.post('<?=BASEURL;?>UserService/UpdateUserService',postdata,function(response){
     //alert(response)
        newResp = JSON.parse(response);
        if(newResp['status'] == 'success')
        {   
            openSuccess(newResp['response'],'<?=BASEURL;?>UserService/Index/') 
        }
        else
        {
           loadingoverlay('error','<?=$error_dash;?>',newResp['response']);

        }
        return false;
    });
    return false;
});

function checkList(val){ 

    if($('#'+val).prop('checked')) {
        $('.'+val+'').prop('checked', true);
    } else {
        $('.'+val+'').prop('checked', false);
    }
}

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#userMenu').attr("data-active","true");
    $('#userNav').addClass('show');
    $('#UserServiceNav').addClass('active');

function pageHistory(page){
  
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;"/>');
    $('#s_pagination').submit();
}


</script>
