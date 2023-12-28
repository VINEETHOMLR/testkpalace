
<?php 
use inc\Root;


$this->mainTitle = 'Departments';
$this->subTitle  = 'Edit';

?>

<style type="text/css">
  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }
  .form-control{
    height: 40px;
  }
  .t-group-item {
    color: #fff;
    background-color: #888ea8;
    border-color: transparent;
    box-shadow: 0 1px 15px 1px rgba(52, 40, 104, 0.15);
    border: 1px solid #e0e6ed;
    padding: 10px 12px;
}
  .info-icon {
    border-radius: 50%;
    background: #888ea8;
    display: inline-block;
    padding: 15px;
    margin-bottom: 20px;
    margin-top:-1.5%;
    margin-left: 42%;
}
.info-icon svg {
    width: 50px;
    height: 50px;
    stroke-width: 1px;
    color: #d3d3d3;
}
svg {
    overflow: hidden;
    vertical-align: middle;
}

.card-body {
    width: 98% !important;
}
.panel-body .box-shadow {
  margin: 0px auto;
}

.sub_permission {
  margin-left: 40px;
}

.col-md-3.col-sm-12.statbox.widget.box.box-shadow {
  overflow: auto;
  height: 559px;
}

.step-2-permission{
    display: none;
}
@media only screen and (max-width: 900px) {
  .info-icon {
     margin-top: -8px !important;
     margin-left: 32%;
  }
}
.card-body{
  width: 70%;margin: 0 auto;
}
 @media only screen and (min-width: 600px) and (max-width: 1000px)  {
  .card-body{
    width: 100%;margin: 25px;
  }
}

</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-12">
                <div class="card">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=BASEURL;?>Departments/Index/">Departments list</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Department</a></li>
                        </ol>
                    </nav>
                    <div class="card-body">
                        <div class="row" >
                            <form role="form" id="regiseterNewUser" method="post" class="col-md-12">
                                <div class="step-1-user">
                                    <div class="panel-body row">
                                        <div class="col-md-8 col-sm-12 statbox widget box box-shadow">
                                              

                                             
                                           
                                            <div class="tabcontent col-sm-12 col-md-12">
                                            <label>Name<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="tabcontent form-group col-sm-12 col-md-12">
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value ="<?= $details['name'] ? $details['name'] : ''?>">
                                            </div>


                                            
                                        </div>  
                                    
                                                     
                                    </div>
                                    <br>

                                    <div class="tabcontent panel-footer">
                                        <div class="form-actions text-center">
                                            <button type="button" class="btn btn-info proceedToNext col-12 col-md-3" onclick="save()">&nbsp;Update</button>
                                        </div>
                                    </div>
                                </div>

                              

                            </form>
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

?>
<script type ="text/javascript">

    // Map your choices to your option value


// When an option is changed, search the above for matching choices


</script>

<script>
$('#accordionExample').find('li a').attr("data-active","false");
$('#settingsMenu').attr("data-active","true");
$('#settingsNav').addClass('show');
$('#departmentsList').addClass('active');
$("#checkallmem").change(function () {
    $(".checkmem").prop('checked', $(this).prop("checked"));
});



$(function () {
    

});

function save()
{
   
    data = new FormData();
    data.append('name', $('#name').val());
    data.append('id', '<?= $details['id']?>');
    loadingoverlay('info',"Loading","Please Wait...");
    $.ajax({
              url: '<?=BASEURL;?>Departments/Add/', 
              dataType: 'text',  
              cache: false,
              contentType: false,
              processData: false,
              data: data,                         
              type: 'post',
              success: function(response){ 
                  newResp = JSON.parse(response);
                  if(newResp['status'] == 'success')
                  {
                      openSuccess(newResp['response'],'<?=BASEURL;?>Departments/Index/')  
                  }else
                  {
                      loadingoverlay('error','Error',newResp['response']);
                  }
                return false;
              }
          });    

}





</script>