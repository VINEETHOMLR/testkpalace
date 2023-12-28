<?php 
use inc\Root;
$this->mainTitle = "Language";
$this->subTitle  = '';

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
  /*table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
}*/
</style>

<div id="content" class="main-content">
  <div class="layout-px-spacing">
    <div class="row layout-top-spacing">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
          <div class="row">
            <div class="row col-md-12 col-xs-12">
              <form id="LanguageForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Language/index/">
                
                <div class="form-group col-sm-3">
                    <input type="text" name="lang_key" class="form-control select" id="lang_key" placeholder="Search By Key">
                </div>
                <div class="form-group col-sm-3">
                    <input type="text" name="en" class="form-control select" id="en" placeholder="Search By English Title">
                </div>
				        <div class="form-group col-sm-3">
                    <input type="text" name="fepage" class="form-control select" id="fepage" placeholder="Search By Page">
                </div>
               
             
               
                <div class="form-group col-sm-2">
                    <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="<?=Root::t('app','search_txt')?>">
                </div>
              </form>
            </div>

            <div class="row col-md-12 col-lg-12">
                           
              <div class="col-md-5 col-lg-5">
                <span class="badge badge-primary "> <a  style="color:#fff;cursor: pointer;" onClick="showImport()">Import</a> </span>
                <span class="badge badge-primary "> <a  style="color:#fff;cursor: pointer;" onClick="exportReport()">Export</a> </span>
                <span class="badge badge-primary "> <a class="date-seven" style="color:#fff;cursor: pointer;" onClick="CreateExcel()">Sample File</a> </span>
                                 
              </div>
              <div class="col-md-7 col-lg-7">
                              
                <a href="<?=BASEURL;?>Language/Create/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add Language</button>
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
                                    <th class="sorting_disabled" rowspan="1" colspan="1" >Key</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Page</th>
								  <?php  foreach ($LanguageArray as $key => $languages) {?>
								  <th class="sorting_disabled" rowspan="1" colspan="1"><?=$languages['lang_name'].'('.$languages['lang_code'].')'?></th>
								  <?php }?>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['lang_key']?></td>
                                           <td><?=$val['page']?></td>
                                           <?php  foreach ($LanguageArray as $keys => $languages) {?>
										   <td id="<?= $val['id'].'__'.$languages['lang_code']?>" ><?=$val[$languages['lang_code']]?></td>
										   <?php }?>
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Language/Index/">
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

	
 <div class="modal fade" id="langupdatemodel" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
              Update Language</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="modal-form">
            <div class="card-block">    
            
              <div class="row m-b-30">
                  <div class="col-12 form-group">
                      <label>Update Language</label>      
                      <textarea class="form-control" id="updatelang"></textarea>
                  </div>
                  <input type="hidden" name="rowid" id="rowid" value="">
				  
              </div>

            </div>
          </form>
          <div class="modal-footer mt-1 mb-1">
            <button type="button" class="btn btn-primary mr-3" onclick="updateLanguage()">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=root::t('announcement','can')?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
 <div class="modal fade" id="importmodel" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
              Import Language</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="modal-form">
            <div class="card-block">    
            
              <div class="row m-b-30">
                  <div class="col-12 form-group">
                      <label>Select File</label>      
                      <input type="file" class="form-control" name="importexcel"  class="" id="importexcel" accept="xlsx/*" >
                  </div>
                  
              </div>

            </div>
          </form>
          <div class="modal-footer mt-1 mb-1">
            <button type="button" class="btn btn-primary mr-3" onclick="importLanguage()">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=root::t('announcement','can')?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>
<?php

$error_dash = Root::t('app','error_dash');
$success    = Root::t('app','suucess_txt');
$okay       = Root::t('app','okay_btn'); 
$load1      = Root::t('app','load1_txt');
$load2      = Root::t('app','load2_txt');
?>


  <script>
       

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#langmenu').attr("data-active","true");

    $(function () {

         
        $('#lang_key').val("<?=$lang_key;?>");
        $('#fepage').val("<?=$fepage;?>");
        $('#en').val("<?=$en;?>");

        });
        
        var lang_key    = "<?=$lang_key;?>"; 
        var fepage    = "<?=$fepage;?>";
        var en    = "<?=$en;?>";        

      $('#search').click(function(){
        $('#LanguageForm').submit();
    })


function exportReport(){
       var lang_key     = $('#lang_key').val();
       var fepage     = $('#fepage').val();
       var en     = $('#en').val();

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>Language/Export',{'lang_key':lang_key,'fepage':fepage,'en':en},
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

  

  function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Language Key !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Language/Delete/',{getId:val},function(response){ 
    
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        openSuccess(newResp['response'])
                    }else{ 
                        loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
                    }
                });
                return false;
            }
        })
  }

  function pageHistory(lang_key,en,fepage,page){
    $('.pagination').append('<input name="lang_key" value="'+lang_key+'" style="display:none;">');
    $('.pagination').append('<input name="en" value="'+en+'" style="display:none;">');
    $('.pagination').append('<input name="fepage" value="'+fepage+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#user_pagination').submit();
  }

   $("#pageJumpBtn").click(function(){
  var pge=$("#pageJump").val();
  pageHistory(lang_key,en,fepage,pge);
 });

 $('#pageJump').val("<?=$data['curPage'];?>");

  function updateLanguage(){

    let updatelang    = $('#updatelang').val();
    let rowid         = $('#rowid').val();

    if(updatelang ==''){
       loadingoverlay('error','error','Please Add Content To Proceed');
       return false;
    }

    if(rowid ==''){
       loadingoverlay('error','error','Error! Try Again Later');
       return false;
    }

    $.post('<?=BASEURL;?>Language/singleUpdate/',{'updatelang':updatelang,'rowid':rowid},function(response){  

        newResp = JSON.parse(response);
        if(newResp['status'] == 'success'){
            openSuccess(newResp['response'])  
        }else{
            loadingoverlay('error','error',newResp['response']);
        }
        return false;
    }); 
    return false;
}
 function CreateExcel() {
    // Create a hidden iframe
    var iframe = $('<iframe>', {
        css: {
            display: 'none'
        }
    }).appendTo('body');

    // Submit a GET request to the download URL using the iframe
    iframe.attr('src', '<?= BASEURL ?>Language/CreateExcel/');
}
function showImport()
{
	$('#importmodel').modal('show');
}

  function importLanguage(){

    data = new FormData();

    data.append('importexcel', $('#importexcel')[0].files[0]);
 
    loadingoverlay('info',"Please Wait","Import In Progress");

    $.ajax({
        url: '<?=BASEURL;?>Language/BulkUpload/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
        // alert(response);
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
                 {
                 openSuccess(newResp['response'],'<?=BASEURL;?>Language/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 
   }
       $('td').on('dblclick', function(e) {
   
		var id   = (e.target.id);
		var text = $("#"+id).text();
		
		$("#updatelang").html(text);
		$("#rowid").val(id);
		//$('#addModal').modal('show');
		$('#langupdatemodel').modal('show');
		

        });
</script>

 



