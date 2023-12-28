<?php 
use inc\Root;
use inc\commonArrays;
$roleArry = $this->systemArrays['roleArr'];
$this->mainTitle = 'Leave Request';
$this->subTitle  = 'Staff List';

?>
<style type="text/css">
 
  .dataTables_filter{
    display: none;
  }
 
  .m-t-40{
    margin-top: 0px !important;
  }
  .dataTable{ text-align: center; }

  .div_float{
     float: right;
     margin-right: 10px;
  }

@media(max-width:767px){
    .div_float {
        
        padding: 5px;
        margin-right: 0px;
    }
    .full_width{
     width: 100%;
   }
} 
.form-control{
       height: 42px;
}
.select{
  padding-top: 9px;
}
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>LeaveRequest/StaffList">

                                 <div class="form-group col-sm-2">
                                   <select type="option" name="user_id" class="form-control custom-select" id="user_list">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($filter['username'])):?>
                                          <option value="<?=$user_id?>" selected><?=$filter['username']?></option>
                                        <?php endif;?>
                                    </select>

                                  </div>
                                  <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="staff_id" name="staff_id" placeholder="Enter Staff Id">
                                  </div>
                                   <div class="form-group col-sm-2">
                                    <select class="form-control custom-select position" name="position" id="position">
                                        <option value="">Select Position</option>
                                        <?php
                                            foreach ($data['positions'] as $key =>$value) { 
                                        ?>
                                            <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                        <?php }?>

                                    </select>
                                  </div>
                                  <div class="form-group col-sm-2">
                                    <select class="form-control custom-select department" name="department" id="department">
                                        <option value="">Select Department</option>
                                        <?php
                                            foreach ($data['departments'] as $key =>$value) { 
                                        ?>
                                            <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                        <?php }?>

                                    </select>
                                  </div>

                                  <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="first_name" name="first_name" placeholder="Enter First Name">
                                  </div>
                                  <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="nick_name" name="nick_name" placeholder="Enter Nick Name">
                                  </div>
                                  <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="email" name="email" placeholder="Enter Email">
                                  </div>
                                   <!-- <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="mob" name="mob" placeholder="Enter Mobile No.">
                                  </div> -->
                                  <!-- <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="passport_number" name="passport_number" placeholder="NRIC/Passport No./FIN no">
                                  </div> -->
                                  <!-- <div class="form-group col-sm-2">
                                    <input type="date"  class="form-control flatpickr-input active" id="dob" name="dob" min='1899-01-01' max='2000-13-13' placeholder="Enter Date of Birth">
                                  </div>
 -->
                                  <div class="form-group col-sm-2">
                                        <select type="option" name="role" class="form-control select" id="role">
                                            <option value=''>Role</option>
                                            <?php
                                                foreach ($roleArry as $key => $value) {
                                                  if($key!=6) {
                                                      echo '<option value="'.$key.'">'.$value.'</option>';
                                                  }    
                                                }
                                            ?>
                                        </select>
                                  </div>
                                 
                                  
                                  <div class="form-group col-sm-2">
                                        <select type="option" name="status" class="form-control select" id="status">
                                            <option value="">Status</option>
                                            <?php
                                                foreach ($this->userArr as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                        </select>
                                  </div>

                            </form>
                               
                        </div>

                        <div class="row col-md-12 col-lg-12">
                           
                            <div class="col-md-5 col-lg-5">
                               
                                

                             

                                <a class="btn btn-primary mb-2"  data-toggle="modal" data-target="#leaveImportModal">
                                        Import
                                </a> 

                                <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>  
                                      
                            </div>
                            <div class="col-md-7 col-lg-7">
                                      <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="Search">
                            </div>
                             

                            
                        </div>
                      
                    </div>

                    <div  class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div  class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width:100%; " role="grid" aria-describedby="dt_info">
                                <thead >
                                  <tr role="row">

                                  <th class="sorting_disabled" rowspan="1" colspan="1">User Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Staff Id</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Role</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Position</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Department</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">First Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Last Name</th>
                                  
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Email</th>
                                  <?php foreach($data_leave_type as $key=>$value){?>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?= $value['leave_name']?></th>
                                  <?php }?>
                                  </tr>
                                
                                </thead>
                                <tbody>
                                <?php

                                 
                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                   
                                        

                                       <tr role="row" class="odd">
                                           
                                           <td><a class="badge outline-badge-primary" href="<?=BASEURL?>User/UpdateUser/?user_id=<?=base64_encode($val['user_id'])?>&active=leavebalance" target="_blank"><?=$val['username']?></a></td>
                                          <td><?=$val['staff_id'];?></td>

                                           <td><?=!empty($val['role']) ? $val['role'] : ''?></td>
                                           <td><?=$val['position'];?></td>
                                           <td><?=$val['department'];?></td>
                                           <td><?=$val['first_name'];?></td>
                                           <td><?=$val['last_name'];?></td>
                                           <td><?=$val['email'];?></td>
                                           <?php foreach($val['leaveList']['0']['leave_report'] as $key=>$value){?>
                                            <td><?= $value['balance']?></td>
                                           <?php } ?>
                                           
                                                    
                                       </tr>
                              <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="17" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>LeaveRequest/StaffList">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination;?>
                                    </ul>
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

<div class="modal fade" id="leaveImportModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>
                Upload Excel</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" id="statusUpdate">
            <div class="card-block"> 
                <div class="row m-b-30 form-group">
                     <label>Excel File * <a href="<?= BASEURL.'web/assets/Sample_excels/sample_staff_leave.csv'?>" download>Sample File<i class="fa fa-download" aria-hidden="true"></i></a> </label>     
                     <input type="file" id="importfile" name="importfile" class="form-control" style="height:53px;">
                </div>                                                                      
            </div>
           </form> 
        </div>
        <div class="modal-footer">
               <button type="button" class="btn btn-primary mr-3" onclick="importStaffLeave()">Upload</button> 
               <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
      </div>
    </div>
</div>

<div class="modal left fade" id="ListImportModel" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="max-width:fit-content;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>
                Import Details</h4>
            <button type="button" class="close cancelImport" data-dismiss="modal" value="">&times;</button>
        </div>
        <form method="post" id="importtable">
        <div class="modal-body" id="table_content_import">
          
        </div>
        <div class="modal-footer">
             
               <button type="button" class="btn btn-primary" value="" onclick="proceedImport(1)">Proceed</button>
               <button type="button" class="btn btn-danger" onclick="proceedImport(2)">Cancel</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>
<script>
    
    $('#accordionExample').find('li a').attr("data-active","false");
    $('#LeaveMenu').attr("data-active","true");
    $('#LeaveNav').addClass('show');
    $('#StaffList').addClass('active');
        
    $('[data-toggle="tooltip"]').tooltip();   

        var user_id    = "<?=$user_id?>";
        var username   = "<?=$filter['username']?>";
        var first_name = "<?=$filter['first_name']?>";
        var status     = "<?=$filter['status']?>";
        var nick_name  = "<?=$filter['nick_name']?>";
        var staff_id   = "<?=$filter['staff_id']?>";
        var position   = "<?=$filter['position']?>";
        var department = "<?=$filter['department']?>";
        var email      = "<?=$filter['email']?>";
        var role      = "<?=$filter['role']?>";
   
   

 
$(function () { 

   

    // $('#userID').val(userID);
    $('#status').val(status);
    $('#user_list').val(user_id);
    $('#username').val(username);
    $('#first_name').val(first_name);
    //$('#mob').val(mob);
    //$('#dob').val(dob);
    $('#nick_name').val(nick_name);
    $('#staff_id').val(staff_id);
    $('#position').val(position);
    $('#department').val(department);
    //$('#passport_number').val(passport_number);
    $('#role').val(role);
    $('#email').val(email);
    
    
 
        
  
    $('#search').click(function(){
        $('#userForm').submit();
    })
});

function pageHistory(user_id,role,staff_id,position,department,first_name,nick_name,status,email,page){


    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+user_id+'" style="display:none;">');
    $('.pagination').append('<input name="role" value="'+role+'" style="display:none;">');
    $('.pagination').append('<input name="staff_id" value="'+staff_id+'" style="display:none;">');
    $('.pagination').append('<input name="position" value="'+position+'" style="display:none;">');
    $('.pagination').append('<input name="department" value="'+department+'" style="display:none;">');
    $('.pagination').append('<input name="first_name" value="'+first_name+'" style="display:none;">');
    $('.pagination').append('<input name="nick_name" value="'+nick_name+'" style="display:none;">');
   $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
   $('.pagination').append('<input name="email" value="'+email+'" style="display:none;">');
   
    $('#user_pagination').submit();
}


  $("#pageJumpBtn").click(function(){ 
       
        var user_id    = "<?=$user_id?>";
        var username   = "<?=$filter['username']?>";
        var first_name = "<?=$filter['first_name']?>";
        var status     = "<?=$filter['status']?>";
        var nick_name  = "<?=$filter['nick_name']?>";
        var staff_id   = "<?=$filter['staff_id']?>";
        var position   = "<?=$filter['position']?>";
        var department = "<?=$filter['department']?>";
        var email      = "<?=$filter['email']?>";
        var role       = "<?=$filter['role']?>";

        var pge        = $("#pageJump").val();
        

    pageHistory(user_id,role,staff_id,position,department,first_name,nick_name,status,email,pge);
    });

    $('#pageJump').val("<?=$data['curPage'];?>");



