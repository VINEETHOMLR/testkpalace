<?php 
use inc\Root;
$this->mainTitle = 'Inventory Management';
$this->subTitle  = 'Inventory Edit Request ';

$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

$add_permission       = in_array(56,$this->admin_services) || $this->admin_role == '1' ? true : false;
$this->statusArray = ['0'=>'Approved','1'=>'Rejected','2'=>'Pending'];
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
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Inventory/">

                                 <div class="form-group col-sm-3">
                                   <select type="option" name="user_id" class="form-control custom-select" id="user_list">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($username)):?>
                                          <option value="<?=$user_id?>" selected><?=$username?></option>
                                        <?php endif;?>
                                    </select>

                                  </div>
                                  
                              
                                  <div class="form-group col-sm-2">
                                        <input type="text" id="staff_id" class="form-control" placeholder="Staff Id" name="staff_id">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date">
                                  </div>
                                 
                                  
                                  <div class="form-group col-sm-2">
                                        <select type="option" name="status" class="form-control select" id="status">
                                            <option value="">Status</option>
                                            <?php
                                                foreach ($this->statusArray as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                        </select>
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
                             

                            
                        </div>
                                  



                            </form>
                               
                        </div>

                        

                        
                      
                    </div>

                    <div  class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div  class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width:100%; " role="grid" aria-describedby="dt_info">
                                <thead >
                                  <tr role="row">

                                  <th class="sorting_disabled" rowspan="1" colspan="1">Staff Id</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Requested By</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Brand</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Name</th>

                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Category</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Vintage</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Country</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Volume</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Alcohol %</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Price</th>

                                  <th class="sorting_disabled" rowspan="1" colspan="1">Type</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Stock Quantity</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Edited Quantity</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Requested Time</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Updated Time</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Updated By</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                
                                </thead>
                                <tbody>
                                <?php

                                 
                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                     
                                        

                                       <tr role="row" class="odd">
                                            <td><?=$val['staff_id'];?></td>
                                           <td><a class="badge outline-badge-primary" href="<?=BASEURL?>User/UpdateUser/?user_id=<?=base64_encode($val['user_id'])?>"><?=$val['requested_by']?></a></td>
                                            <td><?=$val['brand'];?></td>

                                           <td><?=$val['name'];?></td>
                                           
                                           <td><?=$val['category'];?></td>
                                           <td><?=$val['vintage'];?></td>
                                           <td><?=$val['country'];?></td>
                                           <td><?=$val['volume'];?></td>
                                           <td><?=$val['alcohol'];?></td>
                                           <td><?=$val['price'];?></td>
                                          
                                           <td><?= $val['type'];?></td>
                                           <td><?= $val['stock_quantity'];?></td>
                                           <td><?= $val['edit_quantity'];?></td>
                                           <td><?= $val['requested_time']?></td>
                                           <td><?= $val['update_time']?></td>
                                           <td><?= $val['updated_name']?></td>
                                           <td><?= $val['status'];?></td>
                                           <td><?= $val['action']?></td>
                                           
                                            
                                           
                                           
                                           
                                       </tr>
                              <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="8" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Inventory/">
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





<div class="modal fade" id="UpdateModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>
                Upadate Request</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" id="statusUpdate">
            <div class="card-block"> 
                <div class="row m-b-30 form-group">
                    
                   
                     <label>Remark  </label>  
                     <textarea class="form-control" id="remark"></textarea>   
                     
                </div>                                                                      
            </div>
           </form> 
        </div>
        <div class="modal-footer">
               <button type="button" class="btn btn-primary mr-3" id="updateBtn" onclick="updateRequest()">Save</button> 
               <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
      </div>
    </div>
</div>


<div class="modal fade" id="RemarkModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>
                View Remark</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" id="statusUpdate">
            <div class="card-block"> 
                <div class="row m-b-30 form-group">
                    
                   
                     <label>Remark  </label>  
                     <span class="form-control" id="remark_span"></span>   
                     
                </div>                                                                      
            </div>
           </form> 
        </div>
        <div class="modal-footer">
                
               <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
      </div>
    </div>
</div>

<script>

var user_id    = "<?=$user_id?>";
var username   = "<?=$username?>";
var dateto     = "<?=$dateto?>";
var datefrom   = "<?=$datefrom?>";
var status     = "<?=$status?>";
var staff_id   = "<?=$staff_id?>";



 
$(function () { 

   

    // $('#userID').val(userID);
    $('#status').val(status);
    $('#user_list').val(user_id);
    $('#username').val(username);
    $('#datefrom').val(datefrom);
    $('#dateto').val(dateto);
    $('#staff_id').val(staff_id);
    
    
    
 
        
    $('[data-toggle="tooltip"]').tooltip(); 
     $('#accordionExample').find('li a').attr("data-active","false");
    $('#userMenu').attr("data-active","true");
    $('#AlcoholNav').addClass('show');
    $('#inventory_edit_list').addClass('active');
    $('#search').click(function(){
        $('#userForm').submit();
    })
});

function pageHistory(status,user_id,staff_id,datefrom,dateto,page){

    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+user_id+'" style="display:none;">');
    $('.pagination').append('<input name="staff_id" value="'+staff_id+'" style="display:none;">');
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('#user_pagination').submit();
}


  $("#pageJumpBtn").click(function(){ 
       
      var user_id    = "<?=$user_id?>";
      var staff_id   = "<?=$staff_id?>";
      var datefrom   = "<?=$datefrom?>";
      var dateto     = "<?=$dateto?>";
      var status     = "<?=$status?>";
      var pge        = $("#pageJump").val();

      pageHistory(status,user_id,staff_id,datefrom,dateto,page);
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

  $.post('<?=BASEURL;?>User/'+url,{'uid':id,'status':changedToStatus},function(response){

      newResp = JSON.parse(response);
      openSuccess(newResp['response'])
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

function showUpdateModal(id,action)
{
    
    $('#updateBtn').attr('onClick', 'updateRequest('+id+','+action+');');
    $('#UpdateModal').modal('toggle');


}


function updateRequest(id,action)
{



    $.ajax({
        url: "<?=BASEURL?>Inventory/updateRequest",
        type: "post",
        data: {'id':id,'remark':$('#remark').val(),'action':action} ,
        success: function (response) {


          
           var newResp = JSON.parse(response);

           if(newResp['status'] == 'success')
           {
               $('#UpdateModal').modal('hide');
               openSuccess(newResp['response'],'<?=BASEURL;?>Inventory/Index/')  
           }else{
                loadingoverlay('error','error',newResp['response']);
           }
           
           
        } 
    });  


  

}

function showRemarkModal(id)
{

    $.ajax({
        url: "<?=BASEURL?>Inventory/ShowRemark",
        type: "post",
        data: {'id':id} ,
        success: function (response) {


          
           var newResp = JSON.parse(response);
           console.log(newResp['remarks']);
           $('#RemarkModal').modal('show');
           $('#remark_span').html("").html(newResp['remarks']);
           
           
        } 
    });   

}

function exportReport(){

    loadingoverlay('info',"Loading","Please Wait");
    $.post('<?=BASEURL?>Inventory/Export',{'status':status,'user_id':user_id,'staff_id':staff_id,'datefrom':datefrom,'dateto':dateto},
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





 
</script>
