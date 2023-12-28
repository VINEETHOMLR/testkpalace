<?php 
use inc\Root;
$this->mainTitle = 'Memo';
$this->subTitle  = 'Memo';

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
     margin-right: 30px;
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
 
  iframe,video{
    width: 100%;
    height: 500px;
  }
  .width2{
      width: 200px;
      word-wrap: break-word;
  }
</style>

<div id="content" class="main-content">
  <div class="layout-px-spacing">
    <div class="row layout-top-spacing">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
          <div class="row">
            <div class="row col-md-12 col-xs-12">
              <form id="AnnounceForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Memo/">
                <div class="form-group col-sm-2 ">
                  <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom">
                </div>
                <div class="form-group col-sm-2">
                  <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date">
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" name="slug_name" class="form-control select" id="slug_name" placeholder="Memo Name">
                </div>
                <div class="form-group col-sm-2">
                  <select type="option" name="status" class="form-control select" id="status">
                    <option value="">Status</option>
					<?php  foreach ($this->statusArry as $key => $statuses) {?>
						<option value="<?=$key?>"><?=$statuses?></option>
					<?php }?>
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
                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;">Today</a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;">Last 7 days</a> </span>
                                 
              </div>
              <div class="col-md-7 col-lg-7">
                              
                  <a href="<?=BASEURL;?>Memo/Update/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add Memo</button>
                </a>
                                    
              </div>
                           
            </div>
                             
                                           
          </div>

          <div class="widget-content widget-content-area">

              <div class="table-responsive mb-4 mt-4">
                    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                          <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                    <th class="sorting_disabled" rowspan="1" colspan="1" >Date</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Memo Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Form Date</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">To Date</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Position</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): 

                                       //$current_memo = ($val['is_current_memo'] == 1) ? '<br><span style="color:red;font-size:10px;"><b>Current Showing Memo</b></span>' : '';
                                ?>
                                       <tr role="row" class="odd">
                                           <td><?=date("d-m-Y H:i:s",$val['createtime'])?></td>
                                           <td><?=$val['slug']?></td>
                                           <td><?=date("d-m-Y H:i:s",$val['from_date'])?></td>
                                           <td><?=date("d-m-Y H:i:s",$val['to_date'])?></td>
                                           <td><?=$val['position']?></td>
                                           <td><?=$val['status']?></td>
                                           <td><?=$val['action']?></td>
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Memo/">
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
        
<script>

    var status    = "<?=$status;?>"; 
    var datefrom  = "<?=$datefrom;?>";
    var dateto    = "<?=$dateto;?>";
    var slug_name = "<?=$slug_name;?>";

    $(document).ready(function() {

        $('#status').val(status);
        $('#datefrom').val(datefrom);
        $('#dateto').val(dateto);
        $('#slug_name').val(slug_name);

  
    });
    
    $('#search').click(function(){
        $('#AnnounceForm').submit();
    })

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#settingsMenu').attr("data-active","true");
    $('#settingsNav').addClass('show');
    $('#memoNav').addClass('active');

    function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Memo !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Memo/Delete/',{getId:val},function(response){ 
    
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

    function pageHistory(datefrom,dateto,status,slug_name,page){
       $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
       $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
       $('.pagination').append('<input name="slug_name" value="'+slug_name+'" style="display:none;">');
       $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
       $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
       $('#user_pagination').submit();
    }

  $("#pageJumpBtn").click(function(){
  var pge=$("#pageJump").val(); 
  pageHistory(datefrom,dateto,status,slug_name,pge)
 });

 $('#pageJump').val("<?=$data['curPage'];?>");

  function switchStatus(id,status){

      var changedToStatus = (status == 1) ? 0 : 1 ;

      $.post('<?=BASEURL;?>Memo/UpdateMemoStatus/',{'id':id,'status':changedToStatus},function(response){
            if(response){
              openSuccess('Status Updated Successfully')
            }
      });
  }

 
</script>

       



