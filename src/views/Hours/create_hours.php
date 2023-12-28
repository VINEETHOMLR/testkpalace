
<?php 
use inc\Root;


$this->mainTitle = 'Hours';
$this->subTitle  = 'Add Hours';

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
                            <li class="breadcrumb-item"><a href="<?=BASEURL;?>Hours/Index/">Hours list</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Add Hours</a></li>
                        </ol>
                    </nav>
                    <div class="card-body">
                        <div class="row" >
                            <form role="form" id="regiseterNewUser" method="post" class="col-md-12">
                                <div class="step-1-user">
                                    <div class="panel-body row">
                                        <div class="col-md-8 col-sm-12 statbox widget box box-shadow">
                                                <input type="hidden" id="id" name="id" class="form-control" value="">

                                            <div class="tabcontent col-sm-12 col-md-12">
                                            <label>Name<span style="color:red;">*</span></label>
                                            </div>
                                            <div class="tabcontent form-group col-sm-12 col-md-12">
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" value ="">
                                            </div>


                                            <div class="content col-sm-12 col-md-12">
                                                <label>From Time<span style="color:red;">*</span></label>
                                            </div>
                                           <div class=" content form-group col-sm-12 col-md-12">
                                                <input type="time" id="timepicker" name="from_time" class="form-control" placeholder="Enter From Time" value ="">
                                            </div>

                                            <div class="content col-sm-12 col-md-12">
                                                <label>To Time<span style="color:red;">*</span></label>
                                            </div>
                                            <div class=" content form-group col-sm-12 col-md-12">
                                                <input type="time" id="timepicker" name="to_time" class="form-control" placeholder="Enter To Time" value ="">
                                            </div>

                                            


                                            
                                        </div>  
                                    
                                                     
                                    </div><br><br>
                                    <div class="content panel-footer">
                                        <div class="form-actions text-center">
                                            <button type="button" class="btn btn-info proceedToNext col-12 col-md-3">&nbsp;Add Hours</button>
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
$('#timepi').datetimepicker({
    showOn: "button",
    showSecond: true,
    dateFormat: "dd-mm-yy", 
    timeFormat: "HH:mm:ss"
});
</script>
<script type ="text/javascript">

    // Map your choices to your option value
var lookup = {
   '1': ['K1', 'K2', 'K3', 'K5'],
   '2': ['A1', 'A2', 'A3', 'A5', 'A6'],
   '3': ['B1', 'B2', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10', 'B11', 'B12', 'B13'],
};

// When an option is changed, search the above for matching choices
$('#options').on('change', function() {
   // Set selected option as variable
   var selectValue = $(this).val();

   // Empty the target field
   $('#choices').empty();
   
   // For each chocie in the selected option
   for (i = 0; i < lookup[selectValue].length; i++) {
      // Output choice in the target field
      $('#choices').append("<option value='" + lookup[selectValue][i] + "'>" + lookup[selectValue][i] + "</option>");
   }
});
 
</script>

<script>
$('#accordionExample').find('li a').attr("data-active","false");
$('#settingsMenu').attr("data-active","true");
$('#settingsNav').addClass('show');
$('#Hours').addClass('active');
$("#checkallmem").change(function () {
    $(".checkmem").prop('checked', $(this).prop("checked"));
});



$(function () {
    $('.proceedToNext').click(function(){
        postdata = $('#regiseterNewUser').serializeArray();
        
         $.post('<?=BASEURL;?>Hours/Addnewhours/',postdata,function(response){
            console.log(response);
            newResp = JSON.parse(response);
            if(newResp['status']=='success'){
                openSuccess(newResp['response'],'<?=BASEURL;?>Hours/Index/') 
              

            }else{
                loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
            }
           return false;
        });
    });

});
// $(document).ready(function()
// {
//     if($("selected#test_type").val() =='1')
//     {
//         $("#description").hide();
//     }
// });

$('#test_type').change(function() {  
   var val= $(this).val();   
  $('.content').toggle(val=="1");  
   $.ajax({  
  }); }).change();
$('#test_type').change(function() {  
   var val= $(this).val();   
  $('.tabcontent').toggle(val=="2");  
   $.ajax({  
  }); }).change();



</script>