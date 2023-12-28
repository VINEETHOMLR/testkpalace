<?php 
use inc\Root;
$this->mainTitle = 'Customer Management';

?>

<style type="text/css">
  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }
  .form-control{
    height: 50px;
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
.breadcrumb-two .breadcrumb li a::before {
    content: none;
  }

  .breadcrumb-two{
        top: 0;
        position: absolute;
        left: 0;
  }
  .card-block{
      border: 1px solid #0ab910;
      padding: 20px;
      border-radius: 5px;
      margin: 10px;
  }
  #thisAdd{
      height: 100px;
      margin-left: 30px;
      margin-top: 10px
  }
  .remove{
      position: absolute;
      top: 0px;
      right: 5px;
      font-weight: bold;
      color: red;
  }
</style>
<link href="<?=WEB_PATH?>assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />
  <link href="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
  <link href="<?=WEB_PATH?>plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet">

<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                  <nav class="breadcrumb-two" aria-label="breadcrumb">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Customer/Index/">Customer</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">

                                            <div class="col-12 col-md-6 form-group">
                                                <label> Username*</label>
                                                <input type="text" name="name" id="name" value="" class="form-control" placeholder="">                                 
                                            </div>
                                             <div class="col-12 col-md-6 form-group">
                                                <label> Referral*</label>
                                                <input type="text" name="referal" id="referal" value="" class="form-control" placeholder="">                                 
                                            </div>
                                          
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Email</label><br>
                                                <input id="email" name="email" class="form-control" type="text" placeholder="" value="">
                                            </div>

                                           <div class="col-12 col-md-6 form-group">
                                                <label> Nationality*</label>
                                   
                                                <select class="form-control custom-select langSel" name="countrycode" id="countrycode">
                                                        
                                                         <option value="">Select Country</option>
                                                         <?php
                                                         

                                                         foreach ($country as $key =>$value) { ?>
                                                                                                                         
                                                             <option value="<?=$value['id']?>" ><?=$value['nicename']?>-<?=$value['iso']?></option>
                                                          
                                                         <?php }
                                                         ?>
                                                        
                                                            

                                                      </select>                                  
                                            </div>
                                            
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Surname*</label><br>
                                                <input id="surname" name="surname" class="form-control" type="text" placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Given Name*</label><br>
                                                <input type="text" id="gname" name="gname" class="form-control " placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Nickname*</label><br>
                                                <input id="nickname" name="nickname" class="form-control" type="text" placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                            

                                                <label> Mobile Code*</label>
                                                <select class="form-control custom-select langSel" name="mobilecode" id="mobilecode">
                                                        
                                                         
                                                      
                                                         <?php
                                                         foreach ($country as $key => $value) {

                                                          $selected = ($value['id']=='192') ? 'selected' : '';
                                                          echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['phonecode'].' - '.$value['nicename'].'</option>';
                                                         }
                                                         ?>
                                                        
                                                      </select>
                                            </div>

                                            <div class="col-12 col-md-6 form-group">
                                                <label>Mobile*</label><br>
                                                <input type="text" id="mobile" name="mobile" class="form-control" type="text" placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label> Gender</label>
                                                <select class="form-control custom-select langSel" name="gender" id="gender">
                                                <option value="">Select Gender</option>
                                                <option value="0">Male</option>
                                                <option value="1">Female</option>
                                                </select>
                                            </div>
                                             <div class="col-12 col-md-6 form-group">
                                                <label>Date Of Birth</label><br>
                                                <input id="date1" name="date1" type='date' min='1899-01-01' max='2000-13-13' class="form-control" type="text" placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                            

                                                <label> Language*</label>
                                                <select class="form-control custom-select langSel" name="language" id="language">
                                                        
                                                         <option value="">Select Language</option>
                                                         <?php
                                                         

                                                         foreach ($lang as $key =>$value) { ?>
                                                                                                                         
                                                             <option value="<?=$value['id']?>"><?=$value['lang_name']?></option>
                                                          
                                                         <?php }
                                                         ?>
                                                        
                                                      </select>
                                            </div>
                                            
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Allergies</label><br>
                                                
                                                <select class="form-control custom-select" id="allergies" name="allergies" multiple="">
                                                  <option>Select</option>
                                                  <?php foreach($allergies as $key=>$value){?>
                                                    <option value="<?= $value['id']?>"><?= $value['name']?></option>
                                                  <?php }?>
                                                  
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Assistant Name</label><br>
                                                <input type="text" id="assname" name="assname" class="form-control" placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Assistant Mobile</label><br>
                                                <input type="text" id="assmobile" name="assmobile" class="form-control" placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Assistant Email</label><br>
                                                <input type="text" id="assemail" name="assemail" class="form-control" placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Account Manager*</label><br>
                                                <input type="text" id="accman" name="accman" class="form-control" placeholder="" value="">
                                            </div>
                                            <div class="col-12 col-md-6 form-group">
                                                <label>Remarks</label><br>
                                                <input type="text" id="remarks" name="remarks" class="form-control" placeholder="" value="">
                                            </div>

                                            <br>
                                           
                                          
                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                <button class="btn btn-primary proceedToPayment col-12 col-md-5" id="save" type="button">Submit</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>

<script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script>


<script type="text/javascript">

        $('#accordionExample').find('li a').attr("data-active","false");
        $('#custMenu').attr("data-active","true");
        $('#custNav').addClass('show');
        $('#createcustomer').addClass('active');
        var f1 = flatpickr(document.getElementById('date1'),{
            dateFormat:"d-m-Y",
            minDate: "01-01-1899",
            maxDate: "today"
          });

        // var today = new Date();
        // var dd = today.getDate();
        // var mm = today.getMonth() + 1; //January is 0!
        // var yyyy = today.getFullYear();
        // if (dd < 10) {
        // dd = '0' + dd
        // }
        // if (mm < 10) {
        // mm = '0' + mm
        // }

        // today = yyyy + '-' + mm + '-' + dd;
        // document.getElementById("date1").setAttribute("max", today);


    $('#save').click(function(){

    var postdata = $('#formValidation').serialize();
    // alert($("#countrycode").val())

    data = new FormData();

    
    data.append('name', $('#name').val());
    data.append('referal', $('#referal').val());
    data.append('email', $('#email').val());
    data.append('countrycode',$('#countrycode').val());
    data.append('surname', $('#surname').val());
    data.append('gname', $('#gname').val());
    data.append('nickname', $('#nickname').val());
    data.append('mobilecode', $('#mobilecode').val());
    data.append('mobile', $('#mobile').val());
    data.append('gender', $('#gender').val());
    data.append('date1', $('#date1').val());
    data.append('language',$('#language').val());
    data.append('allergies', $('#allergies').val());
    data.append('assname', $('#assname').val());
    data.append('assmobile', $('#assmobile').val());
    data.append('assemail',$('#assemail').val());
    data.append('accman',$('#accman').val());
    data.append('remarks', $('#remarks').val());
    
    loadingoverlay('info',"Please Wait...","Loading....");

    $.ajax({
        url: '<?=BASEURL;?>Customer/AddCustomer/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){
        //alert(response); 
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
                 {
                 openSuccess(newResp['response'],'<?=BASEURL;?>Customer/Index/')  
            }else{
                 loadingoverlay('error','error',newResp['response']);
            }
            return false;
        }
    }); 
});

 

  
</script>
