
<?php 
use inc\Root;

if($details['type']=='1')
{
$this->mainTitle = 'Room';
$this->subTitle  = 'Edit Room';
}
if($details['type']=='2')
{
$this->mainTitle = 'Table';
$this->subTitle  = 'Edit Table';
}

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
                         <?php  if($details['type']=='1'){?>

                            <li class="breadcrumb-item"><a href="<?=BASEURL;?>Room/Index/">List</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Room</a></li>

                             <?php   } ?>

                             <?php  if($details['type']=='2'){?>

                            <li class="breadcrumb-item"><a href="<?=BASEURL;?>Room/Index/">List</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Table</a></li>
                            
                             <?php   } ?>
                        </ol>
                    </nav>
                    <div class="card-body">
                        <div class="row" >
                            <form role="form" id="regiseterNewUser" method="post" class="col-md-12">
                                <div class="step-1-user">
                                    <div class="panel-body row">
                                        <div class="col-md-8 col-sm-12 statbox widget box box-shadow">
                                                <input type="hidden" id="id" name="id" class="form-control" value="<?=$details['id'];?>">

                                         <?php if($details['type']=='1'){?>
                                            <div class="content col-sm-12 col-md-12">
                                                <label>Level<span style="color:red;">*</span></label>
                                            </div>
                                            <div class=" content form-group col-sm-12 col-md-12">
                                                <input type="text" id="level" name="level" class="form-control" placeholder="Enter Level" value ="<?=$details['level'];?>">
                                            </div>
                                            
                                            <div class="content col-sm-12 col-md-12">
                                                <label>Room Number<span style="color:red;">*</span></label>
                                            </div>
                                            <div class=" content form-group col-sm-12 col-md-12">
                                                <input type="text" id="room_no" name="room_no" class="form-control" placeholder="Enter Room Number" value ="<?=$details['room_no'];?>">
                                            </div>

                                            <?php } ?>

                                  <?php if($details['type']=='2'){?>
                                            <div class="content col-sm-12 col-md-12">
                                                <label>Table Number<span style="color:red;">*</span></label>
                                            </div>
                                            <div class=" content form-group col-sm-12 col-md-12">
                                                <input type="text" id="table_no" name="table_no" class="form-control" placeholder="Enter Table Number" value ="<?=$details['room_no'];?>">
                                            </div>
                                        <?php } ?>

                                            <div class="content col-sm-12 col-md-12">
                                                <label>description<span style="color:red;">*</span></label>
                                            </div>


                                            <div class="content form-group col-sm-12 col-md-12">
                                                
                                                <textarea class="form-control" id="description" name="description" spellcheck="false"><?=$details['description'];?></textarea>
                                            </div>

                                            <?php if($details['type']=='1'){?>

                                            <div class="content col-sm-12 col-md-12">
                                                <label>Maximum Allowed<span style="color:red;">*</span></label>
                                            </div>
                                            <div class=" content form-group col-sm-12 col-md-12">
                                                <input type="text" id="maximum_allowed" name="maximum_allowed" class="form-control" placeholder="Enter Maximum Number Allowed" value ="<?=$details['max_allowed'];?>">
                                            </div>

                                            <div class=" col-sm-12 col-md-12" id="myDiv">
                                                <label>Price<span style="color:red;">*</span></label>
                                            </div>
                                            <div class=" content form-group col-sm-12 col-md-12" id="myDiv">
                                                <input type="text"  id="price" name="price" class="form-control input-sm" placeholder="Enter Price" value="<?=$details['price'];?>">
                                            </div>
                                            
                                             <?php }?>

                                            
                                        </div>  
                                    
                                                     
                                    </div><br><br>
                                    <div class="content panel-footer">
                                        <div class="form-actions text-center">
                                            <button type="button" class="btn btn-info proceedToNext col-12 col-md-3">&nbsp;Update</button>
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
$('#createroom').addClass('active');
$("#checkallmem").change(function () {
    $(".checkmem").prop('checked', $(this).prop("checked"));
});



$(function () {
    $('.proceedToNext').click(function(){
        postdata = $('#regiseterNewUser').serializeArray();
        
         $.post('<?=BASEURL;?>Room/Updateroomdetails/',postdata,function(response){
            console.log(response);
            newResp = JSON.parse(response);
            if(newResp['status']=='success'){
                openSuccess(newResp['response'],'<?=BASEURL;?>Room/Index/') 
              

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

// $('#test_type').change(function() {  
//    var val= $(this).val();   
//   $('.content').toggle(val=="1");  
//    $.ajax({  
//   }); }).change();

$('#test_type').change(function() {  
   var val= $(this).val();   
  $('.content').toggle(val=="1");  
   $.ajax({  
  }); }).change();


</script>