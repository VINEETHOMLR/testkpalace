<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Customer</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['customer_name']?></span></div>
</div>

<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Contact No</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['contact_no']?></span></div>
</div>

<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Room No/Table No</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['room_no']?></span></div>
</div>
<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Book Date</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['booked_date']?></span></div>
</div>

<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Hour Type</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['hour_name']?></span></div>
</div>

<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Time</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['hour_time']?></span></div>
</div>

<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Rsvp Type</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['rsvp_type']?></span></div>
</div>

<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Mummy Name</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['mummy_name']?></span></div>
</div>
<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Receptionist No</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['receptionist_no']?></span></div>
</div>
<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>PR Manager</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['pr_manager']?></span></div>
</div>
<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Brought In By</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['brought_in_by']?></span></div>
</div>
<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Male Count</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['male_count']?></span></div>
</div>
<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Female Count</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['female_count']?></span></div>
</div>

<?php if($details['room_type']==1){?>
<div class="row">
    <div class="col-md-6">
        <span style="line-height: 1;"><b>Merge Room</b>&nbsp;&nbsp;:</span></div>
    <div class="col-md-6"><span  style="line-height: 1;" id="orderid"><?= $details['merge_room']?></span></div>
</div>
<?php } ?>