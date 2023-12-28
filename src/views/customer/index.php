
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
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Customer/">
                                   
                                  <div class="form-group col-sm-3">
                                    <select type="option" name="user_id" class="form-control custom-select" id="customer_id">
                                        <option value="">Select Email</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="uniqueid" name="uniqueid" placeholder="Enter Customer ID">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="surname" name="surname" placeholder="Enter Surname">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="gname" name="gname" placeholder="Enter Given Name">
                                  </div>
                                   <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="nickname" name="nickname" placeholder="Enter Nickname">
                                  </div>
                                   <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="mob" name="mob" placeholder="Enter Mobile No.">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="date"  class="form-control flatpickr-input active" id="dob" name="dob" min='1899-01-01' max='2000-13-13' placeholder="Enter Date of Birth">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="assname" name="assname" placeholder="Enter Assistant Name">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="accman" name="accman" placeholder="Enter Account Manager">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="referal" name="referal" placeholder="Enter Referral Name">
                                  </div>

                                  <div class="form-group col-sm-3">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom">
                                  </div>
                                  <div class="form-group col-sm-3">
                                        <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date">
                                  </div>
                                  
                                  
                                  <div class="form-group col-sm-3">
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
                                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;">Yesterday</a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;">Today </a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;">Last 7 days</a> </span>
                                <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>
                                      
                            </div>
                            <div class="col-md-7 col-lg-7">
                                      <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="Search">
                            </div>
                              <div class="form-group col-sm-3">
                                       <a href="<?=BASEURL?>Customer/CreateCustomer/"><button class="btn btn-outline-info addbtn mb-2" >
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Create Customer</button></a>
                                  </div>

                            
                        </div>
                      
                    </div>

                    <div  class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div  class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width:100%; " role="grid" aria-describedby="dt_info">
                                <thead >
                                  <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >ID</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">User Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Surname</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Given Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Nickname</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Mobile</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Email ID</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Date Of Birth</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Nationality</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Language</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Allergies</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Assistant Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Assistant Phone</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Assistant Email</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Account Manager</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Referral</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Remarks</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Join Time</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                      <?php 
                                        $checked = (empty($val['status'])) ? 'checked' : '';  ?>

                                       <tr role="row" class="odd">
                                           <td><a class="badge outline-badge-primary" href="<?=BASEURL?>Customer/Account/?user=<?=$val['id']?>"><?=$val['uniqueid']?></a></td>
                                           <td><?=$val['username'];?></td>

                                           <td><?=$val['surname'];?></td>
                                           <td><?=$val['given_name'];?></td>
                                           <td><?=$val['nickname'];?></td>
                                           <td>+<?=$val['phonecode'];?>  <?=$val['mobile'];?></td>
                                           <td><?=$val['email'];?></td>
                                           <td><?=$val['dob'];?></td>
                                           <td><?=$val['country'];?></td>
                                           <td><?=$val['language'];?></td>
                                           <td><?=$val['allergies'];?></td>
                                           <td><?=$val['assistant_name'];?></td>
                                           <td><?=$val['assistant_mobile'];?></td>
                                           <td><?=$val['assistant_email'];?></td>
                                           <td><?=$val['account_manager'];?></td>
                                           <td><?=$val['referral'];?></td>
                                           <td><?=$val['remarks'];?></td>
                                           <td><label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                                <input type="checkbox" <?=$checked?>>
                                                <span class="slider round" id="swId"<?=$val['id']?> onclick="switchStatus(<?=$val['id']?>,<?=$val['status']?>);"></span>
                                                </label></td>
                                           <td><?=$val['time'];?></td>
                                           
                                           
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Customer/">
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
<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>
<script>
var f1 = flatpickr(document.getElementById('dob'),{
            dateFormat:"d-m-Y",
            minDate: "01-01-1899"
          });
