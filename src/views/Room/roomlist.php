
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
                            <form id="userForm" class="row col-md-8" method="post" action="<?=BASEURL;?>Room/">
                                  <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="maximum_allowed" name="maximum_allowed" onkeypress="return isNumber(event)" placeholder="Enter Maximum Allowed">
                                  </div>
                                 
                                  <div class="form-group col-sm-3">
                                    <input type="text"  class="form-control select" id="price" name="price" placeholder="Enter Price" onkeypress="return isNumber(event)">
                                  </div> 
                                  <div class="form-group col-sm-3">
                                    <select id="type" name="type" class="form-control select" >
                                                  <option value="">Type</option>
                                                  <option value="1">Room</option>
                                                  <option value="2">Table</option>
                                                  </select> 
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
                               
                           
                            <div class="col-md-2">
                                      <input type="submit" class="btn btn-success col-md-11 div_float" id="search" name="Search" value="Search">
                            </div>

                            <?php if(  in_array(63, $this->admin_services) ||($this->admin_role==1)) { ?>
                              <div class="form-group col-sm-2">
                                       <a href="<?=BASEURL?>Room/Createroom/"><button class="btn btn-outline-info addbtn mb-2" >
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Create Room/Table</button></a>
                                  </div>

                              <?php } ?>
                        </div>
                      
                    </div>

                    <div  class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div  class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width:100%; " role="grid" aria-describedby="dt_info">
                                <thead >
                                  <tr role="row">
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Type</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Level</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Room/Table Number</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Description</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Maximum Allowed</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Price</th>
                                   <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                   <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                
                                </thead>
                                <tbody>
                                <?php

                                 $stat_array=['1'=>'Room','2'=>'Table'];
                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                      <?php 
                                        $checked = (empty($val['status'])) ? 'checked' : '';  ?>

                                       <tr role="row" class="odd">
                                           <td><?=$stat_array[$val['type']];?></td>
                                           <td><?=$val['level'];?></td>
                                           <td><?=$val['room_no'];?></td>
                                          
                                          
                                           <td> <?=$val['description'];?></td>
                                           <td> <?=$val['max_allowed'];?></td>
                                           <td> <?=number_format($val['price'],2);?></td>
                                            <td><label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                            
                                            <input type="checkbox" <?=$checked?>>
                                          <span class="slider round" id="swId" <?=$val['id']?> onclick="switchStatus(<?=$val['id']?>,<?=$val['status']?>)"></span>
                                                </label></td>
                                                <?php if(  in_array(76, $this->admin_services) ||($this->admin_role==1)) { ?>
                                           <td> <a href="<?=BASEURL?>Room/Updateroom/?id=<?=$val['id'];?>"><button class="btn btn-info" > Edit </button></a></td>
                                          
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Room/">
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
 
        var maximum_allowed = "<?=$maximum_allowed?>";
        var type      = "<?=$type?>";
        var price      = "<?=$price?>";
        var status      = "<?=$status?>";

$(function () { 

   

    // $('#userID').val(userID);
    $('#status').val(status);
     $('#type').val(type);
    $('#maximum_allowed').val(maximum_allowed);
    $('#price').val(price);
    
    
 
        
    $('[data-toggle="tooltip"]').tooltip(); 
     $('#accordionExample').find('li a').attr("data-active","false");
    $('#settingsMenu').attr("data-active","true");
    $('#settingsNav').addClass('show');
    $('#createroom').addClass('active');
    
});
$('#search').click(function(){
        $('#userForm').submit();
    })
function pageHistory(status,$type,max_allowed,price,page){

    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="type" value="'+type+'" style="display:none;">');
    $('.pagination').append('<input name="maximum_allowed" value="'+maximum_allowed+'" style="display:none;">');
    $('.pagination').append('<input name="price" value="'+price+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    
    $('#user_pagination').submit();
}


  $("#pageJumpBtn").click(function(){ 
       
      
        var maximum_allowed = "<?=$maximum_allowed?>";
        var type      = "<?=$type?>";
        var price      = "<?=$price?>";
        var status     = "<?=$status?>";

        var pge            =$("#pageJump").val();

    pageHistory(status,type,maximum_allowed,price,pge);
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

 
function switchStatus(id,status){
             var url = 'Blockroom';
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
                      $.post('<?=BASEURL;?>Room/'+url,{'uid':id,'status':changedToStatus},function(response){
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
function showEditModal(id)
{
    
    selectedId = id;
    $.ajax({
        url: "<?=BASEURL?>Room/EditRoom",
        type: "post",
        data: {'id':id} ,
        success: function (response) {
          
           var newResp = JSON.parse(response);
          /* $('#foc_reamrk').val(newResp['remark']);
           $('#editModal').modal('show');*/
        } 
    });

    

}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
       alert('Please enter numbers only!');
    }
    return true;
}


</script>
