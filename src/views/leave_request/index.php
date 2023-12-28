<?php 
use inc\Root;
use inc\commonArrays;
$this->mainTitle = 'Leave Request';
$this->subTitle  = 'Leave Request List';
$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

$this->statusArray     = ['0'=>'Requested','1'=>'Approved','2'=>'Rejected','3'=>'Cancelled'];
$this->leave_typeArray = ['1'=>'Full Day','2'=>'Half Day'];

$this->permissionList = $this->systemArrays['roleArr'];

//$create_permission  = in_array(79,$this->admin_services) || $this->admin_role == '1' ? true : false;


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
                            <form id="purchaseform" class="row col-md-12" method="post" action="<?=BASEURL;?>LeaveRequest/Index">
                                
                                <div class="form-group col-sm-3">
                                    <select type="option" name="username" class="form-control custom-select" id="username">
                                        <option value="">Select Customer</option>
                                        <?php if(! empty($username)):?>
                                          <option value="<?=$user_id?>" selected><?=$username?></option>
                                        <?php endif;?>
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-3">
                                    
                                    <select type="option" name="leave_id" class="form-control select" id="leave_id">
                                            <option value="">Select Leave Type</option>
                                            <?php
                                                foreach ($data_leave_type as $leaveType) {
                                                  echo '<option value="'.$leaveType['id'].'">'.$leaveType['leave_name'].'</option>';
                                                }
                                            ?>
                                    </select>

                                </div>
                                <div class="form-group col-sm-3">
                                    <select class="form-control custom-select department" name="department" id="department">
                                        <option value="">Select Department</option>
                                        <?php
                                            foreach ($data['departments'] as $key =>$value) { 
                                        ?>
                                            <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                        <?php }?>

                                    </select>
                                  </div>
                                <div class="form-group col-sm-3">
                                    
                                    <select type="option" name="role_id" class="form-control select" id="role_id">
                                            <option value="">Select Role</option>
                                            <?php
                                                foreach ($this->permissionList as $key=>$value) {
                                                  if($key!='6') {
                                                      echo '<option value="'.$key.'">'.$value.'</option>';
                                                  }
                                                }
                                            ?>
                                    </select>

                                </div>

                                <div class="form-group col-sm-3">
                                    
                                    <input type="date"  class="form-control flatpickr-input" id="leave_date" name="leave_date" placeholder="Leave From Date">
                                
                                </div>
                                <div class="form-group col-sm-3">
                                    
                                    <input type="date"  class="form-control flatpickr-input" id="leave_to_date" name="leave_to_date" placeholder="Leave To Date">
                                
                                </div>

                                <div class="form-group col-sm-3">
                                    
                                    <select type="option" name="leave_taken" class="form-control select" id="leave_taken">
                                            <option value="">Select Day Type</option>
                                            <?php
                                                foreach ($this->leave_typeArray as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                    </select>

                                </div>

                                <div class="form-group col-sm-3">
                                    
                                    <select type="option" name="status" class="form-control select" id="status">
                                            <option value="">Status</option>
                                            <?php
                                                foreach ($this->statusArray as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                    </select>

                                </div>

                                <div class="form-group col-sm-12">
                                  <input type="submit" class="btn btn-success" id="search" name="Search" value="Search">
                                  <a href="<?=BASEURL?>LeaveRequest/Create/" class="btn btn-outline-info addbtn mb-2" style="float: right;">
                                        Apply Leave
                                      
                                       </a>
                                  <!-- <a class="btn btn-primary mb-2"  data-toggle="modal" data-target="#excelModal">
                                        Import
                                      
                                  </a>  -->

                                  
                              </div> 

                              
                            </form>
                        </div>
                        <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Staff ID</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">FullName</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Department</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Role</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">From Date</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">To Date</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Total No of Days</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Reason of Leave</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Uploaded File</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Approved BY</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Remark</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                  if(!empty($data['data'])){

                                     foreach($data['data'] as $key => $val){ ?>                                

                                       <tr role="row" class="odd">
                                           <td><?=$val['staff_id']?></td>
                                           <td><?=$val['username']?></td>
                                           <td><?=$val['first_name'] . ' ' . $val['last_name'] ?></td>
                                           <td><?=$val['department']?></td>
                                           <td><?=$val['role']?></td>
                                           <td><?=$val['from_date']?></td>
                                           <td><?=$val['to_date']?></td>
                                           <td><?=$val['total_days'] .' '.'('. $val['leave_type'] .')'?></td>
                                           <td><?=$val['reason']. ' '.'('. $val['leave_name'] .')' ?></td>
                                           <td><?=$val['upload_file']?></td>
                                           <td><?=$val['updated_name']?></td>
                                           <td><?=$val['remark']?></td>
                                           <td><?=$val['status']?></td>
                                           <td><?=$val['action']?></td>
                                                           
                                       </tr>

                              <?php }
                                  }else{
                                     echo '<tr><td colspan="11" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="purchase_pagination" method="post" action="<?=BASEURL;?>leaveRequest/Index">
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

<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Update Status</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="closemodal('statusModal')" style="margin-top:3px"  aria-label="Close"> x </button>

            </div>
            <div class="modal-body" id="statusModalbody">
                <!-- Select Box -->
                <div class="form-group">
                    <label for="leave_status">Select Status</label>
                    <select class="form-control" id="leave_status">
                        <option value="">Select Status</option>
                        <option value="1">Approve</option>
                        <option value="2">Reject</option>
                        <option value="3">Cancel</option>
                    </select>
                </div>

                <!-- Textarea -->
                <div class="form-group">
                    <label for="remark">Remark:</label>
                    <textarea class="form-control" id="remark" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="proceedStatus()">Submit</button>
                <button type="button" class="btn btn-danger" onclick="closemodal('statusModal')">Close</button>
            </div>
          
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Edit Leave Request</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> x </button>
            </div>
            <div class="modal-body">
                <!-- User Name -->
                <div class="form-group">
                    <label for="userName">User Name<span style="color:red;">*</span>:</label>
                    <input type="text" class="form-control" id="userName" disabled>
                </div>
                
                <!-- Leave Type -->
                <div class="form-group">
                    <label for="leaveType">Leave Type<span style="color:red;">*</span>:</label>
                    <select type="option" name="leaveType" class="form-control select" id="leaveType">
                        <option value="">Leave Type</option>
                            <?php
                                foreach ($data_leave_type as $leaveType) {
                                    echo '<option value="'.$leaveType['id'].'">'.$leaveType['leave_name'].'</option>';
                                }
                            ?>
                    </select>                
                </div>

                <!-- Leave Date -->
                <div class="form-group">
                    <label for="leaveDate">Leave From Date<span style="color:red;">*</span>:</label><br>
                    <input type="date"  class="form-control flatpickr-input" id="leaveDate" name="leaveDate" placeholder="Select Leave From Date" style="width: 440px;">
                </div>

                <div class="form-group">
                    <label for="leaveDate">Leave To Date<span style="color:red;">*</span>:</label><br>
                    <input type="date"  class="form-control flatpickr-input" id="leaveToDate" name="leaveToDate" placeholder="Select Leave To Date" style="width: 440px;">
                </div>

                <!-- Leave Type (Half/Full Day) -->
                <div class="form-group">
                    <label for="leaveDayType">Leave Day Type<span style="color:red;">*</span>:</label>
                    <select class="form-control" id="leaveDayType">
                        <option value="1">Full Day</option>
                        <option value="2">Half Day</option>
                    </select>
                </div>

                <!-- Reason -->
                <div class="form-group">
                    <label for="reason">Reason<span style="color:red;">*</span>:</label>
                    <textarea class="form-control" id="reason" rows="3"></textarea>
                </div>

                <!-- Uploaded File -->
                <div class="form-group">
                    <label for="uploadedFile">Uploaded File:</label>
                    <input type="file" class="form-control-file" id="uploadedFile"><br>
                    <span id="uploadedFileName"></span> 
                </div>

                <!-- Status -->
                <!-- <div class="form-group">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" id="status">
                </div> -->

                <!-- Remark -->
                <div class="form-group">
                    <label for="editremark">Remark<span style="color:red;">*</span>:</label>
                    <textarea class="form-control" id="editremark" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="proceedEdit()">Save Changes</button>
                <button type="button" class="btn btn-danger" onclick="closemodal('editModal')">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>View Leave Request</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> x </button>
            </div>
            <div class="modal-body">
                <!-- User Name -->
                <div class="form-group">
                    <label for="userName_view">User Name:</label>
                    <input type="text" class="form-control" id="userName_view" disabled>
                </div>
                
                <!-- Leave Type -->
                <div class="form-group">
                    <label for="leaveType_view">Leave Type:</label>
                    <select type="option" name="leaveType_view" class="form-control select" id="leaveType_view" disabled>
                        <option value="">Leave Type</option>
                            <?php
                                foreach ($data_leave_type as $leaveType) {
                                    echo '<option value="'.$leaveType['id'].'">'.$leaveType['leave_name'].'</option>';
                                }
                            ?>
                    </select>                
                </div>

                <!-- Leave Date -->
                <div class="form-group">
                    <label for="leaveDate_view">Leave From Date:</label><br>
                    <input type="date"  class="form-control flatpickr-input" id="leaveDate_view" name="leaveDate_view" placeholder="Select Leave From Date" style="width: 440px;" disabled>
                </div>

                <div class="form-group">
                    <label for="leaveDate_view">Leave To Date:</label><br>
                    <input type="date"  class="form-control flatpickr-input" id="leaveToDate_view" name="leaveToDate_view" placeholder="Select Leave To Date" style="width: 440px;" disabled>
                </div>

                <!-- Leave Type (Half/Full Day) -->
                <div class="form-group">
                    <label for="leaveDayType_view">Leave Day Type:</label>
                    <select class="form-control" id="leaveDayType_view" disabled>
                        <option value="1">Full Day</option>
                        <option value="2">Half Day</option>
                    </select>
                </div>

                <!-- Reason -->
                <div class="form-group">
                    <label for="reason_view">Reason:</label>
                    <textarea class="form-control" id="reason_view" rows="3" disabled></textarea>
                </div>

                <!-- Uploaded File -->
                <div class="form-group">
                    <label for="uploadedFile_view">Uploaded File:</label>
                    <span id="uploadedFileName_view"></span> 
                </div>

                <!-- Status -->
                <!-- <div class="form-group">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" id="status">
                </div> -->

                <!-- Remark -->
                <div class="form-group">
                    <label for="editremark_view">Remark:</label>
                    <textarea class="form-control" id="editremark_view" rows="3" disabled></textarea>
                </div>
            </div>

            <div class="modal-footer">
                
                <button type="button" class="btn btn-danger" onclick="closemodal('viewModal')">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="excelModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
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
                    
                   
                     <label>Excel File * <a href="<?= BASEURL.'web/assets/Sample_excels/import_Sample_leave.csv'?>" download>Sample File<i class="fa fa-download" aria-hidden="true"></i></a> </label>     
                     <input type="file" id="importfile" name="importfile" class="form-control" style="height:53px;">
                </div>                                                                      
            </div>
           </form> 
        </div>
        <div class="modal-footer">
               <button type="button" class="btn btn-primary mr-3" onclick="importLeave()">Upload</button> 
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
<style>
table {
  display: block;
  overflow-x: auto;
  white-space: nowrap;
}


</style>

<script>

var selectedId = '';
var edit_user_id = '';
var bulk_id = '';

function proceedImport(type)
{

    $('#ListImportModel').modal('toggle');
    data = new FormData();
    data.append('type',type); //1-yes,2-cancel
    data.append('bulk_id',bulk_id); 
    loadingoverlay('info',"Please wait..","loading...");
    $.ajax({
              url: '<?=BASEURL;?>LeaveRequest/ProcessImport/', 
              dataType: 'text',  
              cache: false,
              contentType: false,
              processData: false,
              data: data,                         
              type: 'post',
              success: function(response){
               

                newResp = JSON.parse(response);
                if(newResp['status'] == 'success') {
                
                    loadingoverlay('success','Succes',newResp['response']);  
                    

                }else{


                    loadingoverlay('error','Error',newResp['response']);    
                }
                
              }
          });   



}
function importLeave()
{
    data = new FormData();
    data.append('filename', $('#importfile')[0].files[0]);
    loadingoverlay('info',"Please wait..","loading...");
    $.ajax({
              url: '<?=BASEURL;?>LeaveRequest/ImportLeave/', 
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
                        $('#excelModal').modal('toggle');
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
$(function () { 

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#LeaveMenu').attr("data-active","true");
    $('#LeaveNav').addClass('show');
    $('#LeaveRequestList').addClass('active');
        
    $('[data-toggle="tooltip"]').tooltip(); 
    
  var username   = '<?=$filter['username']?>';
  var leave_id   = '<?=$filter['leave_id']?>';
  var status     = '<?=$filter['status']?>';
  var leave_date = '<?=$filter['leave_date']?>';
  var leave_to_date = '<?=$filter['leave_to_date']?>';
  var leave_taken= '<?=$filter['leave_taken']?>';
  var role_id = '<?=$filter['role_id']?>';
  var department = '<?=$filter['department']?>';
  $('#username').val(username);
  $('#leave_id').val(leave_id);
  $('#status').val(status);
  $('#leave_date').val(leave_date);
  $('#leave_to_date').val(leave_to_date);
  $('#leave_taken').val(leave_taken);
  $('#role_id').val(role_id);
  $('#department').val(department);


    $('#search').click(function(){
        $('#purchaseform').submit();
    })
});

function exportReport(){

        username   = '<?=$filter['username']?>';
        leave_id   = '<?=$filter['leave_id']?>';
        status     = '<?=$filter['status']?>';
        leave_date = '<?=$filter['leave_date']?>';
        leave_to_date = '<?=$filter['leave_to_date']?>';
        leave_taken= '<?=$filter['leave_taken']?>';
        role_id = '<?=$filter['role_id']?>';
        department = '<?=$filter['department']?>';

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>LeaveRequest/Export',{
        'username': username,
        'leave_id': leave_id,
        'status': status,
        'leave_date': leave_date,
        'leave_taken': leave_taken,
        'role_id': role_id,
        'department': department,
        'leave_to_date': leave_to_date
        },
            function(response){
              //alert(response)
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

$('#username').select2({
    placeholder: 'Select Username',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>User/getUsers',
      dataType: 'json',
      delay: 250,
      data: function (params) {
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

function pageHistory(username,leave_id,status,leave_date,leave_taken,role_id,department,leave_to_date,page){

 
    
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="leave_id" value="'+leave_id+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="leave_date" value="'+leave_date+'" style="display:none;">');
    $('.pagination').append('<input name="leave_taken" value="'+leave_taken+'" style="display:none;">');
    $('.pagination').append('<input name="role_id" value="'+role_id+'" style="display:none;">');
    $('.pagination').append('<input name="department" value="'+department+'" style="display:none;">');
    $('.pagination').append('<input name="leave_to_date" value="'+leave_to_date+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#purchase_pagination').submit();
}



 $("#pageJumpBtn").click(function(){

    pageHistory($('#username').val(),$('#leave_id').val(),$('#status').val(),$('#leave_date').val(),$('#leave_taken').val(),$('#role_id').val(),$('#department').val(),$('#leave_to_date').val(),$("#pageJump").val());

 });

function showApproveModal(id){
    selectedId = id;
    $("#statusModal").modal('toggle');
}
function showEditModal(id){
            
    selectedId = id;
    data = new FormData();
    data.append('id', selectedId);

        $.ajax({
            url: '<?=BASEURL;?>LeaveRequest/Edit/',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: 'post',
            success: function (response) {
                response = JSON.parse(response);

                $('#userName').val(response['response']['username']);
                $('#leaveType').val(response['response']['leave_id']);
                $('#leaveDate').val(response['response']['leave_date']);
                $('#leaveToDate').val(response['response']['leave_to_date']);
                $('#leaveDayType').val(response['response']['leave_type']);
                $('#reason').val(response['response']['reason']);
                $('#editremark').val(response['response']['remark']);
                $('#uploadedFileName').html(response['response']['upload_file']);
                edit_user_id = response['response']['user_id'];

                // Show the modal
                $('#editModal').modal('show');
                },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });
        
}

function showViewModal(id)
{

    selectedId = id;
    data = new FormData();
    data.append('id', selectedId);

        $.ajax({
            url: '<?=BASEURL;?>LeaveRequest/Edit/',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: 'post',
            success: function (response) {
                response = JSON.parse(response);

                $('#userName_view').val(response['response']['username']);
                $('#leaveType_view').val(response['response']['leave_id']);
                $('#leaveDate_view').val(response['response']['leave_date']);
                $('#leaveToDate_view').val(response['response']['leave_to_date']);
                $('#leaveDayType_view').val(response['response']['leave_type']);
                $('#reason_view').val(response['response']['reason']);
                $('#editremark_view').val(response['response']['remark']);
                $('#uploadedFileName_view').html(response['response']['upload_file']);
                
                // Show the modal
                $('#viewModal').modal('show');
                },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });

}

function proceedStatus()
{
    data = new FormData();
    data.append('status', $('#leave_status').val());
    data.append('remark', $('#remark').val());
    data.append('id', selectedId); 
       

    loadingoverlay('info',"Loading","Please Wait...");
    $.ajax({
              url: '<?=BASEURL;?>LeaveRequest/UpdateLeaveStatus/', 
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
                      openSuccess(newResp['response'],'<?=BASEURL;?>LeaveRequest/Index/')  
                  }else
                  {
                      loadingoverlay('error','Error',newResp['response']);
                  }
                return false;
              }
          });   
}


function proceedEdit()
{
    data = new FormData();
    data.append('leave_id', $('#leaveType').val());
    data.append('date_from', $('#leaveDate').val());
    data.append('date_to', $('#leaveToDate').val());
    data.append('leave_type', $('#leaveDayType').val());
    data.append('reason', $('#reason').val());
    data.append('remark', $('#editremark').val());
    data.append('file', $('#uploadedFile').prop('files')[0]);
    data.append('id', selectedId); 
    data.append('user_id', edit_user_id); 
       

    loadingoverlay('info',"Loading","Please Wait...");
    $.ajax({
              url: '<?=BASEURL;?>LeaveRequest/ApplyLeave/', 
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
                      openSuccess(newResp['response'],'<?=BASEURL;?>LeaveRequest/Index/')  
                  }else
                  {
                      loadingoverlay('error','Error',newResp['response']);
                  }
                return false;
              }
          });   
}

$('#pageJump').val("<?=$data['curPage'];?>");

function closemodal(modalid){
     
    $('#'+modalid).modal('toggle');
}
$(document).ready(function() {
  var flatpickr_time = $('.flatpickr-input').flatpickr({
    dateFormat:"d-m-Y",
    static: true
  });
});

flatpickr(document.getElementsByClassName('flatpickr-input'),{
            dateFormat:"d-m-Y"            
});
</script>
