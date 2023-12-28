<?php 
use inc\Root;
$this->mainTitle = "Package";
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

</style>

<div id="content" class="main-content">
  <div class="layout-px-spacing">
    <div class="row layout-top-spacing">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
          <div class="row">
            <div class="row col-md-12 col-xs-12">
              <form id="AnnounceForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Package/index/">
                <div class="form-group col-sm-2 ">
                  <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom" onchange="selfrom();">
                </div>
                <div class="form-group col-sm-2">
                  <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date" onchange="selto();">
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
                    <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="<?=Root::t('app','search_txt')?>">
                </div>
              </form>
            </div>

            <div class="row col-md-12 col-lg-12">
                           
              <div class="col-md-5 col-lg-5">
                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Root::t('app','yesterday_txt')?></a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Root::t('app','today_txt')?></a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Root::t('app','7days_txt')?></a> </span>
                                 
              </div>
              <div class="col-md-7 col-lg-7">
                              
                <a href="<?=BASEURL;?>Package/Create/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add Package</button>
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
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Amount</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Percentage</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Descriptions</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['datetime']?></td>
                                           <td><?=$val['amount']?></td>
                                           <td><?=$val['percentage']?></td>
                                           <td><?=$val['descriptions']?></td>
                                           <td><?=$this->statusArry[$val['status']]?></td>
                                           <td><?=$val['action']?></td>
                                       </tr>
                              <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Package/Index/">
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
</div>



  
        

<?php

$error_dash = Root::t('app','error_dash');
$success    = Root::t('app','suucess_txt');
$okay       = Root::t('app','okay_btn'); 
$load1      = Root::t('app','load1_txt');
$load2      = Root::t('app','load2_txt');
?>



<script>

        $(document).ready(function() {

         
        $('#status').val("<?=$status;?>");
        $('#datefrom').val("<?=$datefrom;?>");
        $('#dateto').val("<?=$dateto;?>");
      
        var status   = "<?=$status;?>"; 
        var datefrom = "<?=$datefrom;?>";
        var dateto   = "<?=$dateto;?>";
     
    });
    
    

    $('#search').click(function(){
        $('#AnnounceForm').submit();
    })

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#settingsMenu').attr("data-active","true");
    $('#settingsNav').addClass('show');
    $('#package').addClass('active');




  $("#pageJumpBtn").click(function(){ 
       
        var status   = "<?=$status;?>"; 
        var date_from = "<?=$datefrom;?>";
        var date_to   = "<?=$dateto;?>";
        var pge       =$("#pageJump").val();

    pageHistory(date_from,date_to,status,pge);
    });

    $('#pageJump').val("<?=$data['curPage'];?>");

  function pageHistory(datefrom,dateto,status,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#user_pagination').submit();
  }


</script>

       