var datefrom   = '<?=$datefrom?>';
var dateto     = '<?=$dateto?>';
var userId    = '<?=$user_id?>';
var status     = '<?=$status?>';
var customername   = '<?=$username?>';
var surname     = '<?=$surname?>';
var gname   = '<?=$given_name?>';
var nickname     = '<?=$nickname?>';
var mob   = '<?=$mobile?>';
var dob     = '<?=$dob?>';
var assname   = '<?=$assistant_name?>';
var accman   = '<?=$account_manager?>';
var referal   = '<?=$referral?>';
var uniqueid   = '<?=$uniqueid?>';



 
$(function () { 

   

    // $('#userID').val(userID);
    $('#status').val(status);
    $('#datefrom').val(datefrom);
    $('#dateto').val(dateto);
    $('#customername').val(customername);
    $('#customer_id').val(userId);
    $('#surname').val(surname);
    $('#gname').val(gname);
    $('#nickname').val(nickname);
    $('#mob').val(mob);
    $('#dob').val(dob);
    $('#assname').val(assname);
    $('#accman').val(accman);
    $('#referal').val(referal);
    $('#uniqueid').val(uniqueid);
    
    
 
        
    $('[data-toggle="tooltip"]').tooltip(); 
     $('#accordionExample').find('li a').attr("data-active","false");
    $('#custMenu').attr("data-active","true");
    $('#custNav').addClass('show');
    $('#userList').addClass('active');
    $('#search').click(function(){
        $('#userForm').submit();
    })
});

function pageHistory(datefrom,dateto,status,user_id,surname,gname,nickname,mob,dob,assname,accman,referal,uniqueid,page){

    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
     $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+user_id+'" style="display:none;">');
   
    $('.pagination').append('<input name="surname" value="'+surname+'" style="display:none;">');
    $('.pagination').append('<input name="gname" value="'+gname+'" style="display:none;">');
    $('.pagination').append('<input name="nickname" value="'+nickname+'" style="display:none;">');
    $('.pagination').append('<input name="mob" value="'+mob+'" style="display:none;">');
    $('.pagination').append('<input name="dob" value="'+dob+'" style="display:none;">');
     $('.pagination').append('<input name="assname" value="'+assname+'" style="display:none;">');
     $('.pagination').append('<input name="accman" value="'+accman+'" style="display:none;">');
     $('.pagination').append('<input name="referal" value="'+referal+'" style="display:none;">');
     $('.pagination').append('<input name="uniqueid" value="'+uniqueid+'" style="display:none;">');
    
    $('#user_pagination').submit();
}


  $("#pageJumpBtn").click(function(){ 
       
        var status         = "<?=$status;?>"; 
        var date_from      = "<?=$datefrom;?>";
        var date_to        = "<?=$dateto;?>";
        var user_id        = "<?=$user_id;?>";
        var surname        = "<?=$surname;?>";
        var gname   = '<?=$given_name?>';
        var nickname     = '<?=$nickname?>';
        var mob   = '<?=$mobile?>';
        var dob     = '<?=$dob?>';
        var assname   = '<?=$assistant_name?>';
        var accman   = '<?=$account_manager?>';
        var referal   = '<?=$referral?>';
        var uniqueid   = '<?=$uniqueid?>';

        var pge            =$("#pageJump").val();

    pageHistory(date_from,date_to,status,user_id,surname,gname,nickname,mob,dob,assname,accman,referal,uniqueid,pge);
    });

    $('#pageJump').val("<?=$data['curPage'];?>");

// function CleanText(){ 
//   $('#username').val("");
// }
// function ClearID(){ 
//   $('#userID').val("");
// }

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

function loginFE(userid){
  
  $.post('<?=BASEURL;?>Customer/GetUser',{'uid':userid},function(response){
      if(response){
        newResp = JSON.parse(response);
        window.open(newResp['response'], '_blank');
      }
  });

}


 function exportReport(){
        var datefrom   = "<?=$datefrom?>";
        var dateto     = "<?=$dateto?>";
        var user_id    = "<?=$user_id?>";
        var surname   = "<?=$surname?>";
        var gname     = "<?=$given_name?>";
        var nickname   = "<?=$nickname?>";
        var mob       = "<?=$mobile?>";
        var dob        = "<?=$dob?>";
        var assname     = "<?=$assistant_name?>";
        var accman     = "<?=$account_manager?>";
        var referal     = "<?=$referral?>";
        var status     = "<?=$status?>";
        var uniqueid     = "<?=$uniqueid?>";

        

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>Customer/Export',{'datefrom':datefrom,'dateto':dateto,'user_id':user_id,'surname':surname,'gname':gname,'nickname':nickname,'mob':mob,'dob':dob,'assname':assname, 'accman':accman,'referal':referal,'status': status,'uniqueid': uniqueid},
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

$('#customer_id').select2({
    placeholder: 'Customer Username',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>Customer/GetCustomersDetails',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var userType = $('#userType').val();
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
</script>
