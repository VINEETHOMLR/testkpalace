
<style type="text/css">

  .select2-container--default .select2-selection--multiple{
     padding: 3px 10px !important;
   }
  
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
  height: 40px;
}

</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row">
                        <form id="btc_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Alcohol/List/">
                            <div class="row col-md-12 col-xs-12"> 
                                 <!-- <div class="form-group col-sm-2">
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Customer</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                  </div>-->
                                  <div class="form-group col-sm-3">
                                    <input type="text" name="name" class="form-control select" id="name" placeholder="Name">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text" name="brand" class="form-control select" id="brand" placeholder="Brand">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <select type="option" name="foc_id" class="form-control custom-select" id="foc_id">
                                        <option value="">Select FOC </option>
                                        <option value="0">No FOC </option>
                                        <option value="1">FOC</option>
                                        
                                    </select>
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <select type="option" name="category" class="form-control custom-select" id="category">
                                        <option value="">Select Category</option>
                                       <?php  foreach ($categoryArray as $category) {
                                            
                                   echo '<option value="'.$category['id'].'" >'.html_entity_decode(html_entity_decode($category['name'])).'</option>';
                                     } ?>
                                    </select>
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <select type="option" name="supplier" class="form-control custom-select" id="supplier">
                                        <option value="">Select Supplier</option>
                                       <?php  foreach ($supplierArray as $supplier) {
                                   echo '<option value="'.$supplier['id'].'" >'.html_entity_decode(html_entity_decode($supplier['supplier_name'])).'</option>';
                                     } ?>
                                    </select>
                                  </div>

                                   
                                  <div class="form-group col-sm-3">
                                    <input type="text" name="vintage" class="form-control select" id="vintage" placeholder="Vintage">
                                  </div>
                                   <div class="form-group col-sm-3">
                                    <input type="text" name="country" class="form-control select" id="country" placeholder="Country">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text" name="volume" class="form-control select" id="volume" placeholder="Volume">
                                  </div>
                                  <div class="form-group col-sm-3">
                                    <input type="text" name="price" class="form-control select" id="price" placeholder="price">
                                  </div>
                            
                        </form>

                        <div class="form-group col-sm-3">
                            <input type="submit" class="btn btn-success" id="search" name="Search" value="Search">
                        </div>        
                    </div>                
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <a href="<?=BASEURL;?>Alcohol/UpdateAlcohol/" class="full_width div_float">
                            <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add New</button>
                        </a>
                
                    </div>
                    <div class="col-md-12 col-lg-12">

                        <button type="button" class="btn btn-primary full_width div_float" data-toggle="modal" data-target="#excelModal">
                            Import Inventory
                        </button>
                    </div>

                    <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>
                </div>
                <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                    <thead>
                                        <tr>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">FOC</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Item Date</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Product Name</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Category</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Supplier</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Brand</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Vintage</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Country</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Volume</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Alcohol (%)</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Unit Price</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Selling Price</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Balance Quantity</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(!empty($data['data'])){
                                            foreach($data['data'] as $key => $val):?>
                                                <tr role="row" class="odd">
                                                    <td><?=$val['foc']?></td>
                                                    <td><?= !empty($val['item_date']) ? $val['item_date'] : '-'?></td>
                                                    <td><?=htmlspecialchars_decode($val['name'])?></td>
                                                    <td><?=htmlspecialchars_decode($val['category'])?></td>
                                                    <td><?=htmlspecialchars_decode($val['supplier'])?></td>
                                                    <td><?=$val['brand']?></td>
                                                    <td><?=$val['vintage']?></td>
                                                    <td><?=$val['country']?></td>
                                                    <td><?=$val['volume']?></td>
                                                    <td><?=$val['alcohol_percent']?></td>
                                                    <td><?=$val['unit_price']?></td>
                                                    <td><?=$val['price']?></td>
                                                    <td><?=$val['quantity']?></td>
                                                    <td><?=$val['action']?></td>
                                                </tr>
                                            <?php endforeach;
                                        }else{
                                             echo '<tr><td colspan="14" class="text-center">No Data Found</td></tr>';
                                        }   
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row text-center">
                            <form class="col-md-12" id="deposit_pagination" method="post" action="<?=BASEURL;?>Alcohol/List/">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination;?>
                                    </ul>
                                </div>
                                <input name="sub" id="subs" value="" style="display: none;">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>
</div>
</div>

<!-- The Modal -->
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
                    
                   
                     <label>Excel File * <a href="<?= BASEURL.'web/assets/Sample_excels/Import_Sample_inventroy.csv'?>" download>Sample File<i class="fa fa-download" aria-hidden="true"></i></a> </label>     
                     <input type="file" id="file" name="file" class="form-control" style="height:53px;">
                </div>                                                                      
            </div>
           </form> 
        </div>
        <div class="modal-footer">
               <button type="button" class="btn btn-primary mr-3" onclick="upload()">Upload</button> 
               <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
      </div>
    </div>
</div>


<div class="modal fade" id="ErrorListModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="max-width: 700px;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>
                Error Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="table_content">
          
        </div>
        <div class="modal-footer">
             
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
             
               <button type="button" class="btn btn-primary" value="" id="proceedImport">Proceed</button>
               <button type="button" class="btn btn-danger cancelImport" value="" >Cancel</button>
            </div>
        </form>
      </div>
    </div>
