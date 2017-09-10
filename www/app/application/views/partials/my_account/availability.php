<ul class="nav nav-pills">
    <li class="active"><a href="#timeslots" data-toggle="tab">Timeslots</a></li>
    <li><a href="#calendar" data-toggle="tab">Calendar</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="timeslots">
        <? include(APPPATH.'views/partials/my_account/includes/availability-timeslots.php'); ?>
    </div>
    <div class="tab-pane active" id="calendar">
        <? include(APPPATH.'views/partials/my_account/includes/availability-calendar.php'); ?>
    </div>
</div>