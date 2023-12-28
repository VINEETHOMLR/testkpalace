<?php

  use inc\Root;
?>


<script type="text/javascript">

    $(document).ready(function(){

        $("#language a").on("click", function(){
            var langId = $(this).attr("data-id");
            if(langId !=''){
                $.post('<?=BASEURL;?>Index/Language',{'language':langId},function(response){
                    location.reload();
                })
            }
        });
    });


    function loadingoverlay(type, title, desc){ 

        if (type == 'success')
        {
            swal("Success", desc, "success");
            currentLanguage = $(this).val();

            $(".languageChange").each(function () {
                if (currentLanguage == 'english')
                {
                    enVal = $(this).data('english');
                    $(this).html(enVal);
                }
               

            });
        } else if (type == 'error')
        {
            swal(title, desc, "error");
        } else {
            swal({
                title: title,
                text: desc,
                showConfirmButton: false
            });
        }

    }

    function hideoverlay(){

        swal.close();
    } 

    function openSuccess(title,url=''){

        swal({
            title: "Success",
            text: title,
            type: "success",
            showCancelButton: false,
            confirmButtonClass: 'btn-success waves-effect waves-light',
            confirmButtonText: 'Okay',
            closeOnConfirm: false,
        }).then(function(isConfirm) {
            if(url==''){
              location.reload();
            }else{
              $(location).prop('href', url);
            }            
        });
    }

    function logOut(){ 

            $.post('<?=BASEURL;?>Index/Logout/',{'logout':true}, function (response) { 
                newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){ 
                 swal({
                        title: "<?= Root::t('login', 'suc'); ?>",
                        text: "<?= Root::t('login', 'logout_suc'); ?>",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: '<?= Root::t('login', 'ok'); ?>',
                    
                    }).then(function (result) {
                        $(location).prop('href', '<?=BASEURL;?>');
                    })
            }
            else{
                loadingoverlay('error',"<?=Root::t('app','error_dash');?>",newResp['response']);
            }

                return false;
            });
        return false;
    }

    function setdate(fullDate,get=false){
        var dd = fullDate.getDate();
        var mm = fullDate.getMonth()+1;
        var yy = fullDate.getFullYear();

        dd = (dd < 10)?('0'+dd):dd;
        mm = (mm < 10)?('0'+mm):mm;

        if(get){
           return dd+ "-" + mm + "-" + yy;   
        }

        var todayFrom = dd + "-" + mm + "-" + yy ; 

        $('#datefrom').val(todayFrom);
        $('#dateto').val(todayFrom);
    }

    // $(function(){
    //     setInterval(checkBanned(),15000);
    // }); 

    function checkBanned(){

        $.post('<?=BASEURL;?>Index/ISBanned/',{}, function (response) { 

            newResp = JSON.parse(response);

            if(newResp['status'] == 'error'){  

                swal({
                    title: "<?= Root::t('login', 'error_txt'); ?>",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonClass: 'btn-success',
                    confirmButtonText: '<?= Root::t('login', 'ok'); ?>',
                    
                }).then(function (result) {
                    $(location).prop('href', '<?=BASEURL;?>');
                })
            }
        });
        return false;
    }



    $(".tagging").select2({
      tags: false,
      placeholder: 'Exclude Users',
    });

    $('#check').change(function () {

        var user = $('#user_id').val();
  
        if(user!=''){

          if($("#check").prop('checked') == true){
             $("#sub").prop("disabled", false );
             getDownline(user);
          }else{
              $("#sub").prop( "disabled", true );
          }
        }
    });

    $('#user_id').change(function(){
        
        if ($("#check").is(":checked")) {
           var user = $('#user_id').val();
           if(user!=''){
               getDownline(user);
           }
        }
    })

    function getDownline(id){

        var userType = $('#userType').val();

        $.post('<?=BASEURL?>Wallet/Getsponsor',{'Id':id,userType},function(response) {
           
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){
              
              $('#sub').html(newResp['response']);
              if($("#check").prop('checked') == true){
                 $("#sub").prop("disabled", false );
              }else{
                 $("#sub").prop( "disabled", true );
              }
            }else{
              loadingoverlay('error','Error',newResp['message']);
              return false;
            }
        });
    };

