<?php
use inc\Root;

$this->mainTitle = 'Leave Management';
$this->subTitle  = 'Apply Leave';

$this->admin_services = $_SESSION[SITENAME . '_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME . '_admin_role'];
?>

<style type="text/css">
.breadcrumb-two .breadcrumb li a::before {
    content: none;
}

.form-control {
    height: 50px;
}

@media only screen and (max-width: 900px) {
    .info-icon {
        margin-top: -8px !important;
        margin-left: 32%;
    }
}

.card-body {
    width: 70%;
    margin: 0 auto;
}

@media only screen and (min-width: 600px) and (max-width: 1000px) {
    .card-body {
        width: 100%;
        margin: 25px;
    }
}

.breadcrumb-two .breadcrumb li a::before {
    content: none;
}

.breadcrumb-two {
    top: 0;
    position: absolute;
    left: 0;
}

.card-block {
    border: 1px solid #0ab910;
    padding: 20px;
    border-radius: 5px;
    margin: 10px;
}

#thisAdd {
    height: 100px;
    margin-left: 30px;
    margin-top: 10px
}

.remove {
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
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"> -->


<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=BASEURL;?>LeaveRequest/Index/">Leave Management</a>
                            </li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"><?=$this->subTitle?></a>
                            </li>
                        </ol>
                    </nav><br>

                    <form id="formValidation" class="form-horizontal" role="form">
                        <div class="info">
                            <div class="row">
                                <div class="col-md-11 mt-4 mb-4 box1 rw1" id="html_content">
                                    <div class="row">

                                        <div class="col-12 col-md-6 form-group">
                                            <label> Staff <span style="color:red">* </span></label>
                                            <select type="option" name="user_ids" class="form-control custom-select"
                                                id="user_ids">
                                                <option value="">Select Staff</option>
                                                <?php if(!empty($customer_name)):?>
                                                <option value="<?=$user_id?>" selected><?=$customer_name?></option>
                                                <?php endif;?>
                                            </select>


                                        </div>

                                        <div class="col-12 col-md-6 form-group hde">
                                            <label> Staff ID </label>
                                            <input id="staff_id" type="text" class="input-group form-control"
                                                disabled="disabled">
                                        </div>

                                        <div class="col-12 col-md-6 form-group hde">
                                            <label> Department </label>
                                            <input id="department" type="text" class="input-group form-control"
                                                disabled="disabled">
                                        </div>

                                        <div class="col-12 col-md-6 form-group hde">
                                            <label> Position </label>
                                            <input id="position" type="text" class="input-group form-control"
                                                disabled="disabled">
                                        </div>

                                        <div class="col-12 col-md-6 form-group">
                                            <label> Leave <span style="color:red">* </span> </label>
                                            <select class="form-control" id="leave_id">
                                                <option value="">Select</option>
                                                <?php foreach($types as $key => $value) {?>
                                                <option value='<?= $value['id']?>'><?= $value['leave_name']?></option>

                                                <?php } ?>


                                            </select>

                                            <span class="leave_hde tag-primary">
                                                <span>Total Leaves : <b id="total"></b></span>
                                                <span>Balance : <b id="balance"></b></span>
                                        </div>


                                        <div class="col-12 col-md-6 form-group">
                                            <label> Type <span style="color:red">* </span></label>
                                            <select class="form-control" id="leave_type">
                                                <option value="">Select</option>
                                                <option value='1'>Full Day</option>
                                                <option value='2'>Half Day</option>


                                            </select>
                                        </div>



                                        <div class="col-12 col-md-6 form-group">
                                            <label>From Date <span style="color:red">* </span></label>
                                            <input id="date_from" name="date_from" type='date' min='1899-01-01'
                                                max='2000-13-13' class="input-group form-control" type="text"
                                                placeholder="">
                                        </div>

                                        <div class="col-12 col-md-6 form-group">
                                            <label>To Date <span style="color:red">* </span></label>
                                            <input id="date_to" name="date_to" type='date' min='1899-01-01'
                                                max='2000-13-13' class="input-group form-control" type="text"
                                                placeholder="">
                                        </div>

                                        <div class="col-12 col-md-6 form-group">
                                            <label for="uploadedFile">Uploaded File:</label>
                                            <input type="file" class="form-control-file" id="uploadedFile"><br>

                                        </div>






                                        <div class="col-12 col-md-6 form-group">


                                            <label> Reason <span style="color:red">* </span></label>
                                            <textarea class="form-control" id='reason'></textarea>

                                        </div>





                                    </div>


                                    <div class="col-md-12  text-center" style="margin-top: 2%;">
                                        <button class="btn btn-primary proceedToPayment col-12 col-md-5" id="save"
                                            type="button"
                                            onclick="applyLeave()"><?=Root::t('announcement', 'submit')?></button>
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
    $('#accordionExample').find('li a').attr("data-active", "false");
    $('#LeaveMenu').attr("data-active", "true");
    $('#LeaveNav').addClass('show');
    $('#LeaveRequestList').addClass('active');

    $('[data-toggle="tooltip"]').tooltip();
    var f1 = flatpickr(document.getElementById('date_from'), {
        dateFormat: "d-m-Y",
        minDate: "today",

    });

    var f1 = flatpickr(document.getElementById('date_to'), {
        dateFormat: "d-m-Y",
        minDate: "today",

    });



    $(".hde").hide();
    $(".leave_hde").hide();



    $('#user_ids').change(function() {

        var user_id = $(this).val();
        data = new FormData();
        data.append('user_id', user_id);
        $.ajax({
            url: '<?=BASEURL;?>LeaveRequest/getStaffData/',
            dataType: 'text',
            data: data,
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                //alert(response);
                newResp = JSON.parse(response);
                $('.hde').show();
                $('#staff_id').val(newResp['staffid']);
                $('#department').val(newResp['department']);
                $('#position').val(newResp['position']);

            }
        });

    });


    $('#leave_id').change(function() {

        var leave_id = $(this).val();
        var user_id = $('#user_ids').val();
        data = new FormData();
        data.append('user_id', user_id);
        data.append('leave_id', leave_id);

        $.ajax({
            url: '<?=BASEURL;?>LeaveRequest/getLeaveBalance/',
            dataType: 'text',
            data: data,
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                newResp = JSON.parse(response);

                $('#total').html(newResp['total']);
                $('#balance').html(newResp['balance']);
                $('.leave_hde').show();


            }
        });


    });

    function applyLeave() {


        data = new FormData();
        data.append('leave_id', $('#leave_id').val());
        data.append('user_id', $('#user_ids').val());
        data.append('leave_type', $('#leave_type').val());
        data.append('date_from', $('#date_from').val());
        data.append('date_to', $('#date_to').val());
        data.append('reason', $('#reason').val());
        data.append('file', $('#uploadedFile').prop('files')[0]);
        loadingoverlay('info', "Loading", "Please Wait...");
        $.ajax({
            url: '<?=BASEURL;?>LeaveRequest/ApplyLeave/',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: 'post',
            success: function(response) {
                newResp = JSON.parse(response);


                if (newResp['status'] == 'success') {
                    openSuccess(newResp['response'], '<?=BASEURL;?>LeaveRequest/Index/')
                } else {
                    loadingoverlay('error', 'Error', newResp['response']);
                }
                return false;
            }
        });

    }





    $('#user_ids').select2({
        placeholder: 'Select Staff',
        tags: false,
        minimumInputLength: 1,
        ajax: {
            url: '<?=BASEURL?>LeaveRequest/GetUsers',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                //var userType = $('#userType').val();
                return {
                    term: params.term, // search term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
    </script>
    <style type="text/css">
    .select2 {
        min-width: 10em !important;
    }
    </style>