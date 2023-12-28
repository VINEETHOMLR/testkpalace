<?php 
use inc\Root;
use inc\commonArrays;
$roleArry = $this->systemArrays['roleArr'];

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
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>User/">

                                 <div class="form-group col-sm-2">
                                   <select type="option" name="user_id" class="form-control custom-select" id="user_list">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
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
                                    <input type="text"  class="form-control select" id="mob" name="mob" placeholder="Enter Mobile No.">
                                  </div>
                                  <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="passport_number" name="passport_number" placeholder="NRIC/Passport No./FIN no">
                                  </div>
                                  <div class="form-group col-sm-2">
                                    <input type="date"  class="form-control flatpickr-input active" id="dob" name="dob" min='1899-01-01' max='2000-13-13' placeholder="Enter Date of Birth">
                                  </div>

                                  <div class="form-group col-sm-2">
                                        <select type="option" name="role" class="form-control select" id="role">
                                            <option value="">Role</option>
                                            <?php
                                                foreach ($roleArry as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
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
                               
                                <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>
                                      
                            </div>
                            <div class="col-md-7 col-lg-7">
                                      <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="Search">
                            </div>
                              <div class="form-group col-sm-3">
                                       <a href="<?=BASEURL?>User/CreateUser/"><button class="btn btn-outline-info addbtn mb-2" >
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Create User</button></a>
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
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Nick Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Mobile No</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Second Mobile No</th>
                                   <th class="sorting_disabled" rowspan="1" colspan="1">NRIC/Passport No./FIN no</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Email ID</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Date Of Birth</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Nationality</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Gender</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                
                                </thead>
                                <tbody>
                                <?php

                                 $stat_array=['1'=>'Male','2'=>'Female'];
                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                      <?php 
                                        $checked = (empty($val['status'])) ? 'checked' : '';  ?>

                                       <tr role="row" class="odd">
                                           
                                           <td><a class="badge outline-badge-primary" href="<?=BASEURL?>User/UpdateUser/?user_id=<?=base64_encode($val['user_id'])?>"><?=$val['username']?></a></td>
                                            <td><?=$val['staff_id'];?></td>

                                           <td><?=!empty($val['role_id']) ? $roleArry[$val['role_id']] : ''?></td>
                                           <td><?=$val['position'];?></td>
                                           <td><?=$val['department'];?></td>
                                           <td><?=$val['first_name'];?></td>
                                            

                                           <td><?=$val['last_name'];?></td>
                                           <td><?=$val['nick_name'];?></td>
                                          
                                           <td> <?=$val['mobileno'];?></td>
                                           <td> <?=$val['mobileno2'];?></td>
                                           <td> <?=$val['passport_number'];?></td>
                                           <td><?=$val['email'];?></td>
                                           <td><?=!empty($val['dob'])?date('d-m-Y',$val['dob']) : '-'?></td>

                                           <td><?=$val['nationality'];?></td>
                                           <td><?=!empty($val['gender']) ? $stat_array[$val['gender']] : '-';?></td>
                                           
                                           <td><label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                            
                                            <input type="checkbox" <?=$checked?>>
                                     <span class="slider round" id="swId" <?=$val['user_id']?> onclick="switchStatus(<?=$val['user_id']?>,<?=$val['status']?>)"></span>
                                                </label></td>
                                           
                                           
                                           
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>User/">
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
<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>
<script>
var f1 = flatpickr(document.getElementById('dob'),{
            dateFormat:"d-m-Y",
            minDate: "01-01-1899"
            
          });
        var user_id    = "<?=$user_id?>";
        var username   = "<?=$username?>";
        var first_name = "<?=$first_name?>";
        var mob        = "<?=$mobile?>";
        var dob        = "<?=$dob?>";
        var status     = "<?=$status?>";
        var nick_name  = "<?=$nick_name?>";
        var staff_id   = "<?=$staff_id?>";
        var position   = "<?=$position?>";
        var department = "<?=$department?>";
        var passport_number     = "<?=$passport_number?>";
        var role       = "<?=$role?>";
        var uniqueid   = "<?=$uniqueid?>";



 
$(function () { 

   

    // $('#userID').val(userID);
    $('#status').val(status);
    $('#user_list').val(user_id);
    $('#username').val(username);
    $('#first_name').val(first_name);
    $('#mob').val(mob);
    $('#dob').val(dob);
    $('#nick_name').val(nick_name);
    $('#staff_id').val(staff_id);
    $('#position').val(position);
    $('#department').val(department);
    $('#passport_number').val(passport_number);
    $('#role').val(role);
    
    
 
        
    $('[data-toggle="tooltip"]').tooltip(); 
     $('#accordionExample').find('li a').attr("data-active","false");
    $('#userMenu').attr("data-active","true");
    $('#userNav').addClass('show');
    $('#usersList').addClass('active');
    $('#search').click(function(){
        $('#userForm').submit();
    })
});

function pageHistory(status,user_id,username,first_name,mob,dob,nick_name,staff_id,position,department,passport_number,uniqueid,role,page){

    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+user_id+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="first_name" value="'+first_name+'" style="display:none;">');
    $('.pagination').append('<input name="mob" value="'+mob+'" style="display:none;">');
    $('.pagination').append('<input name="dob" value="'+dob+'" style="display:none;">');
   $('.pagination').append('<input name="nick_name" value="'+nick_name+'" style="display:none;">');
   $('.pagination').append('<input name="staff_id" value="'+staff_id+'" style="display:none;">');
   $('.pagination').append('<input name="position" value="'+position+'" style="display:none;">');
   $('.pagination').append('<input name="department" value="'+department+'" style="display:none;">');
   $('.pagination').append('<input name="role" value="'+role+'" style="display:none;">');
    $('.pagination').append('<input name="passport_number" value="'+passport_number+'" style="display:none;">');
   
    
    $('#user_pagination').submit();
}


  $("#pageJumpBtn").click(function(){ 
       
        var user_id    = "<?=$user_id?>";
        var username   = "<?=$username?>";
        var first_name = "<?=$first_name?>";
        var mob        = "<?=$mobile?>";
        var dob        = "<?=$dob?>";
        var status     = "<?=$status?>";
        var nick_name  = "<?=$nick_name?>";
        var staff_id   = "<?=$staff_id?>";
        var position   = "<?=$position?>";
        var department = "<?=$department?>";
        var passport_number = "<?=$passport_number?>";
        var role     = "<?=$role?>";

        var pge            =$("#pageJump").val();
        uniqueid = '';

    pageHistory(status,user_id,username,first_name,mob,dob,nick_name,staff_id,position,department,passport_number,uniqueid,role,pge);
    });

    $('#pageJump').val("<?=$data['curPage'];?>");

// function CleanText(){ 
//   $('#username').val("");
// }
// function ClearID(){ 
//   $('#userID').val("");
// }


function loginFE(userid){
  
  $.post('<?=BASEURL;?>User/GetUser',{'uid':userid},function(response){
      if(response){
        newResp = JSON.parse(response);
        window.open(newResp['response'], '_blank');
      }
  });

}


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
        var username   = "<?=$username?>";
        var first_name = "<?=$first_name?>";
        var nick_name  = "<?=$nick_name?>";
        var staff_id   = "<?=$staff_id?>";
        var position   = "<?=$position?>";
        var department = "<?=$department?>";
        var mob       = "<?=$mobile?>";
        var passport_number = "<?=$passport_number?>";
        var dob        = "<?=$dob?>";
        var status     = "<?=$status?>";
        var role     = "<?=$role?>";
        

    

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>User/Export',{'user_id':user_id,'username':username,'first_name':first_name,'mob':mob,'passport_number':passport_number,'nick_name':nick_name,'staff_id':staff_id,'position':position,'department':department,'dob':dob,'status': status,'role':role},
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
