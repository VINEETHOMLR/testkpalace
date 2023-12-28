
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
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Customer/Gallery">
                              <div class="form-group col-sm-3">
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                  </div>
                                    
                                  <div class="form-group col-sm-2">
                                    <select type="option" name="room_id" class="form-control custom-select" id="room_id">
                                        <option value="">Select Room</option>
                                            <?php

                                            if(!empty($roomList)){
                                                foreach($roomList as $key=>$value){
                                            ?>
                                        <option value="<?=$value['id']?>"><?=$value['description']?></option>
                                            <?php }
                                            }?>
                                    </select>
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
                                                foreach ($this->ImgArr as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                        </select>
                                  </div>
                                <div class="form-group col-sm-2">
                                  <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="Search">
                                </div>
                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                           
                            <div class="col-md-5 col-lg-5">
                                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;">Yesterday</a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;">Today </a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;">Last 7 days</a> </span>
                                      
                            </div>
                            

                            <div class="col-md-7 col-lg-7">
                              
                <a href="<?=BASEURL;?>Customer/Create/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add Gallery</button>
                </a>
                                    
              </div>
                        </div>
                      
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >User ID</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Room</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Email ID</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Image</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Create Time</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data']['data'])){
                                     foreach($data['data']['data'] as $key => $val): ?>
                                      <?php 
                                        $checked = (empty($val['status'])) ? 'checked' : '';  
                                        $statusArray = array('0'=>'Active','1'=>'Hide');
                                        ?>

                                       <tr role="row" class="odd">
                                           <td><?=$val['uniqueid']?></a></td>                               
                                           <td><?=$val['fname'];?></td>
                                           <td><?=$val['room_no']?></a></td>        
                                           <td><?=$val['email'];?></td>
                                           <td>
                                            <?php 
                                            echo (empty($val['image'])) ? "-" : '<button class="btn btn-outline-primary mb-2" onclick="viewImage('.$val['id'].')">View Image</button>'
                                            ?>
                                              
                                            </td>
                                           <td><?=$statusArray[$val['status']]?></td>
                                           <td><?=$val['time'];?></td>
                                           <td>
                                               <?php  
                                                   echo '<a href="'.BASEURL.'Customer/Create/?id='.$val['id'].'"><button class="btn btn-info">Edit</button></a>
                                                   <button class="btn btn-info" onclick="deleteThis('.$val['id'].')">Delete</button>';
                                               ?>

                                           </td>
                                           
                                       </tr>
                              <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="10" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Customer/Gallery">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$data['pagination'];?>
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

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Gallery Images</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="closemodal()" style="margin-top:3px"  aria-label="Close"> x </button>

            </div>
            <div class="modal-body" >
              <img class="img_gallery" src="" style="width:250px;height:250px;margin-left:80px">
             </div>
          
        </div>
    </div>
</div>

<script>

var datefrom   = '<?=$data['datefrom']?>';
var dateto     = '<?=$data['dateto']?>';
var room_id     = '<?=$data['room_id']?>';
var userId    = '<?=$data['user_id']?>';
var status     = '<?=$data['status']?>';
var username   = '<?=$data['username']?>';
 
$(function () { 

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#custMenu').attr("data-active","true");
    $('#custNav').addClass('show');
    $('#gallery').addClass('active');

    // $('#userID').val(userID);
    $('#status').val(status);
    $('#datefrom').val(datefrom);
    $('#dateto').val(dateto);
    $('#room_id').val(room_id);
    $('#username').val(username);
        
    $('[data-toggle="tooltip"]').tooltip(); 
    
    $('#search').click(function(){
        $('#userForm').submit();
    })
});

function pageHistory(page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+userId+'" style="display:none;">');
    $('.pagination').append('<input name="room_id" value="'+room_id+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    
    $('#user_pagination').submit();
}



 $("#pageJumpBtn").click(function(){
    pageHistory($("#pageJump").val());
 });

 function viewImage(Id){  

    $.ajax({
        url: "<?=BASEURL?>Customer/getimage",
        type: "post",
        data: {'gallery':Id} ,
        success: function (response) {
          var image = response.imagename;
          var image_url = '<?=FRONTEND?>web/upload/gallery/'+image;
           $('#img_pId').val(Id);
           $('.img_gallery').attr('src',"");
           $('.img_gallery').attr('src',image_url);
           $('#imageModal').modal('show');
        } 
    });
}

 $('#pageJump').val("<?=$data['page'];?>");

 function closeModal(){
     $('#imageModal').toggle()
  }

    function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This gallery !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
              // loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Customer/Delete/',{getId:val},function(response){ 
    
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        openSuccess(newResp['response'])
                    }else{ 
                        loadingoverlay('error','Error',newResp['response']);
                    }
                });
                return false;
            }
        })
  }
</script>