$('#user_id').select2({
    placeholder: 'Customer Username/ID',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>Customer/getCustomers',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});
$('#users_ids').select2({
    placeholder: 'Select Uname',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>User/getUsers',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});

/*$('#alcohol_id').select2({
    placeholder: 'Select Product',
    tags: true,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>Inventory/GetItems',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});*/

$('#alcohol_id').select2();
   function clearData(){ 
  
     $("#user_id").val(null).trigger("change"); 
     $("#sub").val(null).trigger("change"); 
     $("#sub").prop( "disabled", true );
   } 

   $('#include_user').select2({
      placeholder: 'Include Users',
      tags: false,
      minimumInputLength:1,
      ajax: {
      url: '<?=BASEURL?>Wallet/data/',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        //$('#exclude_user').prop("disabled",true);
        return {
          results: data
        };
      },
      cache: true
    }
  });

  

  $('#exclude_user').select2({
      placeholder: 'Exclude Users',
      tags: false,
      minimumInputLength:1,
      ajax: {
      url: '<?=BASEURL?>Wallet/data/',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        //$('#include_user').prop("disabled",true);
        return {
          results: data
        };
      },
      cache: true
    }
  });

 

  $('#include_country').select2({
      placeholder: 'Include Country',
      tags: false,
      minimumInputLength:1,
      ajax: {
      url: '<?=BASEURL?>Memo/getCountry/',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        $('#exclude_country').prop("disabled",true);
       // alert(JSON.stringify(data))
        return {
          results: data
        };
      },
      cache: true
    }
  });

   $('#include_country').change(function(){

    if($('#include_country').val()=='')
        $('#exclude_country').prop("disabled",false);
    else
        $('#exclude_country').prop("disabled",true);
  })

  $('#exclude_country').select2({
      placeholder: 'Exclude Country',
      tags: false,
      minimumInputLength:1,
      ajax: {
      url: '<?=BASEURL?>Memo/getCountry/',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        $('#include_country').prop("disabled",true);
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $('#exclude_country').change(function(){
    if($('#exclude_country').val()=='')
        $('#include_country').prop("disabled",false);
    else
        $('#include_country').prop("disabled",true);
  })
   $('#exclude_userr').select2({
      placeholder: 'Exclude Users',
      tags: false,
      minimumInputLength:1,
      ajax: {
      url: '<?=BASEURL?>Customer/getCustomers',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        //var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});
$('#include_userr').select2({
    placeholder: 'Include Users',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>Customer/getCustomers',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        //var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});


$('#item_id').select2({
    placeholder: 'Select Item Name',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>Inventory/GetItems',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});

$(function () {
    
        $('.date-today').click(function () {
            var fullDate = new Date();
            setdate(fullDate);
        });

        $('.date-yesterday').click(function () {
            var today = new Date();
            var fullDate = new Date(today);
            fullDate.setDate(today.getDate() - 1);
            setdate(fullDate);
        });

        $('.date-seven').click(function () {
            var today = new Date();
            var fullDate = new Date(today);
            fullDate.setDate(today.getDate() - 7);

            var todayFrom = setdate(fullDate, true);

            var fullDatea = new Date(today);
            fullDatea.setDate(today.getDate() - 1);

            var todayTo = setdate(fullDatea, true);

            $('#datefrom').val(todayFrom);
            $('#dateto').val(todayTo);
        });

        if ($('#datefrom').length) {
            var f1 = flatpickr(document.getElementById('datefrom'), {
                dateFormat: "d-m-Y",
            });
        }
        if ($('#dateto').length) {
            var f2 = flatpickr(document.getElementById('dateto'), {
                dateFormat: "d-m-Y",
            });
        }
    });

</script>

