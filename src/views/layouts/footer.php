
<div class="footer-wrapper">
    <div class="f-section-1">
        <p class="">Copyright © <?php echo date('Y') ?> <a target="_blank" href="JAVASCRIPT:VOID(0)"> <?=SITENAME?></a>, All rights reserved.</p>
    </div>
</div>

<script type="text/javascript">

setInterval(function(){ 

$.post('<?=BASEURL;?>Index/GetAdminNotifications/',function(response){

    newResp = JSON.parse(response);

    $('#notificationCount').html("");
    $('#notificationCount').html(newResp['count']);
    if(newResp['count']!='0') {
        
        $('#notificationList').html(""); 
        $('#notificationList').html(newResp['html']); 
    }else{
        $('#notificationList').html(""); 
        $('#notifynotification').removeClass('show');
        $('.notification-dropdown').removeClass('show');
        $("#notificationDropdown").attr("aria-expanded","false");
    }

}); 



 }, 10000);


function notificationread(redirct_notifi_url, notifi_id)
{
        data = new FormData();
        data.append('notifi_id', notifi_id); 

         $.ajax({
              url: '<?=BASEURL;?>Index/Notificationread/', 
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
                      $(location).prop('href', redirct_notifi_url); 
                  }else
                  {
                      loadingoverlay('error','Error',newResp['response']);
                  }
                return false;
              }
          });  
}

</script>