$('#user_list').select2({
    placeholder: 'Select Username',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>User/GetUsers',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        //var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});

 function exportReport(){
      
        var user_id    = "<?=$user_id?>";
        var username   = "<?=$filter['username']?>";
        var first_name = "<?=$filter['first_name']?>";
        var status     = "<?=$filter['status']?>";
        var nick_name  = "<?=$filter['nick_name']?>";
        var staff_id   = "<?=$filter['staff_id']?>";
        var position   = "<?=$filter['position']?>";
        var department = "<?=$filter['department']?>";
        var email      = "<?=$filter['email']?>";
        var role      = "<?=$filter['role']?>";
      
        

    

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>LeaveRequest/ExportStaffList',{'user_id':user_id,'first_name':first_name,'status':status,'nick_name':nick_name,'staff_id':staff_id,'position':position,'department':department,'role':role},
            function(response){
                hideoverlay();
        newResp = JSON.parse(response); 
        
        if(newResp['status'] == 'success')
        {        
            $('#downfile').html(newResp['response']);
            $('#downloadcsv').click();

        }else{
            loadingoverlay("error","Error","Try Again>");
        }

    });
    return false;
        
} 


function importStaffLeave(){
    data = new FormData();
    data.append('filename', $('#importfile')[0].files[0]);
    loadingoverlay('info',"Please wait..","loading...");
    $.ajax({
       // url: '<?=BASEURL;?>LeaveRequest/ImportStaffLeave/', 
        url: '<?=BASEURL;?>LeaveRequest/ImportStaffLeaveBalance/', 

        
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success') {
                if(newResp['response']['type'] == 'showpopup') {
                    hideoverlay();
                    $('#ListImportModel').modal('toggle');
                    $('#leaveImportModal').modal('toggle');
                    $('#table_content_import').html("");
                    var header = newResp['response']['html']['header'];
                    var valid = newResp['response']['html']['valid'];
                    var invalid = newResp['response']['html']['invalid'];
                    var footer = newResp['response']['html']['footer'];
                    var combinedHTML = header + valid + invalid + footer;
                    $('#table_content_import').html(combinedHTML);
                    bulk_id = newResp['response']['bulk_id'];
                }
            }else{
                if(newResp['response']['type'] == 'validation') {
                    loadingoverlay('error','Error',newResp['response']['msg']);  
                }
            }
        }
    }); 
    
}


function proceedImport(type){

    //$('#ListImportModel').modal('toggle');
    data = new FormData();
    data.append('type',type); //1-yes,2-cancel
    data.append('bulk_id',bulk_id); 
    loadingoverlay('info',"Please wait..","loading...");
    $.ajax({
        url: '<?=BASEURL;?>LeaveRequest/ProcessStaffLeaveImport/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){
            newResp = JSON.parse(response);
            $('#ListImportModel').modal('toggle');
            if(newResp['status'] == 'success') {
                loadingoverlay('success','Success',newResp['response']);  
            }else{
                loadingoverlay('error','Error',newResp['response']);    
            }
        }
    });

}
</script>
