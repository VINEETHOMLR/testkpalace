
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
                            <?php if($this->admin_role==1) { ?>
                              <div class="form-group col-sm-3">
                                       <a href="<?=BASEURL?>TermsAndConditions/Create/"><button class="btn btn-outline-info addbtn mb-2" >
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Add New</button></a>
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
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Sl.No</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">File</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Language</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            if(!empty($data['data'])){
                                                $i=1;
                                                foreach($data['data'] as $key => $val): ?>
                                                <?php 
                                                $checked = (empty($val['status'])) ? 'checked' : '';  ?>
                                                <tr role="row" class="odd">
                                           
                                                    <td><?=$i?></td>
                                                    <td><a class="badge outline-badge-primary" href="<?=BASEURL?>web/upload/termsandconditions/<?=$val['file_name'];?>" target="_blank"/>View Pdf</td>
                                                    <td><?=$val['lang'];?></td>
                                                    <td><?=$val['action'];?></td>
                                                </tr>
                                                <?php $i++; endforeach;
                                            }else{
                                                echo '<tr><td colspan="3" class="text-center">No Data Found</td></tr>';
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
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
var status      = "<?=$status?>";
$(function () {
    // $('#userID').val(userID);
    $('#status').val(status);   
    $('[data-toggle="tooltip"]').tooltip(); 
     $('#accordionExample').find('li a').attr("data-active","false");
    $('#settingsMenu').attr("data-active","true");
    $('#settingsNav').addClass('show');
    $('#termsandConditions').addClass('active');
    
});

function switchStatus(id,status){
    if(status==0){
        var btn  = "Activate"; var txt = "Are you sure want to proceed ?";
        var swClass ='';
        var changedToStatus = 1 ;
    }else{
        var btn  = "Deactivate";  var txt = "Are you sure want to proceed ?";
        var swClass ='';
        var changedToStatus = 0 ;
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
            $.post('<?=BASEURL;?>TermsAndConditions/UpdateStatus',{'getId':id,'status':changedToStatus},function(response){
                hideoverlay();
                newResp = JSON.parse(response);
                if (newResp['status'] == 'success') {
                    openSuccess(newResp['response']);
                } else {
                    loadingoverlay("error", "Error", newResp['response']);
                    window.setTimeout(function(){ 
                        location.reload();
                    } ,3000);
                }
            });
            return false;

        }else{
            location.reload();
        }
    })
}


</script>