</div>
<script>

  var brand       = '<?=$brand?>';
  var name        = '<?=$name?>';
  var vintage     = '<?=$vintage?>';
  var country     = '<?=$country?>';
  var volume      = '<?=$volume?>';
  var price       = '<?=$price?>';
  var category    = '<?=$category_id?>';
  var supplier    = '<?=$supplier_id?>';
  var foc_id    = '<?=$foc_id?>';

  $('#brand').val(brand);
  $('#name').val(name);
  $('#vintage').val(vintage);
  $('#country').val(country);
  $('#volume').val(volume);
  $('#price').val(price);
  $('#category').val(category);
  $('#supplier').val(supplier);
  $('#foc_id').val(foc_id);

 
$(function () { 

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#AlcoholMenu').attr("data-active","true");
  $('#AlcoholNav').addClass('show');
  $('#al_list').addClass('active');

  $('#search').click(function(){
      $('#btc_form').submit();
  })
});

function pageHistory(name,brand,vintage,country,volume,price,category,supplier,foc_id,page){
    $('.pagination').append('<input name="name" value="'+name+'" style="display:none;">');
    $('.pagination').append('<input name="brand" value="'+brand+'" style="display:none;">');
    $('.pagination').append('<input name="vintage" value="'+vintage+'" style="display:none;">');
    $('.pagination').append('<input name="country" value="'+country+'" style="display:none;">');
    $('.pagination').append('<input name="volume" value="'+volume+'" style="display:none;">');
    $('.pagination').append('<input name="price" value="'+price+'" style="display:none;">');
    $('.pagination').append('<input name="category" value="'+category+'" style="display:none;">');
    $('.pagination').append('<input name="supplier" value="'+supplier+'" style="display:none;">');
    $('.pagination').append('<input name="foc_id" value="'+foc_id+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
   
   
    $('#deposit_pagination').submit();
}



  function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Alcohol !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Alcohol/DeleteAlcohol/',{getId:val},function(response){ 
    
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
 $("#pageJumpBtn").click(function(){

    var brand       = '<?=$brand?>';
    var name        = '<?=$name?>';
    var vintage     = '<?=$vintage?>';
    var country     = '<?=$country?>';
    var volume      = '<?=$volume?>';
    var price       = '<?=$price?>';
    var category    = '<?=$category_id?>';
    var supplier    = '<?=$supplier_id?>';
    var foc         = '<?=$foc_id?>';

    var pge=$("#pageJump").val();
    pageHistory(name,brand,vintage,country,volume,price,category,supplier,foc_id,pge)
 });

 $('#pageJump').val("<?=$data['curPage'];?>");
 
  function exportReport(){
        

        loadingoverlay('info',"Loading","Please Wait");
        $.post('<?=BASEURL?>Alcohol/ExportAlcohol',{'name':name,'brand':brand,'vintage':vintage,'country':country,'volume':volume,'price':price,'category':category,'supplier':supplier,'foc_id':foc_id},
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

//for import datas

function filterWithId(id) {
       
    url = "<?=BASEURL?>Alcohol/List/?id="+id;
    window.open(url, "_blank"); 
    return false;
}   
 $("#proceedImport").click(function(){


        loadingoverlay('info',"Loading","Please Wait");
        data = new FormData();
        var bulk_id=$("#proceedImport").val();
        data.append('bulk_id',bulk_id);
        console.log(data);
        $.ajax({
              url: '<?=BASEURL;?>Alcohol/updateInventroryFromTemp/', 
              dataType: 'text',  
              cache: false,
              contentType: false,
              processData: false,
              data: data,                         
              type: 'post',
              success: function(response){
                  $('#ListImportModel').modal('toggle');
                  newResp = JSON.parse(response);
                  if(newResp['status'] == 'success')
                    {
                        hideoverlay();
                        openSuccess(newResp['response'],'<?=BASEURL;?>Alcohol/List')  

                    }else
                    {
                        loadingoverlay('error','Error',newResp['response']);  
                    }
                return false;
              }
          }); 


 });

  $(".cancelImport").click(function(){

    bulk_id=$(".cancelImport").val();

     swal({
          title:'Are you sure?',
          text: "Cancel This Import",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Alcohol/DeleteInventroryFromTemp/',{getId:bulk_id},function(response){ 
    
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
    });
function upload(){
    
    data = new FormData();
    data.append('file', $('#file').prop('files')[0]);
    loadingoverlay('info',"Please wait..","loading...");
       $.ajax({
              url: '<?=BASEURL;?>Alcohol/ImportInventoryList/', 
              dataType: 'text',  
              cache: false,
              contentType: false,
              processData: false,
              data: data,                         
              type: 'post',
              success: function(response){
                  $('#excelModal').modal('toggle');
                  newResp = JSON.parse(response);
                  if(newResp['status'] == 'success')
                           {
                            hideoverlay();
                            $('#ListImportModel').modal('toggle');
                            $('#table_content_import').html("");
                            var header = newResp['response']['html']['header'];
                            var valid = newResp['response']['html']['valid'];
                            var invalid = newResp['response']['html']['invalid'];
                            var footer = newResp['response']['html']['footer'];
                            var combinedHTML = header + valid + invalid + footer;
                            $('#table_content_import').html(combinedHTML);
                            $('#proceedImport').val(newResp['response']['bulk_id']);
                            $('.cancelImport').val(newResp['response']['bulk_id']);

                      }else
                           {
                            if(newResp['response']['showlistpopup']) {
                                
                                hideoverlay();
                                $('#ErrorListModal').modal('toggle');
                                $('#table_content').html("");
                                $('#table_content').html(newResp['response']['html']);
                                 
                            }else{
                                loadingoverlay('error','Error',newResp['response']['msg']);  
                            }

                            

                               
                           }
                return false;
              }
          }); 
}
</script>
